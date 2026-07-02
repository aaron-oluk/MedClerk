<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('email');
            $table->foreignId('institution_id')->nullable()->after('role')->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->after('institution_id')->constrained()->nullOnDelete();
            $table->string('student_number')->nullable()->after('department_id');

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('institution_id');
            $table->dropConstrainedForeignId('department_id');
            $table->dropColumn(['role', 'student_number']);
        });
    }
};
