<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('revision_topics', function (Blueprint $table) {
            $table->string('exam_type')->default('primary')->after('slug')->index();
        });

        Schema::table('mock_papers', function (Blueprint $table) {
            $table->string('exam_type')->default('primary')->after('slug')->index();
        });
    }

    public function down(): void
    {
        Schema::table('revision_topics', function (Blueprint $table) {
            $table->dropColumn('exam_type');
        });

        Schema::table('mock_papers', function (Blueprint $table) {
            $table->dropColumn('exam_type');
        });
    }
};
