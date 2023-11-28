<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = ['company_name', 'street_address', 'representative_name'];

    public function product()
    {
        return $this->hasMany('App\Models\product');
    }
}
