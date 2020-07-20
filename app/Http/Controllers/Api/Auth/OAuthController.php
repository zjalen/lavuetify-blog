<?php
/**
 * Created by PhpStorm.
 * User: jialinzhang
 * Date: 2018/9/9
 * Time: 00:05
 */

namespace App\Http\Controllers\Api\Auth;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Overtrue\Socialite\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Overtrue\Socialite\SocialiteManager;

class OAuthController extends Controller
{
    use ApiResponse;
    
    public function login($type)
    {
        if (!in_array($type,['github','qq','weibo'])){
            return $this->failed('参数有误');
        }
        $conf = config('socialite');
        $socialite = new SocialiteManager($conf);
        $data = [
            'targetUrl' => request()->input('frontend_url')
        ];
        session($data);
        $response = $socialite->driver($type)->redirect();
        return $response;// or $response->send();
    }

    public function callback($type) {
        $url = env('FRONT_URL', 'www.jalen.top');
        $socialite = new SocialiteManager(config('socialite'));
        try {
            $user_info = $socialite->driver($type)->user();
        }catch (Exception $e) {
            Log::error($e);
            return redirect($url);
        }
        $data = [
            'type'=>$type,
            'openid'=>$user_info->getId(),
        ];

        $user = User::firstOrCreate($data);
        $user->access_token=$user_info->getToken()->access_token;
        $user->last_login_ip=request()->getClientIp();
        $user->email=$user_info->getEmail();
        $user->nickname=$user_info->getNickname();
        $user->avatar=$user_info->getAvatar();
        $user->save();
        if (!$user->is_black) {
            return redirect($url."/login?path=".session('targetUrl')."&access_token=".$user->access_token.'&type='.$type);
        }
        return redirect($url);
    }

    /**
     * 前端登出
     * @return array
     */
    public function logout()
    {
        $user = request()->get('user_info');
        $user->access_token = null;
        $user->save();
        return $this->success();
    }

    public function me()
    {
        $user = request()->get('user_info');
        $data = [
            'type' => $user->type,
            'openid' => $user->openid,
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'access_token' => $user->access_token,
        ];
        return $this->success($data);
    }
}