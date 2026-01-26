<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('exam_type', 20)->default('primary')->after('name');
            $table->index('exam_type');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->string('exam_type', 20)->default('primary')->after('slug');
            $table->index('exam_type');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->string('exam_type', 20)->default('primary')->after('topic_id');
            $table->index('exam_type');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['exam_type']);
            $table->dropColumn('exam_type');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->dropIndex(['exam_type']);
            $table->dropColumn('exam_type');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropIndex(['exam_type']);
            $table->dropColumn('exam_type');
        });
    }
};
