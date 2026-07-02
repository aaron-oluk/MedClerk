<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->string('curriculum_version')->nullable();
            $table->timestamp('assessed_at');
            $table->timestamps();

            $table->index('student_id');
            $table->index('assessed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
