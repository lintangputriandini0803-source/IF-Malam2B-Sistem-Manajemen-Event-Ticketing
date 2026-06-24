<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Registration extends Model
{
    protected $fillable = [
        'event_id',
        'ticket_type_id',
        'reg_number',
        'order_ref',
        'name',
        'nim',
        'email',
        'phone',
        'quantity',
        'total_price',
        'payment_method',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'total_price'    => 'decimal:2',
        'email_sent_at'  => 'datetime',
        'expires_at'     => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($registration) {
            $registration->reg_number = static::generateRegNumber();
            if (empty($registration->order_ref)) {
                $registration->order_ref = strtoupper(Str::random(2)) . '-' . rand(10000, 99999);
            }
        });
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }
    public function details(): HasMany
    {
        return $this->hasMany(RegistrationDetail::class);
    }

    // ─── Helper methods ───────────────────────────────────────────────────────

    /**
     * Bug fix (Critical): generateRegNumber() sebelumnya pakai count()+1, yang
     * race condition kalau dua checkout jalan bersamaan — keduanya bisa membaca
     * count yang sama sebelum salah satu commit, lalu gagal saat insert karena
     * reg_number sama (duplicate entry di constraint unique). Sekarang pakai
     * lockForUpdate() pada baris reg_number terakhir tahun ini supaya transaksi
     * lain harus menunggu, jadi nomor berikutnya selalu unik.
     */
    public static function generateRegNumber(): string
    {
        $year   = now()->format('Y');
        $prefix = "EVT-{$year}-";

        $lastNumber = static::where('reg_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('reg_number')
            ->value('reg_number');

        $next = $lastNumber ? ((int) substr($lastNumber, strlen($prefix))) + 1 : 1;

        return $prefix . sprintf('%06d', $next);
    }

    public function getTotalPrice(): float
    {
        return (float) ($this->ticketType->price * $this->quantity);
    }

    public function confirm(): void
    {
        $this->update(['status' => 'confirmed']);
    }
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
