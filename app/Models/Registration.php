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
        'status',
    ];

    protected $casts = [
        'total_price'    => 'decimal:2',
        'email_sent_at'  => 'datetime',
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
    public static function generateRegNumber(): string
    {
        $year  = now()->format('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('EVT-%s-%06d', $year, $count);
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
