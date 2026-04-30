<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            // Deskripsi/benefit tiket yang ditampilkan ke pengunjung
            $table->text('description')->nullable()->after('name');
            // Batas waktu khusus (misal: Early Bird tutup 24 jam pertama)
            $table->datetime('closes_at')->nullable()->after('sale_end');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropColumn(['description', 'closes_at']);
        });
    }
};
