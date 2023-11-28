<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RegisterUser;
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

        try {
            RegisterUser::registerUser(
                $request->input('email'),
                $request->input('password')
            );

            auth()->attempt($request->only('email', 'password'));

            return redirect('/');
        } catch (\Exception $e) {
            \Log::error('エラーが発生しました: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'ユーザー登録中にエラーが発生しました。']);
        }
    }
}
