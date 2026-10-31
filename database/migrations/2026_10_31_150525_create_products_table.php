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
        Schema::create('products', function (Blueprint $table) {
            $table->string('id', 12)->unique()->primary();
            $table->string('name', 150);
            $table->string('slug', 200)->unique();
            $table->string('tagline', 255)->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('category', 100)->index();          // education, hr, retail, finance...
            $table->string('image', 200)->nullable();
            $table->json('features')->nullable();
            $table->json('modules')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
