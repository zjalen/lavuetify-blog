<?php


namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\File;
use Tymon\JWTAuth\JWTAuth;

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
            return $this->failed('账号或密码有误');
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

    public function changeUserInfo()
    {
         $params = request()->only(['avatar', 'name']);
         $adminUser = AdminUser::find(auth()->id());
         if ($adminUser) {
             if (array_key_exists('avatar', $params)) {
                 $file = request()->file('avatar');
                 $res = $this->saveImageFile($file, 'images/avatar');
                 if (!$res) {
                     return $this->failed('图片异常');
                 }
                 $params['avatar'] = $res;
                 Storage::disk('public')->delete($adminUser->avatar);
             }else {
                 unset($params['avatar']);
             }
             $adminUser->fill($params);
             $adminUser->save();
             return $this->success(auth()->user());
         }else {
             return $this->notFond('未找到用户信息');
         }
    }

    public function resetPassword()
    {
        $params = request()->only(['old_password', 'new_password']);
        $adminUser = AdminUser::find(auth()->id());
        if (Hash::check($params['old_password'], $adminUser->password)) {
            $adminUser->password = Hash::make($params['new_password']);
            $adminUser->save();
            auth()->logout();
            return $this->success();
        }else {
            return $this->notFond('密码错误');
        }
    }

    /**
     * 保存图片
     * @param File $file
     * @param string $path
     * @param string $disk
     * @return bool|false|string
     */
    protected function saveImageFile(File $file, $path = 'images', $disk = 'public')
    {
        $allowed_ext = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];
        // 如果上传的不是图片将终止操作
        if ( !in_array($file->getClientOriginalExtension(), $allowed_ext)) {
            return false;
        }
        $path = Storage::disk($disk)->putFile($path, $file);
        if (!$path) {
            return false;
        }
        return $path;
    }
}
