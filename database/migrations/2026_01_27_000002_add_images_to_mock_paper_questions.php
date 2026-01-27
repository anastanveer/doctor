<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mock_paper_questions', function (Blueprint $table) {
            $table->string('image', 512)->nullable()->after('stem');
            $table->string('image_alt', 160)->nullable()->after('image');
            $table->string('explanation_image', 512)->nullable()->after('explanation');
            $table->string('explanation_image_alt', 160)->nullable()->after('explanation_image');
        });
    }

    public function down(): void
    {
        Schema::table('mock_paper_questions', function (Blueprint $table) {
            $table->dropColumn(['image', 'image_alt', 'explanation_image', 'explanation_image_alt']);
        });
    }
};
