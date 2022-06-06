<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $token = auth()->attempt($request->only('email', 'password'));

        if (!$token) {
            return response()->json(['error' => 'Email atau Password Salah!'], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => auth()->user(),
            'address' => \App\Models\Adresses::where('user_id', auth()->user()->id)->first()
        ]);
    }
}
