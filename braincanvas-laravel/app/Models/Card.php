<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    // Kolom yang boleh diisi lewat Card::create([...]) dan update()
    protected $fillable = [
        'nama',
        'deskripsi',
        'tipe',
        'status',
        'x',
        'y',
    ];
}
