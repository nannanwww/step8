<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductEditController extends Controller
{
    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404); // 商品が見つからない場合の処理
        }

        return view('products.edit', ['product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

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

        return redirect()->route('products.update', ['id' => $id])->with('success', 'Product updated successfully');
    }

}
