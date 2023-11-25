<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'image', 'product_name', 'price', 'stock', 'company_id'];

    public function company()
    {
        return $this->belongsTo('App\Models\company');
    }
}
