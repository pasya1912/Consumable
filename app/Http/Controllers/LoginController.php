<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use db facade
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index()
    {
        $users = DB::table('users')->select('username')->get()->toArray();
        //users username to single array
        $users = array_map(function($user){
            return $user->username;
        }, $users);
        return view('layouts.login',[
            'users' => $users
        ]);
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

        return redirect()->back()->with('error', 'Username or password do not match our records!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.index');
    }
}
