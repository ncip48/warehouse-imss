<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spph extends Model
{
    use HasFactory;
    protected $table = 'spph';
    protected $fillable = [
        'nomor_spph',
        'lampiran',
        'vendor_id',
        'tanggal_spph',
        'batas_spph',
        'perihal',
        'penerima',
        'alamat'
    ];
}
