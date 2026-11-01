<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 32)->index();
            $table->string('session_id', 32)->index();
            $table->boolean('is_new')->default(false);    // first ever page view of this visitor
            $table->boolean('is_entry')->default(false);  // first page view of a visit (session)
            $table->string('path', 255)->index();
            $table->string('referrer_host', 150)->nullable();
            $table->string('referrer_url', 500)->nullable();
            $table->string('source', 100)->nullable();
            $table->string('medium', 40)->nullable();
            $table->string('utm_campaign', 120)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('device_type', 12)->nullable();
            $table->string('browser', 40)->nullable();
            $table->string('os', 40)->nullable();
            $table->string('language', 10)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['created_at', 'is_entry']);
        });

        foreach (['demo_requests', 'quote_requests'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->string('visitor_id', 32)->nullable()->index();
                $table->string('source', 100)->nullable();
                $table->string('medium', 40)->nullable();
                $table->string('campaign', 120)->nullable();
                $table->string('landing_path', 255)->nullable();
                $table->char('country_code', 2)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');

        foreach (['demo_requests', 'quote_requests'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropColumn(['visitor_id', 'source', 'medium', 'campaign', 'landing_path', 'country_code']);
            });
        }
    }
};
