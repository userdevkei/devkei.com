<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
        });

        foreach (['demo_requests', 'quote_requests'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->text('admin_notes')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_admin'));

        foreach (['demo_requests', 'quote_requests'] as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->dropColumn('admin_notes'));
        }
    }
};
