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
        Schema::create('demo_requests', function (Blueprint $table) {
            $table->string('id', 12)->unique()->primary();
            $table->string('product_id', 12)->nullable();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('organization');
            $table->string('organization_type', 50)->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time', 20)->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('demo_requests');
    }
};
