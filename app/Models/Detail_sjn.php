<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_sjn extends Model
{
    use HasFactory;
    protected $table = 'detail_sjn';
    protected $fillable = [
        'sjn_id', 
        'product_id', 
        'stock', 
        
    ];
}
