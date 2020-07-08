<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Controllers\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends AdminBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

//
//     /**
//      * Where to redirect users after login.
//      *
//      * @var string
//      */
//     protected $redirectTo = RouteServiceProvider::HOME;
//
//     /**
//      * Create a new controller instance.
//      *
//      * @return void
//      */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    /**
     * 处理登录认证
     *
     * @return ApiResponse
     * @translator laravelacademy.org
     */
    public function login(Request $request)
    {
        if (Auth::user()) {
            // 认证通过...
            return $this->success('登录成功');
        }

        $credentials = $request->only(['username', 'password','captcha']);

        $rules = [
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ];
        $message = [
           'username.required'=>'登录名必填',
           'password.required'=>'密码必填',
           'captcha.required'=>'验证码必填',
           'captcha.captcha'=>'验证码错误'
       ];
        $validator = Validator::make($credentials, $rules, $message);

        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
        unset($credentials['captcha']);
        if (Auth::guard('web')->attempt($credentials)) {
            // 认证通过...
            return $this->success('登录成功');
        }

        return $this->failed('账号或密码错误');
    }

    public function username()
    {
        return 'username';
    }

    public function logout()
    {
        if (Auth::guard('web')->check()){
            Auth::guard('web')->logout();
        }
        return $this->success('退出登录成功');
    }

    public function captcha()
    {
    }
}
