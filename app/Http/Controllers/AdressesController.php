<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adresses;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdressesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'alamat' => 'required',
                'longitude' => 'required',
                'latitude' => 'required',
            ],
            [
                'alamat.title' => 'Masukkan Alamat',
            ]
        );
        $userInfo = auth('api')->user();
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ], 401);
        } else {
            $alamat = Adresses::create([
                'user_id' =>  auth('api')->user()->id,
                'alamat' => $request->alamat,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
            ]);
            if ($alamat) {
                return response()->json([
                    'success' => 'True',
                    'data' => $alamat
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Alamat Gagal Disimpan!',
                ], 401);
            }
        }
    }
    //public function show($id)
    //{
    //    //$user = Auth::user();
    //    //$alamat = $user->adresses;
    //    $data_user = User::find($id);
    //    $post = User::find($id)->Adresses;
    //
    //
    //    if ($post) {
    //        return response()->json([
    //            'success' => true,
    //            'message' => 'Alamat User ' . $data_user->name,
    //            'data'    => $post
    //        ], 200);
    //    } else {
    //        return response()->json([
    //            'success' => false,
    //            'message' => 'Post Tidak Ditemukan!',
    //            'data'    => ''
    //        ], 401);
    //    }
    //}
    public function showAlamat()
    {
        try {
            $user_id = auth('api')->user()->id;
            $data = User::find($user_id)->Adresses;
            return response()->json(['status' => 'true', 'message' => 'Alamat User', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
}
