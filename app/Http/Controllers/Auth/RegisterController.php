<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // トランザクションの開始
        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            DB::commit();

            auth()->login($user);

            return redirect('/');
        } catch (\Exception $e) {
            // エラー時の処理
            DB::rollBack();

            // エラーをログに記録する
            Log::error('エラーが発生しました: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'ユーザー登録中にエラーが発生しました。']);
        }
    }
}
