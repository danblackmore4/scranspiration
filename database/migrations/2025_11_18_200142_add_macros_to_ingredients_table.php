<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->float('calories_per_100g')->default(0);
            $table->float('protein_per_100g')->default(0);
            $table->float('carbs_per_100g')->default(0);
            $table->float('fats_per_100g')->default(0);
            $table->string('brand')->nullable();
            $table->string('barcode')->nullable();
            $table->string('image_url')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            //
        });
    }
};
