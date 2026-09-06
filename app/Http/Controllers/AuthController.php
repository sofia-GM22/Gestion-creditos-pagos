<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function mostrarFormulario()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credenciales)) {
            return back()
                ->withErrors(['username' => 'Usuario o contraseña incorrectos.'])
                ->onlyInput('username');
        }

        $usuario = Auth::user();

        if ($usuario->estado !== 'Activo') {
            Auth::logout();
            return back()->withErrors(['username' => 'Esta cuenta está desactivada.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}