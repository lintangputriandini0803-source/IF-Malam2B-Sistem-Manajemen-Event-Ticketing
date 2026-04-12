<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        
        $event = "Konser Musik Batam";
        $harga = "150000";
        
        return view('event_list', compact('event', 'harga'));
    }
}