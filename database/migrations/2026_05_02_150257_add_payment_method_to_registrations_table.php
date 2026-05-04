<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom payment_method sudah ditambahkan di migration sebelumnya
        // (2026_04_29_193307_add_payment_columns_to_registrations.php)
        // Migration ini di-skip agar tidak duplikat
        if (! Schema::hasColumn('registrations', 'payment_method')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('payment_method')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        // Tidak drop karena kolom ini dipakai migration lain
    }
};
