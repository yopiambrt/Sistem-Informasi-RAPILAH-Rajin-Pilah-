<?php

namespace App\Http\Controllers;

use App\Models\TrashTypes;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;

class TrashTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.role:Pengguna');
    }
    public function index()
    {
        $trash_types = TrashTypes::orderBy('id', 'ASC')->get();
        $response = [
            'message' => 'Daftar Jenis sampah',
            'data' => $trash_types
        ];

        return response()->json($response, Response::HTTP_OK);
    }
    public function create(Request $request)
    {
        $trash_types = new TrashTypes([
            'jenis_sampah' => $request->jenis_sampah,
            'images' => $request->images,
            'harga' => $request->harga,
            'quantity' => $request->quantity,
        ]);
        $trash_types->save();
        return response('Data Berhasil ditambahkan');
    }
}
