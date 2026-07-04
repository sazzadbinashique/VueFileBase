<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->string('banner_eyebrow')->nullable()->after('title_bn');
            $table->string('banner_eyebrow_bn')->nullable()->after('banner_eyebrow');
            $table->string('banner_title')->nullable()->after('banner_eyebrow_bn');
            $table->string('banner_title_bn')->nullable()->after('banner_title');
            $table->text('banner_description')->nullable()->after('banner_title_bn');
            $table->text('banner_description_bn')->nullable()->after('banner_description');
            $table->longText('layout_json')->nullable()->after('meta_description');
            $table->longText('layout_json_bn')->nullable()->after('layout_json');
        });
    }

    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->dropColumn([
                'banner_eyebrow',
                'banner_eyebrow_bn',
                'banner_title',
                'banner_title_bn',
                'banner_description',
                'banner_description_bn',
                'layout_json',
                'layout_json_bn',
            ]);
        });
    }
};
