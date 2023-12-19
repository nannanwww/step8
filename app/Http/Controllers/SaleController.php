<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1); // もし”quantity”が指定されていない場合は1を代入する

        return DB::transaction(function () use ($productId, $quantity) {
            try {
                $result = Sale::purchaseProduct($productId, $quantity);

                return response()->json(['message' => $result['message']], $result['status']);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('エラーが発生しました: ' . $e->getMessage());

                return response()->json(['message' => 'Transaction failed: ' . $e->getMessage()], 500);
            }
        });
    }
}
