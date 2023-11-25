<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::query();
        $companies = Company::pluck('company_name', 'id');
        logger($companies);

        $keyword = $request->input('key_word');
        $company_id = $request->input('key_company');

        if ($keyword) {
            $products = $products->where(function ($query) use ($keyword) {
                $query->where('product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        if ($company_id) {
            $products = $products->where('company_id', $company_id);
        }

        $products = $products->orderBy('product_name')->paginate(6);
        $request->session()->put('products_page', $products->currentPage());
        $request->session()->put('searchParams', $request->getQueryString());

        $errorMessage = '';

        if ($request->has('key_word') && $products->isEmpty()) {
            $errorMessage = '検索キーワードに一致する商品が見つかりませんでした。';
        }

        $requestParams = $request->getQueryString();

        return view('products.index', [
            'products' => $products,
            'companies' => $companies,
            'errorMessage' => $errorMessage,
            'requestParams' => $requestParams,
        ]);
    }





    public function create()
    {
        $companies = Company::pluck('company_name', 'id');
        return view('products.create', ['companies' => $companies]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|max:20',
            'company_id' => 'nullable|max:20',
            'new_company_name' => 'nullable|max:50',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'nullable|max:140',
            'image' => 'nullable|image|max:2048',
        ]);

        // DBトランザクションの開始
        DB::beginTransaction();

        try {

            $companyName = $request->input("company_name");
            $companyId = Company::where('company_name', $companyName)->value('id');

            $product = new Product;
            $product->product_name = $request->input("product_name");

            if ($companyId) {
                // 既存のメーカーが選択された場合
                $product->company_id = $companyId;
            } else {
                // 新しいメーカー名が入力された場合
                $newCompany = new Company;
                $newCompany->company_name = $companyName;
                $newCompany->save();
                $product->company_id = $newCompany->id;
            }

            $product->price = $request->input("price");
            $product->stock = $request->input("stock");
            $product->description = $request->input("description");

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $imageName); // 画像を保存
                $product->image = 'storage/images/' . $imageName;
            }

            $product->save();

            DB::commit();

            return redirect('/products');
        } catch (\Exception $e) {
            // エラー時の処理
            DB::rollBack();

            // エラーをログに記録する
            Log::error('エラーが発生しました: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'エラーが発生しました。']);
        }
    }



    public function showDetail($id)
    {
        $product = Product::find($id);
        $companyName = Company::find($product->company_id)->company_name;

        return view('products.detail', [
            'product' => $product,
            'companyName' => $companyName,
        ]);
    }

    public function delete($id)
    {
        // DBトランザクションの開始
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);
            logger($product);

            $product->delete();

            DB::commit();
            return redirect('/products');
        } catch (\Exception $e) {
            // エラー時の処理
            DB::rollBack();

            // エラーをログに記録する
            Log::error('エラーが発生しました: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'エラーが発生しました。']);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $companies = Company::pluck('company_name', 'id');

        if (!$product) {
            // 商品が存在しない場合の処理
            abort(404);
        }
        return view('products.edit', [
            'product' => $product,
            'companies' => $companies,
        ]);
    }



    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $companyName = $request->input("company_name");

        $request->validate([
            'product_name' => 'required|max:20',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'nullable|max:140',
            'image' => 'nullable|image|max:2048',
        ]);

        // DBトランザクションの開始
        DB::beginTransaction();
        try {
            // 更新処理
            $product->product_name = $request->input("product_name");
            $product->price = $request->input("price");
            $product->stock = $request->input("stock");
            $product->description = $request->input("description");

            $companyId = Company::where('company_name', $companyName)->value('id');
            if ($companyId) {
                // 既存のメーカーが選択された場合
                $product->company_id = $companyId;
            } else {
                // 新しいメーカー名が入力された場合
                $newCompany = new Company;
                $newCompany->company_name = $companyName;
                $newCompany->save();
                $product->company_id = $newCompany->id;
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $imageName);
                $product->image = 'storage/images/' . $imageName;
            }

            $product->save();

            DB::commit();

            return redirect()->route('products.showDetail', ['id' => $id])->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            // エラー時の処理
            DB::rollBack();

            // エラーをログに記録する
            Log::error('エラーが発生しました: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'エラーが発生しました。']);

        }

    }
}
