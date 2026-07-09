<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->json('equipment')->nullable()->after('procedure_steps');
            $table->unsignedInteger('est_minutes')->nullable()->after('equipment');
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['equipment', 'est_minutes']);
        });
    }
};
