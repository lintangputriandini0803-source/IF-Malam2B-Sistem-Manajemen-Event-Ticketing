<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListItemController extends Controller
{
    public function show($id=null, $name=null){
        return view('list_item',compact('id','name'));
    }
}
