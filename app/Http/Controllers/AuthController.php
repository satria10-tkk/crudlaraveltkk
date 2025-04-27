<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    public function login(){
        return view ('auth/login');
    }

    public function loginProses(Request $request){
        $request->validate([
            'email'        => 'required',
            'password'     => 'required|min:8',
        ],[
            'email.required'           => 'Email tidak boleh kosong',
            'password.required'        => 'Password tidak boleh kosong',
            'password.min'             => 'Password minimal 8 karakter',
        ]);

        $data = array(
            'email'        => $request->email,
            'password'     => $request->password,
        );

        if (Auth::attempt($data)) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with('error', 'Email atau Password salah');
        }
    }
}