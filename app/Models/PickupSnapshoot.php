<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupSnapshoot extends Model
{
    use HasFactory;
    protected $table = 'pickup_snapshoot';
    protected $fillable = [
        'no_order',
        'tanggal',
        'user_id',
        'jenis_sampah',
        'berat_sampah',
        'harga_sampah',
        'total_harga',
    ];
    public function InfoPickupSnashoot()
    {
        return $this->belongsTo(InfoPickupSnapshoot::class);
    }
}
