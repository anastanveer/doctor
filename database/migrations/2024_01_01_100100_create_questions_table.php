<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('difficulty')->nullable();
            $table->text('stem');
            $table->text('explanation')->nullable();
            $table->text('answer_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('shuffle_options')->default(true);
            $table->unsignedInteger('time_limit')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
