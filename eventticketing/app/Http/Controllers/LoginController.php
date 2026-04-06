<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class LoginController extends Controller
{
    //untuk login
    public function index(){
        return view('login');
    }

    public function login(Request $request){
        $username= $request->username;
        $password= $request->password;

        if ($username=='admin' && $password== 'admin'){
            return redirect ('/dashboard');
        } else {
            return back()->with('error','Username atau password salah!');
        }
    }
}
