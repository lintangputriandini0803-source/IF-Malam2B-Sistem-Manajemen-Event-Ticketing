<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'ticket_type_id',
        'reg_number',
        'name',
        'email',
        'phone',
        'quantity',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($registration) {
            $registration->reg_number = static::generateRegNumber();
        });
    }

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
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
