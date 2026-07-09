<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rotations', function (Blueprint $table) {
            $table->unsignedInteger('required_encounters')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('rotations', function (Blueprint $table) {
            $table->dropColumn('required_encounters');
        });
    }
};
