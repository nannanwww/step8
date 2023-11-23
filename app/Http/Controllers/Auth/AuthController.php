<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('login.login_form'); }

    public function login(LoginFormRequest $request) {
        $credentials = $request->only('password','email');

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('products')->with('login_success','ログイン成功しました。');
        }

        return back()->withErrors([
            'login_error' => 'メールアドレスかパスワードが間違っています。',
        ]); }
}
