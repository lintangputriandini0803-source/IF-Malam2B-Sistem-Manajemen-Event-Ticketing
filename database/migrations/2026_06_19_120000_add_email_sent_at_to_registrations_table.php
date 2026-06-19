<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Menandai kapan email tiket terkirim untuk order ini.
            // Dipakai sebagai guard supaya email tidak terkirim dobel
            // (karena bisa dipicu dari summary() ATAU notification() webhook).
            $table->timestamp('email_sent_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('email_sent_at');
        });
    }
};
