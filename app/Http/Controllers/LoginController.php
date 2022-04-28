<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function index()
    {
        if(Auth::check()){
            return redirect()->intended('/');
        }

        return view('auth.login');
    }

    public function authenticate(Request $request){

        $credentials = $request->validate([
            'usuario' => 'required',
            'password' => 'required'
        ]);

        $credentials['estado'] = 1;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'user' => 'Usuario o contraseña incorrecto'
        ])->onlyInput('user');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }
}
