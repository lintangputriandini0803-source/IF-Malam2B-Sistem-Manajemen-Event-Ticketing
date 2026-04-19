<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_type_id')->constrained()->onDelete('restrict');
            $table->string('reg_number')->unique()->comment('Nomor registrasi unik, misal: EVT-2026-000001');
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');
            $table->timestamps();

            // Cegah email mendaftar event yang sama (via ticket_type_id)
            // Catatan: constraint ini di-handle di aplikasi, bukan DB,
            // karena satu email bisa beli beberapa jenis tiket berbeda dalam event yang sama.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
