<?php


namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['username', 'password','captcha']);
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


        if (! $token = auth()->attempt($credentials)) {
            return fail('账号或密码有误');
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->success(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->success('Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
