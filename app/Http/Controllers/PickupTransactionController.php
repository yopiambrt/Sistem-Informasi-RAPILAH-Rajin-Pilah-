<?php

namespace App\Http\Controllers;

use App\Models\Adresses;
use App\Models\InfoPickupSnapshoot;
use App\Models\PickupCheckout;
use App\Models\PickupOrders;
use App\Models\PickupSnapshoot;
use App\Models\PickupTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Validator;

class PickupTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function show()
    {
        $user_id = auth('api')->user()->id;
        $posts = PickupOrders::whereDate('created_at', Carbon::today())->get();
        $data = $posts->where('user_id', $user_id);
        $estimasi = $data->sum('total_harga');
        if ($data == null) {
            return response()->json([
                'status' => 'false',
                'message' => 'Tidak ada Transaksi Hari ii',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 'true',
                'message' => 'Pickup Transaction',
                'data' => $data,
                'estimasi' => $estimasi
            ]);
        }
    }
    public function create(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $posts = PickupOrders::whereDate('created_at', Carbon::today())->get();
        $user = User::find($user_id);
        $data = $posts->where('user_id', $user_id);
        $estimasi = $data->sum('total_harga');

        $validator = Validator::make(
            $request->all(),
            [],
            [
                //'alamat.title' => 'Masukkan Alamat',
            ]
        );
        if ($estimasi > $user->point) { // Memeriksa Apakah Point User Cukup
            return response()->json([
                'success' => false,
                'message' => 'Point Anda Tidak Cukup untuk melanjutkan Transaksi ini. Point Anda Adalah ' . $user->point,
                'data'    => $validator->errors()
            ], 500);
        } else {
            $pickup_transaction = PickupTransaction::create([
                'user_id' =>  auth('api')->user()->id,
                'hari_pickup' => $request->hari_pickup,
                'jam_pickup' => $request->jam_pickup,
                'adresses_id' => $request->adresses_id,
                'total_harga' => $estimasi,
                'foto_bukti' => $request->foto_bukti->store('public/images'),
                'status' => 'Pending'
            ]);
            $sisa_point = $user->point - $estimasi;
            $update_user_point = User::find($user_id)->update([
                'point' => $sisa_point
            ]);
            if ($pickup_transaction) {
                return response()->json([
                    'success' => 'True',
                    'data' => $pickup_transaction
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal Transaksi',
                ], 401);
            }
        }
    }
    public function showAlamat()
    {
        $user_id = auth('api')->user()->id;
        $data_transaction = User::find($user_id)->transaction;
        foreach ($data_transaction as $key => $value) {
        }
        $address_id =  $value->adresses_id;
        $data = Adresses::find($address_id);
        return response()->json([
            'success' => 'True',
            'message' => 'Alamat yang dipilih',
            'data' => $data
        ], 200);
    }
    public function showAlamatByAdmin(Request $request)
    {
        $hasil = PickupTransaction::select(['pickup_transaction.*', 'adresses.alamat', 'users.name'])->join('adresses', 'pickup_transaction.adresses_id', '=', 'adresses.id')
        ->leftJoin('users', 'pickup_transaction.user_id', '=', 'users.id')
        ->where('users.name', 'like', '%'.$request->q.'%')
        ->get();

        return response()->json([
            'success' => 'True',
            'data' => $hasil,
        ], 200);
    }

    public function deletePickupByAdmin($id)
    {
        PickupTransaction::find($id)->delete();
        return response()->json(['success' => 'True', 204]);
    }

    public function pickupSnapshoot(Request $request)
    {
        $jumlah_data = PickupSnapshoot::count();
        if ($jumlah_data == 0) {
            foreach ($request->list_jenis as $key => $value) {
                $no = "RV01";
                $tanggal = Carbon::today();
                $user_id = auth('api')->user()->id;
                $jenis = [
                    'user_id' => $user_id,
                    'no_order' => $no,
                    'tanggal' => $tanggal,
                    'jenis_sampah' => $value['jenis_sampah'],
                    'berat_sampah' => $value['berat_sampah'],
                    'harga_sampah' => $value['harga_sampah'],
                    'total_harga' => $value['berat_sampah'] * $value['harga_sampah'],
                ];
                $jenis = PickupSnapshoot::create($jenis);
            }
            $user_id = auth('api')->user()->id;
            $info_pickup = InfoPickupSnapshoot::create([
                'user_id' =>  $user_id,
                'no_order' => $no,
                'foto_bukti' => $request->file('image'),
                'address_id' => $request->address_id,
                'hari_penjemputan' => $request->hari_penjemputan,
                'waktu_penjemputan' => $request->waktu_penjemputan,
                'informasi_tambahan' => $request->informasi_tambahan,
            ]);
            if ($jenis) {
                return response()->json([
                    'success' => 'True',
                    'code' => 200,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'Gagal',
                ], 401);
            }
        } else {
            $data = PickupSnapshoot::all()->last();
            $no_akhir = $data->no_order;
            $no_order = ++$no_akhir;
            $user_id = auth('api')->user()->id;
            $tanggal = Carbon::today();
            foreach ($request->list_jenis as $key => $value) {
                $no = $no_order;
                $array = 0;
                $p_array = ++$array;
                $jenis = [
                    'user_id' => $user_id,
                    'no_order' => $no,
                    'tanggal' => $tanggal,
                    'jenis_sampah' => $value['jenis_sampah'],
                    'berat_sampah' => $value['berat_sampah'],
                    'harga_sampah' => $value['harga_sampah'],
                    'total_harga' => $value['berat_sampah'] * $value['harga_sampah'],
                ];
                $jenis = PickupSnapshoot::create($jenis);
            }
            $user_id = auth('api')->user()->id;
            $tanggal = Carbon::today();
            $info_pickup = InfoPickupSnapshoot::create([
                'user_id' =>  $user_id,
                'no_order' => $jenis['no_order'],
                'foto_bukti' => $request->file('image'),
                'address_id' => $request->address_id,
                'hari_penjemputan' => $request->hari_penjemputan,
                'waktu_penjemputan' => $request->waktu_penjemputan,
                'informasi_tambahan' => $request->informasi_tambahan,
                'tanggal' => $tanggal,
            ]);
            //$validator = Validator::make($request->all(), [
            //    'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
            //]);
            //
            //if ($validator->fails()) {
            //    return sendCustomResponse($validator->messages()->first(), 'error', 500);
            //}

            if ($jenis) {
                return response()->json([
                    'success' => 'True',
                    'code' => 200,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'Gagal',
                ], 401);
            }
        }
    }
    public function viewSnapshoot()
    {
        $user_id = auth('api')->user()->id;
        $pickup_snapshoot = PickupSnapshoot::all()->last();
        $no_akhir = $pickup_snapshoot->no_order;
        $hasil = PickupSnapshoot::join('info_pickup_snapshoot', 'pickup_snapshoot.no_order', '=', 'info_pickup_snapshoot.no_order')
            ->whereDate('pickup_snapshoot.tanggal', Carbon::today())
            ->get();
        $tampil = $hasil->where('no_order', $no_akhir);
        $tanggal =  Carbon::today();
        $view = $hasil->where($tanggal);
        foreach ($hasil as $data_transaksi) {
            $data[] = [
                $data_transaksi->jenis_sampah,
                // $data_transaksi->berat_sampah,
                // $data_transaksi->harga_sampah,
                // $data_transaksi->total_harga,
                // $data_transaksi->waktu_penjemputan,
                // $data_transaksi->hari_penjemputan,
            ];
        }
        $jumlah_data = count($hasil);
        if ($jumlah_data == 0) {
            return response()->json([
                'success' => 'False',
                'message' => 'Tidak Ada Transaksi Hari ini',
                'code' => 404,
            ], 404);
        } else {
            return response()->json([
                'success' => 'True',
                'data' => $tampil,
                'code' => 200,
            ], 200);
        }
    }
    public function pickupCheckout(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $pickup_snapshoot = PickupSnapshoot::all()->last();
        $no_akhir = $pickup_snapshoot->no_order;
        $hasil = PickupSnapshoot::join('info_pickup_snapshoot', 'pickup_snapshoot.no_order', '=', 'info_pickup_snapshoot.no_order')
            ->whereDate('pickup_snapshoot.tanggal', Carbon::today())
            ->get();
        $tampil = $hasil->where('no_order', $no_akhir);
        foreach ($tampil as $data_transaksi) {
            $jenis[] = [
                $data_transaksi->jenis_sampah,
                // $data_transaksi->berat_sampah,
            ];
        }
        $jumlah_array = count($tampil);
        $data_sampah = array();
        for ($i = 0; $i < $jumlah_array; $i++) {
            $array[$i] = $jenis[$i];
            //$output = array_merge($array[$i]);
            //$jenis_sampah = implode($output);
            $user_id = auth('api')->user()->id;
            $pickup_snapshoot = PickupSnapshoot::all()->last();
            $posts = PickupSnapshoot::where('no_order', Carbon::today())->get();
            $user = User::find($user_id);
            //$data = $posts->where('user_id', $user_id);
            $estimasi = $tampil->sum('total_harga');
        }
        $user = User::find($user_id);
        if ($estimasi > $user->point) {
            return response()->json([
                'success' => false,
                'message' => 'Point Anda Tidak Cukup untuk melanjutkan Transaksi ini. Point Anda Adalah ' . $user->point,
                'code'    => '500'
            ], 500);
        } else {
            $data_sampah =  implode(', ', array_map(function ($entry) {
                return ($entry[key($entry)]);
            }, $jenis));
            $tanggal = Carbon::today();
            $jumlah_data = PickupCheckout::count();
            if ($jumlah_data == 0) {
                $no = "RV01";
                $pickup_checkout = PickupCheckout::create([
                    'user_id' =>  $user_id,
                    'no_order' => $no,
                    'jenis_sampah' => $data_sampah,
                    'tanggal_transaksi' => $tanggal,
                    'status' => 'Diproses',
                    'pendapatan' => $estimasi,
                    //'pickup_snapshoot_no_order' => $jenis['no_order'],
                ]);
                if ($pickup_checkout) {
                    return response()->json([
                        'success' => 'True',
                        'message' => 'Pickup Akan diproses',
                        'code' => 200,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'code' => 401,
                        'message' => 'Gagal',
                    ], 401);
                }
            } else {
                $data = PickupCheckout::all()->last();
                $no_akhir = $data->no_order;
                $no_order = ++$no_akhir;
                $pickup_checkout = PickupCheckout::create([
                    'user_id' =>  $user_id,
                    'no_order' => $no_order,
                    'jenis_sampah' => $data_sampah,
                    'tanggal_transaksi' => $tanggal,
                    'status' => 'Diproses',
                    'pendapatan' => $estimasi,
                ]);
                $sisa_point = $user->point - $estimasi;
                $update_user_point = User::find($user_id)->update([
                    'point' => $sisa_point
                ]);
                if ($pickup_checkout) {
                    return response()->json([
                        'success' => 'True',
                        'message' => 'Pickup Akan diproses',
                        'code' => 200,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'code' => 401,
                        'message' => 'Gagal',
                    ], 401);
                }
            }
        }
    }
}
