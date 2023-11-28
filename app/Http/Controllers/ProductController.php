<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $products = Product::query();
        $companies = Company::pluck('company_name', 'id');

        $keyword = $request->input('key_word');
        $company_id = $request->input('key_company');

        // 検索機能
        if ($keyword) {
            $products = $products->where(function ($query) use ($keyword) {
                $query->where('product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        if ($company_id) {
            $products = $products->where('company_id', $company_id);
        }

        if (!$keyword && !$company_id) {
            $products = Product::orderBy('product_name')->paginate(6);
        } else {
            $products = $products->orderBy('product_name')->paginate(6);
        }

        if ($request->has('key_word') && $products->isEmpty()) {
            $errorMessage = '検索キーワードに一致する商品が見つかりませんでした。';
        }

        $errorMessage = '';

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

        try {
            $product = new Product();
            $product->createProduct($request);

            return redirect('/products/create')->with('success', '登録に成功しました。');
        } catch (\Exception $e) {
            \Log::error('エラーが発生しました: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'エラーが発生しました。']);
        }
    }

    public function showDetail($id, Request $request)
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
        try {
            $product = new Product();
            $product->deleteProduct($id);
            return redirect('/products');
        } catch (\Exception $e) {
            \Log::error('エラーが発生しました: ' . $e->getMessage());
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

        $request->validate([
            'product_name' => 'required|max:20',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'description' => 'nullable|max:140',
            'image' => 'nullable|image|max:2048',
        ]);

        if (!$product) {
            abort(404);
        }

        try {
            $product->updateProduct($request);

            return redirect()->route('products.edit', ['id' => $id])->with('success', '更新完了しました。');
        } catch (\Exception $e) {
            \Log::error('エラーが発生しました: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'エラーが発生しました。']);
        }
    }
}
