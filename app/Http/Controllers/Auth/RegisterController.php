<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email',
            'no_hp' => 'required|string|numeric',
            'password' => 'required|confirmed'
        ]);
        $user = new User([
            'role' => 'Pengguna',
            'name' => $request->username,
            'jenis_kelamin' => 'Laki-Laki',
            'tanggal_lahir' => '1999-12-12',
            'username' => $request->username,
            'email' => $request->email,
            'alamat' => 'Alamat',
            'foto_profil' => 'foto_profil.jpg',
            'no_hp' => $request->no_hp,
            'point' => '0',
            'password' => Hash::make($request->password),
        ]);

        $user->save();
        return response()->json([
            'success' => 'True',
            'message' => 'Regristrasi Berhasil',
            'data' => $user
        ], 200);
    }
}
