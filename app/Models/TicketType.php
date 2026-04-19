<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quota',
        'sold',
        'sale_start',
        'sale_end',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'sale_start' => 'datetime',
        'sale_end'   => 'datetime',
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

    // ─── Business logic (kuota) ───────────────────────────────────────────────

    public function getRemainingQuota(): int
    {
        return max(0, $this->quota - $this->sold);
    }

    public function isAvailable(int $qty = 1): bool
    {
        return $this->getRemainingQuota() >= $qty;
    }

    public function isSaleOpen(): bool
    {
        $now = now();
        $afterStart = $this->sale_start ? $now->gte($this->sale_start) : true;
        $beforeEnd  = $this->sale_end   ? $now->lte($this->sale_end)   : true;
        return $afterStart && $beforeEnd;
    }

    /**
     * Kurangi kuota secara atomik menggunakan DB lock
     * agar tidak terjadi race condition saat banyak user beli bersamaan.
     */
    public function decreaseQuota(int $qty): bool
    {
        $updated = self::where('id', $this->id)
            ->where('sold', '<=', \DB::raw("quota - {$qty}"))
            ->increment('sold', $qty);

        return $updated > 0;
    }
}
