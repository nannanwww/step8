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
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function deleteProduct($id)
    {
        $product = $this->findOrFail($id);
        $companyId = $product->company_id;

        $product->delete();
    }

    public function createProduct($request)
    {
        $companyName = $request->input("company_name");
        $companyId = Company::where('company_name', $companyName)->value('id');

        $product = new Product;
        $product->product_name = $request->input("product_name");

        if ($companyId) {
            $product->company_id = $companyId;
        } else {
            \Log::error('メーカーが選択されませんでした。');
        }

        $product->price = $request->input("price");
        $product->stock = $request->input("stock");
        $product->description = $request->input("description");

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $product->image = 'storage/images/' . $imageName;
        } else {
            $imageName = '';
        }

        $product->image = $imageName;
        $product->save();
    }

    public function updateProduct($request)
    {
        $product = $this;
        $companyId = $request->input("company_name");

        $product->product_name = $request->input("product_name");
        $product->price = $request->input("price");
        $product->stock = $request->input("stock");
        $product->description = $request->input("description");

        // メーカーが変更されている場合のみ更新
        if ($product->company_id != $companyId) {
            $product->company_id = $companyId;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $product->image = 'storage/images/' . $imageName;
        } else {
            $imageName = '';
        }

        $product->save();
    }
}
