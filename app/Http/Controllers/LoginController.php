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
        return view('auth.login');
    }

    public function authenticate(Request $request){

        $credentials = $request->validate([
            'usuario' => 'required',
            'password' => 'required'
        ]);

        Debugbar::error($credentials);

        $credentials['estado'] = 1;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }



        return back()->withErrors([
            'user' => 'El usuario o contraseña es incorrecto.'
        ])->onlyInput('user');
    }
}
