<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            // 'active'   → tampil & bisa dibeli (subjek ke aturan sale_start/sale_end/quota seperti biasa)
            // 'inactive' → disembunyikan dari halaman publik, dipakai sebagai pengganti hard-delete
            //              untuk tipe tiket yang sudah punya transaksi (sold > 0)
            $table->string('status')->default('active')->after('sold');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
