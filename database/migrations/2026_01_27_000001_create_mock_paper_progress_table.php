<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_paper_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mock_paper_id')->constrained('mock_papers')->cascadeOnDelete();
            $table->unsignedSmallInteger('active_index')->default(0);
            $table->json('state')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'mock_paper_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_paper_progress');
    }
};
