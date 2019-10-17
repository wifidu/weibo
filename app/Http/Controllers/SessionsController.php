<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        
        if (Auth::attempt($credentials, $request->has('remember'))){
            session()->flash('success', '欢迎回来');
            $fallback = route('users.show', Auth::user());//默认跳转地址
            return redirect()->intended($fallback);
        } else{
            session()->flash('danger', '对不起，您的邮箱或密码错误');
            return redirect()->back()->withInput();
        }
        return;
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已经成功退出！');
        return redirect('login');
    }
}

