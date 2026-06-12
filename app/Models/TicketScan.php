<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketScan extends Model
{
    protected $fillable = ['ticket_code', 'event_id', 'scanned_at'];

    public $timestamps = true;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
