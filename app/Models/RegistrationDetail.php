<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationDetail extends Model
{
    protected $fillable = ['registration_id', 'ticket_type_id', 'quantity', 'price'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }
}
