<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Tambah NIM/NIK penanggung jawab
            $table->string('nim')->nullable()->after('name');
            // Referensi pemesanan (XX-12345)
            $table->string('order_ref')->nullable()->after('reg_number');
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['nim', 'order_ref']);
        });
    }
};
