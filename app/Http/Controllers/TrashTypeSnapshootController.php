<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrashTypes;
use App\Models\TrashTypeSnapshots;
use App\Models\PickupOrders;
use PhpParser\Node\Stmt\Echo_;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;


class TrashTypeSnapshootController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function create(Request $request)
    {
        $id_snapshoot = array();
        foreach ($request->list_jenis as $key => $value) {
            $jenis = [
                'id' => $request->id,
                'trash_type_id' => $value['trash_types_id'],
                'jenis_sampah' => $value['jenis_sampah'],
                'harga' => $value['harga'],
            ];
            $jenis = TrashTypeSnapshots::create($jenis);
            $id_snapshoot1 = [];
            $id_snapshoot1 = $jenis['id'];
            $id_snapshoot[] = $id_snapshoot1;
        }
        // print_r($id_snapshoot);
        foreach ($request->list_pickup as $key => $value) {
            $pickup = [
                'jumlah_barang' => $value['jumlah_barang'],
            ];
        }
        $isijumlah1 = array(); // Menampung Nilai Array
        foreach ($request->list_pickup as $post1) { // Looping Nilai Array yang telah ditampung
            $isijumlah = [];

            $isijumlah = $post1['jumlah_barang'];
            $isijumlah1[] = $isijumlah; // Mengisi Nilai Array ke dalam variabel $isi_jumlah
        }
        $isiharga1 = array();
        foreach ($request->list_jenis as $post) {
            $isiharga = [];
            $isiharga = $post['harga'];
            $isiharga1[] = $isiharga;
        }

        $hasil[] = array();
        for ($i = 0; $i < count($isijumlah1); $i++) { // Fungsi untuk mendapatkan total harga serta buat input ke dalam database Pickups_orders
            $hasil[$i] = $isijumlah1[$i] * $isiharga1[$i];
            $id_snapshoot_for[$i] = $id_snapshoot[$i];
            $mytime = Carbon::now()->toDateString();
            $insert_pickup = [
                'user_id' =>  auth('api')->user()->id,
                'pickup_snapshoot_id' => $id_snapshoot_for[$i],
                'jumlah_barang' => $isijumlah1[$i],
                'total_harga' => $hasil[$i],
                'tanggal' => $mytime
            ];

            $insert_pickup = PickupOrders::create($insert_pickup);
            //echo $hasil[$i];
            //echo $isijumlah1[$i];
        }
        //foreach ($hasil as $key => $hasil_input) {
        //    $pickup = [
        //        'total_harga' => $hasil_input,
        //    ];
        //    $pickup = PickupOrders::create($pickup);
        //}
        //print_r($isijumlah1);
        //print_r($isiharga1);
        //for ($i = 0; $i < count($request->list_jenis); $i++) {
        //    $hasil[$i] = $jenis['harga'] * $pickup['jumlah_barang'];
        //    echo $hasil[$i];
        //}
        //$pickup->jumlah_barang = $value['jumlah_barang'];
        //$pickup->total_harga = $value['harga'] * $pickup->jumlah_barang;
        //$pickup->pickup_snapshoot_id = $jenis->id;
        //$pickup->save();

        return response()->json([
            'message' => 'success'
        ], 200);
        echo base_path();
        //if ($request->isMethod('post')) {
        //    $list = $request->input();
        //    foreach ($list as $key => $value) {
        //        $list_jenis = new TrashTypeSnapshots;
        //        $list_jenis->trash_type_id = $value['trash_type_id'];
        //        $list_jenis->jenis_sampah = $value['jenis_sampah'];
        //        $list_jenis->harga = $value['harga'];
        //        $list_jenis->save();
        //    }
        //    return response()->json([
        //        'message' => 'sukes'
        //    ], 200);
        //}
        ## Proses ke pickup transaction
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
    

    }
    
    //public function latihancreate(Request $request)
    //{
    //    $nama = $request->list_jenis;
    //    $harga = $request->harga;
    //    //$jenis_sampah = array();
    //    //foreach ($nama as $post) {
    //    //    $nama_sampah = [];
    //    //    $jenis_sampah = $nama;
    //    //    $jenis_sampah[] = $nama_sampah;
    //    //}
    //    //$hitung = count($nama);
    //    //echo $hitung;
    //    for ($i = 0; $i < count($nama); $i++) {
    //        //echo TrashTypes::get('jenis_sampah', $nama[$i]);
    //        $types = TrashTypes::all();
    //        $type = $types->where('id', $nama[$i]);
    //        echo $type;
    //        $insert_jenis_sampah = [
    //            'trash_type_id' => $nama[$i],
    //            'jenis_sampah' => 'default',
    //            'harga' => 0,
    //        ];
    //        $insert_jenis_sampah = TrashTypeSnapshots::create($insert_jenis_sampah);
    //    }
    //}
}
