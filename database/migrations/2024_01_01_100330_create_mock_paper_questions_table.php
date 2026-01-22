<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_paper_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_paper_id')
                ->constrained('mock_papers')
                ->cascadeOnDelete();
            $table->string('topic')->nullable();
            $table->text('stem');
            $table->text('explanation')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_paper_questions');
    }
};
