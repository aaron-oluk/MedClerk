<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbook_entries', function (Blueprint $table) {
            $table->string('encounter_type')->nullable()->after('skill_id');
            $table->foreignId('signed_off_by')->nullable()->after('notes')->constrained('users')->nullOnDelete();
            $table->timestamp('signed_off_at')->nullable()->after('signed_off_by');
            $table->uuid('client_uuid')->nullable()->unique()->after('id');

            $table->index('encounter_type');
        });
    }

    public function down(): void
    {
        Schema::table('logbook_entries', function (Blueprint $table) {
            $table->dropIndex(['encounter_type']);
            $table->dropConstrainedForeignId('signed_off_by');
            $table->dropColumn(['encounter_type', 'signed_off_at', 'client_uuid']);
        });
    }
};
