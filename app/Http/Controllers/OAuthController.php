<?php
/**
 * Created by PhpStorm.
 * User: jialinzhang
 * Date: 2018/9/9
 * Time: 00:05
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request;
use Overtrue\Socialite\SocialiteManager;

class OAuthController extends Controller
{
    public function login($type, Request $request)
    {
        if (!in_array($type,['github','qq','weibo'])){
            return ['error_code'=> 1, 'error_message'=>'参数错误'];
        }
        $conf = config('socialite');
        $data = [
            'targetUrl' => URL::previous().$request->input('page')
        ];
        session($data);
        $socialite = new SocialiteManager($conf);
        $response = $socialite->driver($type)->redirect();
        return $response;// or $response->send();
    }

    public function callback($type, Request $request) {
        $socialite = new SocialiteManager(config('socialite'));
        try {
            $user_info = $socialite->driver($type)->user();
        }catch (\Exception $e) {
            Log::error($e->getMessage()->toString());
            return redirect(session('targetUrl', url('/')));
        }
        $data = [
            'type'=>$type,
            'openid'=>$user_info->getId(),
        ];

        $user = User::firstOrCreate($data);
        $user->access_token=$user_info->getToken()->access_token;
        $user->last_login_ip=$request->getClientIp();
        $user->email=$user_info->getEmail();
        $user->nickname=$user_info->getNickname();
        $user->avatar=$user_info->getAvatar();
        $user->save();
        if (!$user->is_black) {
            session(['userInfo'=>$user]);
        }
//        if ($request->isMethod('ajax')){
//            return $user;
//        }
        return redirect(session('targetUrl', url('/')));
    }
}
