<?php

namespace App\Http\Controllers;

use App\Models\Event; 
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        
        $events = Event::all(); 

        // Mengirim data tersebut ke view 'home'
        return view('home', compact('events'));
    }
}