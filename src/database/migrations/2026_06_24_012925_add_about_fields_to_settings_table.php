<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('about_title')->nullable()->after('email');
            $table->text('about_description')->nullable()->after('about_title');
            $table->string('about_image')->nullable()->after('about_description');
            $table->json('about_features')->nullable()->after('about_image');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['about_title', 'about_description', 'about_image', 'about_features']);
        });
    }
};