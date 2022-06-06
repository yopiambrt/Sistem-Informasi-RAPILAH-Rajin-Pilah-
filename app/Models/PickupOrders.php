<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PickupOrders extends Model
{
    use HasFactory;
    protected $table = 'pickup_orders';
    protected $fillable = [
        'total_harga',
        'pickup_snapshoot_id',
        'jumlah_barang',
        'user_id',
        'tanggal',
    ];
    public function user()
    {
        return $this->belongsTo(User::class); //Fungsi setiap alamat memiliki 1 user
    }
    protected $dateFormat = 'Y-m-d';
}
