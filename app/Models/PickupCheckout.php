<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupCheckout extends Model
{
    use HasFactory;
    protected $table = 'pickup_checkout';
    protected $fillable = [
        'user_id',
        'no_order',
        'jenis_sampah',
        'tanggal_transaksi',
        'petugas',
        'status',
        'pendapatan',
    ];
}
