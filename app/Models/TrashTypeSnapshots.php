<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrashTypeSnapshots extends Model
{
    use HasFactory;
    protected $table = 'trash_type_snapshots';
    protected $fillable = [
        'trash_type_id',
        'jenis_sampah',
        'harga',
    ];
}
