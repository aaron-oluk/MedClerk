<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rotation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('clinical_sign_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('skill_id')->nullable()->constrained()->nullOnDelete();
            $table->date('encounter_date');
            $table->json('findings')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('encounter_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_entries');
    }
};
