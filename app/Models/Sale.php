<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['product_id',];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function purchaseProduct($productId, $quantity)
    {
        $product = Product::find($productId);

        if (!$product) {
            return '商品が存在しません';
        }

        if ($product->stock < $quantity) {
            return '商品の在庫不足が不足しています';
        }

        $product->stock -= $quantity;
        $product->save();

        $sale = new Sale([
            'product_id' => $productId,
        ]);

        $sale->save();

        return '購入成功';
    }
}
