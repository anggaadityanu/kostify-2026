<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('home_hero_title')->nullable()->after('about_features');
            $table->text('home_hero_subtitle')->nullable()->after('home_hero_title');
            $table->json('home_carousel_images')->nullable()->after('home_hero_subtitle');
            $table->string('home_cta_title')->nullable()->after('home_carousel_images');
            $table->text('home_cta_description')->nullable()->after('home_cta_title');
            $table->string('home_cta_image')->nullable()->after('home_cta_description');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_hero_title',
                'home_hero_subtitle',
                'home_carousel_images',
                'home_cta_title',
                'home_cta_description',
                'home_cta_image',
            ]);
        });
    }
};