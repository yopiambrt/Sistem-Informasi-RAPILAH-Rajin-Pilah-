<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PickupTransaction extends Model
{
    use HasFactory;
    protected $table = 'pickup_transaction';
    protected $fillable = [
        'user_id',
        'hari_pickup',
        'jam_pickup',
        'adresses_id',
        'total_harga',
        'foto_bukti',
        'status',
    ];
    public function pickup_transaction()
    {
        return $this->belongsTo(Adresses::class);
    }
    public function user_detail()
    {
        return $this->belongsTo(User::class);
    }
}
