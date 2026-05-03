<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quota',
        'sold',
        'sale_start',
        'sale_end',
        'closes_at',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'sale_start' => 'datetime',
        'sale_end'   => 'datetime',
        'closes_at'  => 'datetime',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // ─── Business logic ───────────────────────────────────────────────────────

    public function getRemainingQuota(): int
    {
        return max(0, $this->quota - $this->sold);
    }

    /** Cek apakah tiket masih dalam periode penjualan (sale_start / sale_end) */
    public function isSaleOpen(): bool
    {
        $now = now();
        $afterStart = $this->sale_start ? $now->gte($this->sale_start) : true;
        $beforeEnd  = $this->sale_end   ? $now->lte($this->sale_end)   : true;
        return $afterStart && $beforeEnd;
    }

    /** Cek apakah belum melewati batas waktu khusus (mis. early bird 24 jam) */
    public function isWithinTimeLimit(): bool
    {
        if (! $this->closes_at) return true;
        return now()->lte($this->closes_at);
    }

    /**
     * Gabungan: tiket tersedia jika kuota cukup DAN waktu masih terbuka
     */
    public function isAvailable(int $qty = 1): bool
    {
        return $this->getRemainingQuota() >= $qty
            && $this->isSaleOpen()
            && $this->isWithinTimeLimit();
    }

    /**
     * Status tiket untuk ditampilkan di UI:
     * 'available'   → bisa dibeli
     * 'sold_out'    → habis kuota
     * 'time_closed' → habis waktu (closes_at)
     * 'not_open'    → belum/sudah di luar sale_start/sale_end
     */
    public function getStatus(): string
    {
        if (! $this->isSaleOpen())           return 'not_open';
        if (! $this->isWithinTimeLimit())    return 'time_closed';
        if ($this->getRemainingQuota() <= 0) return 'sold_out';
        return 'available';
    }

    /**
     * Sisa waktu closes_at dalam format human-readable.
     * Return null jika tidak ada closes_at.
     */
    public function getClosesAtHuman(): ?string
    {
        if (! $this->closes_at) return null;
        if (now()->gt($this->closes_at)) return 'Waktu habis';
        return $this->closes_at->diffForHumans(now(), true);
    }

    /**
     * Kurangi kuota secara atomik menggunakan DB lock
     */
    public function decreaseQuota(int $qty): bool
    {
        $updated = self::where('id', $this->id)
            ->where('sold', '<=', \DB::raw("quota - {$qty}"))
            ->increment('sold', $qty);

        return $updated > 0;
    }
}
