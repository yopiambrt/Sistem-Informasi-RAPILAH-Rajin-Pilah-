<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoPickupSnapshoot extends Model
{
    use HasFactory;
    protected $table = 'info_pickup_snapshoot';
    protected $fillable = [
        'user_id',
        'no_order',
        'foto_bukti',
        'address_id',
        'hari_penjemputan',
        'waktu_penjemputan',
        'informasi_tambahan',
        'tanggal',
    ];
    public function PickupSnapshoot()
    {
        return $this->hasMany(PickupSnapshoot::class);
    }
}
