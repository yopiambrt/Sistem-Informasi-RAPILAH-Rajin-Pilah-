<?php

namespace App\Http\Controllers;

use App\Models\PickupTransaction;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function showPickupCustomer()
    {
        $hasil = PickupTransaction::join('adresses', 'pickup_transaction.adresses_id', '=', 'adresses.id')->where('petugas', '=', null)->get();
        echo $hasil;
    }
    public function pilihPickup()
    {
        $user_id = auth('api')->user()->id;
    }
}
