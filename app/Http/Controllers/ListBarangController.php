<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListBarangController extends Controller
{
    public function index()
    {
        $barangs = [
            ['id' => 1, 'nama' => 'Laptop'],
            ['id' => 2, 'nama' => 'Mouse'],
            ['id' => 3, 'nama' => 'Keyboard'],
        ];
    
        return view('list_barang', compact('barangs'));
    }
}
