<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('layouts.login');
    }

    public function authenticate(Request $request)
    {

        $credentials = $request->validate([
            'username' => 'required',

            'password' => 'required'
        ]);

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            //check if user is admin
            if(Auth::user()->role == 0)
            {
                return redirect()->route('admin.dashboard');
            }
            else
            {
                return redirect()->route('user.dashboard');
            }
        }

        return redirect()->back()->with('error', 'Email or password do not match our records!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.index');
    }
}
