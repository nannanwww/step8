<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'image', 'product_name', 'price', 'stock', 'company'];

    public function searchProducts($keyword, $company)
    {
        $query = $this->query();

        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        if ($company) {
            $query->where('company', $company);
        }

        return $query->orderBy('product_name')->paginate(6);
    }

    public static function updateProduct(Request $request, $id)
    {
        $product = self::find($id);

        if (!$product) {
            return null; // 商品が見つからない場合の処理
        }

        $request->validate([
            'product_name' => 'required|max:20',
            'company' => 'required|max:20',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'nullable|max:140',
            'image' => 'nullable|image|max:2048',
        ]);

        // 更新処理
        $product->product_name = $request->input("product_name");
        $product->company = $request->input("company");
        $product->price = $request->input("price");
        $product->stock = $request->input("stock");
        $product->description = $request->input("description");

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $product->image = 'storage/images/' . $imageName;
        }

        $product->save();

        return $product;
    }
}
