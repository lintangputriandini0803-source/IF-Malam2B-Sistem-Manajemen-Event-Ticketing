<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'event_date',
        'end_date',
        'event_time',
        'location',
        'poster',
        'status',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    /**
     * Cek apakah event sudah berakhir.
     * Pakai end_date jika ada, fallback ke event_date.
     */
    public function isExpired(): bool
    {
        $endDate = $this->end_date ?? $this->event_date;
        if (! $endDate) return false;

        try {
            // Bandingkan tanggal saja (bukan datetime), event berakhir setelah hari itu lewat
            return Carbon::parse($endDate)->endOfDay()->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format tanggal event untuk ditampilkan.
     * Jika ada end_date berbeda, tampilkan range: "21 Jun 2026 – 23 Jun 2026"
     */
    public function getFormattedDateRange(): string
    {
        try {
            $start = Carbon::parse($this->event_date)->locale('id')->isoFormat('D MMM YYYY');

            if ($this->end_date && $this->end_date !== $this->event_date) {
                $end = Carbon::parse($this->end_date)->locale('id')->isoFormat('D MMM YYYY');
                return "{$start} – {$end}";
            }

            return $start;
        } catch (\Exception $e) {
            return $this->event_date ?? '-';
        }
    }
}
