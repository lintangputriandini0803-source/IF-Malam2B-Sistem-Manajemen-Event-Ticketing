<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'poster',
        'status',
        // tambahkan kolom lain yang sudah ada di tabel kamu
    ];

    // Tambahkan relasi ini
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
}
