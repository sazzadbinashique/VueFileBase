<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('title_bn')->nullable()->after('title');
            $table->string('icon')->nullable()->after('slug');
            $table->string('color')->default('primary')->after('icon');
            $table->text('short_en')->nullable()->after('description');
            $table->text('short_bn')->nullable()->after('short_en');
            $table->json('body_en')->nullable()->after('short_bn');
            $table->json('body_bn')->nullable()->after('body_en');
            $table->json('impact_en')->nullable()->after('body_bn');
            $table->json('impact_bn')->nullable()->after('impact_en');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->string('title_bn')->nullable()->after('title');
            $table->text('description_bn')->nullable()->after('description');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->string('title_bn')->nullable()->after('title');
            $table->text('description_bn')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['title_bn', 'icon', 'color', 'short_en', 'short_bn', 'body_en', 'body_bn', 'impact_en', 'impact_bn']);
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['title_bn', 'description_bn']);
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn(['title_bn', 'description_bn']);
        });
    }
};
