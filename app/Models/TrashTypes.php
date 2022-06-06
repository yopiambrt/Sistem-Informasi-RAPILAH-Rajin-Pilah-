<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrashTypes extends Model
{
    use HasFactory;
    protected $table = 'trash_types';
    protected $fillable = [
        'jenis_sampah',
        'harga',
        'images',
        'quantity',
    ];
}
