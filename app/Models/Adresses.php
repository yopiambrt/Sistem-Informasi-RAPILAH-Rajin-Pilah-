<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adresses extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'adresses';
    protected $fillable = [
        'user_id',
        'alamat',
        'longitude',
        'latitude',
    ];
    public function user()
    {
        return $this->belongsTo(User::class); //Fungsi setiap alamat memiliki 1 user
    }
    public function user_transaction()
    {
        return $this->hasMany(PickupTransaction::class);
    }
}
