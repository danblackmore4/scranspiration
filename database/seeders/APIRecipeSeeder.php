<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use App\Services\OpenFoodFactsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class ApiRecipeSeeder extends Seeder
{
    private function storeSeederImage(string $url, string $filename): ?string
    {
        try {
            $response = Http::get($url);

            if ($response->failed()) {
                return null;
            }

            $path = 'recipe_images/' . $filename;
            Storage::disk('public')->put($path, $response->body());

            return $path; // what goes into $recipe->image
        } catch (\Exception $e) {
            return null;
        }
    }


    public function run(): void
    {
        /** @var OpenFoodFactsService $off */
        $off = app(OpenFoodFactsService::class);

        $defaultRole = \App\Models\Role::firstOrCreate(['name' => 'user']);
        $creatorRole = \App\Models\Role::firstOrCreate(['name' => 'creator']);

        
        // Ensure some users exist (with correct roles)
        $users = collect([
            ['name' => 'Dan',   'email' => 'dan@example.com',   'role' => 'creator'],
            ['name' => 'Alice', 'email' => 'alice@example.com', 'role' => 'user'],
            ['name' => 'Bob',   'email' => 'bob@example.com',   'role' => 'user'],
        ])->map(function ($u) use ($creatorRole, $defaultRole) {

            $roleId = $u['role'] === 'creator'
                ? $creatorRole->id
                : $defaultRole->id;

            return User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name'     => $u['name'],
                    'password' => 'password',
                    'role_id'  => $roleId,
                ]
            );
        });


    

        // Ensure some categories exist
        $categories = collect([
            'Bulking',
            'Cutting',
            'Balanced',
            'High Protein',
        ])->mapWithKeys(function ($name) {
            $category = Category::firstOrCreate(['name' => $name]);
            return [$name => $category];
        });

        // Define your recipes + ingredients (amount in grams)
        $recipesData = [
            [
                'title'       => 'High-Protein Chicken & Rice Bowl',
                'description' => 'Grilled chicken breast with fluffy white rice and steamed broccoli – a simple, high-protein gym classic.',
                'category'    => 'Bulking',
                'servings'    => 2,
                'instructions' => "1. Cook the rice.\n2. Grill or pan-fry the chicken.\n3. Steam the broccoli.\n4. Combine in a bowl and season with salt, pepper, and your favourite herbs.",
                'image'       => 'https://media.chefdehome.com/740/0/0/teriyaki/teriyaki-chicken-rice-bowl.jpg',
                'ingredients' => [
                    ['search' => 'chicken breast', 'amount' => 250],
                    ['search' => 'white rice cooked', 'amount' => 200],
                    ['search' => 'broccoli', 'amount' => 150],
                ],
            ],
            [
                'title'       => 'Overnight Protein Oats',
                'description' => 'Creamy overnight oats with protein powder and banana, perfect as a pre- or post-workout breakfast.',
                'category'    => 'Balanced',
                'servings'    => 1,
                'instructions' => "1. Mix oats, milk and protein powder.\n2. Stir in sliced banana.\n3. Refrigerate overnight.\n4. Top with a little peanut butter before serving.",
                'image'       => 'https://lemonsandzest.com/wp-content/uploads/2021/07/Protein-Overnight-Oats-3.12.jpg',
                'ingredients' => [
                    ['search' => 'rolled oats', 'amount' => 60],
                    ['search' => 'skimmed milk', 'amount' => 200],
                    ['search' => 'banana', 'amount' => 80],
                ],
            ],
            [
                'title'       => 'Lean Turkey Lettuce Wraps',
                'description' => 'Seasoned lean turkey mince wrapped in crisp lettuce leaves for a low-carb, high-protein meal.',
                'category'    => 'Cutting',
                'servings'    => 2,
                'instructions' => "1. Brown turkey mince in a pan.\n2. Season with spices of your choice.\n3. Wash and separate lettuce leaves.\n4. Spoon turkey into leaves and serve.",
                'image'       => 'https://cdn.apartmenttherapy.info/image/upload/f_jpg,q_auto:eco,c_fill,g_auto,w_1500,ar_4:3/k%2FPhoto%2FSeries%2F2020-01-Snapshot-Ground-Turkey%2FSnapshot-Ground-Turkey-Lettuce-Wraps_052',
                'ingredients' => [
                    ['search' => 'turkey mince', 'amount' => 250],
                    ['search' => 'lettuce', 'amount' => 80],
                    ['search' => 'olive oil', 'amount' => 10],
                ],
            ],
            [
                'title'       => 'Greek Yogurt Protein Parfait',
                'description' => 'Thick Greek yogurt layered with berries and crunchy granola.',
                'category'    => 'Balanced',
                'servings'    => 1,
                'instructions' => "1. Add half of the yogurt to a bowl or glass.\n2. Layer berries and granola.\n3. Add remaining yogurt and repeat layers.\n4. Chill briefly before serving.",
                'image'       => 'https://spicecravings.com/wp-content/uploads/2023/09/Greek-Yogurt-Parfait-Featured.jpg',
                'ingredients' => [
                    ['search' => 'greek yogurt 0%', 'amount' => 170],
                    ['search' => 'mixed berries', 'amount' => 80],
                    ['search' => 'granola', 'amount' => 30],
                ],
            ],
            [
                'title'       => 'Salmon, Sweet Potato & Greens',
                'description' => 'Baked salmon with roasted sweet potato and green beans – perfect for healthy bulking.',
                'category'    => 'Bulking',
                'servings'    => 2,
                'instructions' => "1. Roast cubed sweet potato.\n2. Bake salmon fillets in the oven.\n3. Steam green beans.\n4. Serve everything together, seasoned to taste.",
                'image'       => 'https://images.immediate.co.uk/production/volatile/sites/30/2020/08/sesame-salmon-and-purple-broccoli-24cb460.jpg?quality=90&resize=440,400',
                'ingredients' => [
                    ['search' => 'salmon fillet', 'amount' => 240],
                    ['search' => 'sweet potato', 'amount' => 250],
                    ['search' => 'green beans', 'amount' => 150],
                ],
            ],
            [
                'title'       => 'Tofu Stir-Fry with Brown Rice',
                'description' => 'Crispy tofu with mixed vegetables over nutty brown rice.',
                'category'    => 'Balanced',
                'servings'    => 2,
                'instructions' => "1. Cook brown rice.\n2. Stir-fry tofu until golden.\n3. Add mixed vegetables and soy sauce.\n4. Serve over rice.",
                'image'       => 'https://shwetainthekitchen.com/wp-content/uploads/2014/03/Brown-Rice-Stir-Fry.jpg',
                'ingredients' => [
                    ['search' => 'tofu firm', 'amount' => 200],
                    ['search' => 'brown rice cooked', 'amount' => 200],
                    ['search' => 'mixed vegetables frozen', 'amount' => 150],
                ],
            ],
            [
                'title'       => 'Egg White Veggie Omelette',
                'description' => 'Fluffy egg whites with peppers and spinach – a low-fat, high-protein breakfast.',
                'category'    => 'Cutting',
                'servings'    => 1,
                'instructions' => "1. Whisk egg whites.\n2. Sauté peppers and spinach.\n3. Pour egg whites over veg and cook until set.\n4. Fold and serve.",
                'image'       => 'https://beautifuleatsandthings.com/wp-content/uploads/2018/05/Veggie-Stuffed-Egg-White-Omelet2.jpg',
                'ingredients' => [
                    ['search' => 'egg white', 'amount' => 150],
                    ['search' => 'spinach', 'amount' => 50],
                    ['search' => 'red bell pepper', 'amount' => 50],
                ],
            ],
            [
                'title'       => 'Beef & Quinoa Power Bowl',
                'description' => 'Lean beef mince with fluffy quinoa and roasted vegetables.',
                'category'    => 'High Protein',
                'servings'    => 2,
                'instructions' => "1. Cook quinoa.\n2. Brown lean beef mince.\n3. Roast mixed vegetables.\n4. Serve together in a bowl.",
                'image'       => 'https://theyummybowl.com/wp-content/uploads/ground-beef-and-quinoa-skillet-11.jpg',
                'ingredients' => [
                    ['search' => 'lean beef mince', 'amount' => 220],
                    ['search' => 'quinoa cooked', 'amount' => 200],
                    ['search' => 'mixed vegetables', 'amount' => 150],
                ],
            ],
            [
                'title'       => 'Protein Smoothie',
                'description' => 'Quick whey protein smoothie with banana and oats.',
                'category'    => 'Balanced',
                'servings'    => 1,
                'instructions' => "1. Add milk, protein powder, banana and oats to a blender.\n2. Blend until smooth.\n3. Serve immediately.",
                'image'       => 'https://images.immediate.co.uk/production/volatile/sites/30/2024/06/Berry-Smoothie-14f662b.jpg',
                'ingredients' => [
                    ['search' => 'whey protein powder', 'amount' => 30],
                    ['search' => 'banana', 'amount' => 80],
                    ['search' => 'semi skimmed milk', 'amount' => 200],
                    ['search' => 'rolled oats', 'amount' => 30],
                ],
            ],
            [
                'title'       => 'Chickpea & Tuna Salad',
                'description' => 'High-protein salad with tuna, chickpeas and crunchy vegetables.',
                'category'    => 'Cutting',
                'servings'    => 2,
                'instructions' => "1. Drain and rinse chickpeas.\n2. Mix with canned tuna.\n3. Add chopped cucumber and tomato.\n4. Dress lightly with olive oil and lemon.",
                'image'       => 'https://dishingouthealth.com/wp-content/uploads/2021/05/ChickpeaTunaSalad_Square.jpg',
                'ingredients' => [
                    ['search' => 'canned tuna in water', 'amount' => 150],
                    ['search' => 'chickpeas canned', 'amount' => 150],
                    ['search' => 'cucumber', 'amount' => 80],
                    ['search' => 'tomato', 'amount' => 80],
                ],
            ],
            [
                'title'       => 'Peanut Butter Banana Toast',
                'description' => 'Wholegrain toast with peanut butter and sliced banana – simple bulk snack.',
                'category'    => 'Bulking',
                'servings'    => 1,
                'instructions' => "1. Toast the bread.\n2. Spread peanut butter.\n3. Top with sliced banana.\n4. Optional: drizzle honey.",
                'image'       => 'https://www.justspices.co.uk/media/recipe/Cocoa-Banana-Porridge-Spice_Peanut-butter-banana-toast_1.webp',
                'ingredients' => [
                    ['search' => 'wholemeal bread', 'amount' => 60],
                    ['search' => 'peanut butter', 'amount' => 30],
                    ['search' => 'banana', 'amount' => 60],
                ],
            ],
            [
                'title'       => 'Cottage Cheese & Pineapple Bowl',
                'description' => 'Low-fat cottage cheese with pineapple chunks, great as a light high-protein snack.',
                'category'    => 'Cutting',
                'servings'    => 1,
                'instructions' => "1. Add cottage cheese to a bowl.\n2. Top with pineapple chunks.\n3. Chill or serve immediately.",
                'image'       => 'https://www.fyffes.com/wp-content/uploads/2021/08/Pineapple-and-cottage-cheese-Bowl.jpg',
                'ingredients' => [
                    ['search' => 'cottage cheese low fat', 'amount' => 150],
                    ['search' => 'pineapple pieces canned in juice', 'amount' => 80],
                ],
            ],
            [
                'title'       => 'Prawn & Avocado Rice Salad',
                'description' => 'Light salad with prawns, avocado and rice – high in protein and healthy fats.',
                'category'    => 'Balanced',
                'servings'    => 2,
                'instructions' => "1. Cook and cool rice.\n2. Mix with cooked prawns.\n3. Add diced avocado and cucumber.\n4. Dress with lemon juice and a little olive oil.",
                'image'       => 'https://img.hellofresh.com/hellofresh_s3/image/prawn-and-avocado-salad-8a7702cc-81ed0bfe.jpg',
                'ingredients' => [
                    ['search' => 'prawns cooked', 'amount' => 200],
                    ['search' => 'avocado', 'amount' => 80],
                    ['search' => 'white rice cooked', 'amount' => 150],
                ],
            ],
            [
                'title'       => 'Lentil & Spinach Dahl',
                'description' => 'Comforting lentil dahl with spinach, great for plant-based protein.',
                'category'    => 'Balanced',
                'servings'    => 3,
                'instructions' => "1. Simmer red lentils in stock.\n2. Add spices and tomatoes.\n3. Stir in spinach at the end.\n4. Serve with rice or on its own.",
                'image'       => 'https://www.nourishandtempt.com/wp-content/uploads/2020/01/dahl22-scaled.jpg',
                'ingredients' => [
                    ['search' => 'red lentils dry', 'amount' => 180],
                    ['search' => 'spinach', 'amount' => 80],
                    ['search' => 'chopped tomatoes canned', 'amount' => 200],
                ],
            ],
            [
                'title'       => 'Turkey & Sweet Potato Hash',
                'description' => 'Diced sweet potato with lean turkey and peppers, pan-fried.',
                'category'    => 'High Protein',
                'servings'    => 2,
                'instructions' => "1. Dice and parboil sweet potatoes.\n2. Brown turkey mince.\n3. Add sweet potato and peppers and fry together.\n4. Season and serve.",
                'image'       => 'https://paleoglutenfreeguy.com/wp-content/uploads/2024/08/ground-turkey-sweet-potato-hash-6.jpg',
                'ingredients' => [
                    ['search' => 'turkey mince', 'amount' => 220],
                    ['search' => 'sweet potato', 'amount' => 220],
                    ['search' => 'red bell pepper', 'amount' => 60],
                ],
            ],
            [
                'title'       => 'Chicken Pesto Pasta',
                'description' => 'Chicken breast with pasta and a light pesto sauce.',
                'category'    => 'Bulking',
                'servings'    => 2,
                'instructions' => "1. Cook pasta.\n2. Pan-fry chicken breast.\n3. Toss cooked pasta with pesto and sliced chicken.\n4. Season and serve.",
                'image'       => 'https://www.theburntbuttertable.com/wp-content/uploads/2025/03/Chicken-pesto-pasta-card.jpg',
                'ingredients' => [
                    ['search' => 'chicken breast', 'amount' => 250],
                    ['search' => 'pasta cooked', 'amount' => 220],
                    ['search' => 'pesto', 'amount' => 40],
                ],
            ],
            [
                'title'       => 'Greek Chicken Salad',
                'description' => 'Grilled chicken over salad with tomatoes, cucumber, olives and a little feta.',
                'category'    => 'Cutting',
                'servings'    => 2,
                'instructions' => "1. Grill chicken breast and slice.\n2. Assemble salad leaves, tomatoes, cucumber and olives.\n3. Top with chicken and a little feta.\n4. Drizzle with olive oil and lemon.",
                'image'       => 'https://gimmedelicious.com/wp-content/uploads/2024/08/Greek-Chopped-Chicken-Salad-6.jpg',
                'ingredients' => [
                    ['search' => 'chicken breast', 'amount' => 220],
                    ['search' => 'feta cheese', 'amount' => 40],
                    ['search' => 'olives', 'amount' => 30],
                    ['search' => 'lettuce', 'amount' => 80],
                ],
            ],
            [
                'title'       => 'Bulk Gainer Shake',
                'description' => 'High-calorie shake with oats, milk, peanut butter and banana.',
                'category'    => 'Bulking',
                'servings'    => 1,
                'instructions' => "1. Add milk, oats, peanut butter and banana to a blender.\n2. Blend until smooth.\n3. Adjust thickness with more milk if needed.",
                'image'       => 'https://www.krispykreme.co.uk/media/catalog/product/c/h/chocolate_shake_result.png?quality=80&fit=bounds&height=&width=&canvas=:',
                'ingredients' => [
                    ['search' => 'whole milk', 'amount' => 300],
                    ['search' => 'rolled oats', 'amount' => 60],
                    ['search' => 'peanut butter', 'amount' => 30],
                    ['search' => 'banana', 'amount' => 80],
                ],
            ],
            [
                'title'       => 'Shrimp & Broccoli Stir-Fry',
                'description' => 'Simple prawn and broccoli stir-fry served with rice.',
                'category'    => 'High Protein',
                'servings'    => 2,
                'instructions' => "1. Stir-fry prawns.\n2. Add broccoli florets and continue cooking.\n3. Season with soy sauce.\n4. Serve with cooked rice.",
                'image'       => 'https://heavenlyhomecooking.com/wp-content/uploads/2024/10/Shrimp-and-Broccoli-Stir-Fry-Recipe-Featured-3.jpg',
                'ingredients' => [
                    ['search' => 'prawns cooked', 'amount' => 200],
                    ['search' => 'broccoli', 'amount' => 150],
                    ['search' => 'soy sauce', 'amount' => 20],
                ],
            ],
        ];

        // Create recipes + ingredients + pivot data
        foreach ($recipesData as $data) {
            $user     = $users->random();
            $category = $categories[$data['category']];

            /** @var Recipe $recipe */
            $recipe = Recipe::create([
                'title'       => $data['title'],
                'description' => $data['description'],
                'category_id' => $category->id,
                'user_id'     => $user->id,
                'servings'    => $data['servings'],
                'instructions'=> $data['instructions'],
                'image' => $this->storeSeederImage(
                    $data['image'],                                   // URL
                    str::slug($data['title']) . '.jpg'                 // filename
                ),
                // totals will be set after attaching ingredients
                'calories'    => 0,
                'protein'     => 0,
                'carbs'       => 0,
                'fats'        => 0,
            ]);

            $totalCalories = 0;
            $totalProtein  = 0;
            $totalCarbs    = 0;
            $totalFats     = 0;

            foreach ($data['ingredients'] as $ingredientData) {
                $search = $ingredientData['search'];
                $amount = $ingredientData['amount']; // grams

                $products = $off->search($search, 1);

                if (empty($products)) {
                    Log::warning("No OFF result for '{$search}' – skipping ingredient for recipe {$recipe->title}");
                    continue;
                }

                $product = $products[0];

                // Map OFF product → Ingredient model
                $ingredient = Ingredient::firstOrCreate(
                    ['barcode' => $product['id']],
                    [
                        'name'                => $product['name'],
                        'brand'               => $product['brand'] ?? null,
                        'calories_per_100g'   => $product['nutrients']['calories_100g'] ?? 0,
                        'protein_per_100g'    => $product['nutrients']['protein_100g'] ?? 0,
                        'carbs_per_100g'      => $product['nutrients']['carbs_100g'] ?? 0,
                        'fats_per_100g'       => $product['nutrients']['fats_100g'] ?? 0,
                        'image_url'           => null, // could be extended if you add field in service
                    ]
                );

                // Calculate macros for this specific amount in grams
                $cals   = $ingredient->calories_per_100g * $amount / 100;
                $prot   = $ingredient->protein_per_100g * $amount / 100;
                $carbs  = $ingredient->carbs_per_100g * $amount / 100;
                $fats   = $ingredient->fats_per_100g * $amount / 100;

                $recipe->ingredients()->attach($ingredient->id, [
                    'amount'   => $amount,
                    'calories' => $cals,
                    'protein'  => $prot,
                    'carbs'    => $carbs,
                    'fats'     => $fats,
                ]);

                $totalCalories += $cals;
                $totalProtein  += $prot;
                $totalCarbs    += $carbs;
                $totalFats     += $fats;
            }

            // Update totals on the recipe itself
            $recipe->update([
                'calories' => round($totalCalories),
                'protein'  => round($totalProtein, 1),
                'carbs'    => round($totalCarbs, 1),
                'fats'     => round($totalFats, 1),
            ]);
        }
    }
}
