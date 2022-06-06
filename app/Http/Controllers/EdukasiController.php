<?php

namespace App\Http\Controllers;

use App\Models\Edukasi;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Auth;

use App\Http\Middleware\JwtMiddleware;

class EdukasiController extends Controller
{
    public function index()
    {
        $this->middleware('auth:api');
        $edukasi = Edukasi::orderBy('id', 'ASC')->get();
        $response = [
            'message' => 'Ini adalah Edukasi',
            'data' => $edukasi
        ];

        return response()->json($response, Response::HTTP_OK);
    }
    public function search($id)
    {
        $edukasi = Edukasi::find($id);
        $response = [
            'message' => 'Ini adalah Edukasi',
            'data' => $edukasi
        ];

        return response()->json($response, Response::HTTP_OK);
    }
    public function create(Request $request)
    {
        $edukasi = new Edukasi([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'foto_edukasi' => $request->foto_edukasi->store('public/images'),
            'video_edukasi' => $request->video_edukasi->store('public/images'),
        ]);
        $edukasi->save();
        return response('Data Berhasil ditambahkan');
        //$edukasi = new Edukasi;
        //$edukasi->judul = $request->judul;
        //$edukasi->deskripsi = $request->deskripsi;
        //$edukasi = $request->foto_edukasi->store('public/images');
        //$edukasi = $request->video_edukasi->store('public/images');
        //$edukasi->save();
        //return response()->json(['message' => 'Edukasi Berhasil ditambahkan']);
    }
    public function update(Request $request, $id)
    {
        $edukasi = Edukasi::find($id);
        if ($edukasi) {
            $edukasi->judul = $request->judul;
            $edukasi->deskripsi = $request->deskripsi;
            $edukasi->update();
            return response()->json(['message' => 'Data Berhasil diubah'], 200);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 404);
        }
        //$edukasi = new Edukasi;
        //$edukasi->judul = $request->judul;
        //$edukasi->deskripsi = $request->deskripsi;
        //$edukasi = $request->foto_edukasi->store('public/images');
        //$edukasi = $request->video_edukasi->store('public/images');
        //$edukasi->save();
        //return response()->json(['message' => 'Edukasi Berhasil ditambahkan']);
    }
    public function delete($id)
    {
        $edukasi = Edukasi::find($id);
        $result = $edukasi->delete();
        if ($result) {
            return response()->json(['message' => 'Data Berhasil dihapus'], 200);
        } else {
            return ["result" => "Data tidak ditemukan"];
        }
    }
}
