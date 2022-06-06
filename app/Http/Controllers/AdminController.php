<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.role:admin');
    }
    public function hitungPengguna()
    {
        $count = User::where('role', '=', 'Pengguna')->get();
        $hitung = $count->count();
        if ($count) {
            return response()->json([
                'success' => 'True',
                'data' => $hitung
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data',
            ], 401);
        }
    }
    public function hitungPetugas()
    {
        $count = User::where('role', '=', 'Petugas')->get();
        $hitung = $count->count();
        if ($count) {
            return response()->json([
                'success' => 'True',
                'data' => $hitung
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data',
            ], 401);
        }
    }
}
