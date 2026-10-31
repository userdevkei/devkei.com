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
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->string('id', 12)->primary()->unique();
            $table->string('product_id', 12)->nullable();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->string('name', 150);
            $table->string('email', 100);
            $table->string('phone', 30);
            $table->string('organization', 150);
            $table->string('organization_type', 50)->nullable();
            $table->unsignedInteger('user_count')->nullable();
            $table->string('hosting_preference', 30)->nullable(); // self_host | devkei_host | lease
            $table->text('requirements');
            $table->string('budget_range', 50)->nullable();
            $table->string('status', 20)->default('new')->index();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
