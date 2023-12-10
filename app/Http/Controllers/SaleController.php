<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class SaleController extends Controller
{
    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1); // もし”quantity”が指定されていない場合は1を代入する

        $result = Sale::purchaseProduct($productId, $quantity);

        return response()->json(['message' => $result]);
    }
}
