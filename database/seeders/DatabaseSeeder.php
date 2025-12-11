<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Profile;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * Create Roles (creator, user)
         */
        $creatorRole = Role::firstOrCreate(['name' => 'creator']);
        $userRole    = Role::firstOrCreate(['name' => 'user']);

        /**
         * Create Categories (Bulking, Cutting, Balanced)
         */
        $categories = Category::factory()->count(3)->create();

        /**
         * Create Users with Roles + Profiles
         */
        User::factory(10)->create()->each(function ($user) use ($userRole) {

            // Every generated user is a normal user
            $user->role_id = $userRole->id;
            $user->save();

            // Create profile
            Profile::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        /**
         * Run the API-powered recipe seeder
         * (This also creates Dan as a 'creator')
         */
        $this->call(ApiRecipeSeeder::class);
    }
}
