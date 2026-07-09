<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinical_signs', function (Blueprint $table) {
            $table->string('eponym')->nullable()->after('name');
            $table->json('red_flags')->nullable()->after('diagnostic_relevance');
            $table->string('difficulty')->default('core')->after('red_flags');
            $table->date('last_reviewed')->nullable()->after('difficulty');
            $table->string('media_type')->default('text')->after('media_urls');
            $table->string('media_duration')->nullable()->after('media_type');
        });
    }

    public function down(): void
    {
        Schema::table('clinical_signs', function (Blueprint $table) {
            $table->dropColumn(['eponym', 'red_flags', 'difficulty', 'last_reviewed', 'media_type', 'media_duration']);
        });
    }
};
