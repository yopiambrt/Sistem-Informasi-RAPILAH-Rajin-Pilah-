<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edukasi extends Model
{
    use HasFactory;
    protected $table = 'edukasi';
    protected $fillable = [
        'judul',
        'deskripsi',
        'foto_edukasi',
        'video_edukasi',
    ];
}
