<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Support\Facades\Response;
use Log;
use Overtrue\Socialite\AccessToken;
use Overtrue\Socialite\SocialiteManager;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class OauthVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->hasHeader('access_token') && $request->hasHeader('type')){
            $accessToken = $request->header('access_token');
            $type = $request->header('type');
            $socialite = new SocialiteManager(config('socialite'));
            try {
                $accessToken = new AccessToken(['access_token' => $accessToken]);
                $user = $socialite->driver($type)->user($accessToken);
                if ($user) {
                    $user = User::where('access_token', $accessToken)->where('type', $type)->first();
                    if ($user) {
                        $update_time = $user->updated_at;
                        if(Carbon::now()->diffInDays(Carbon::parse($update_time)) < 10) {
                            $request->attributes->set('user_info', $user);
                            return $next($request);
                        }
                    }
                }
            }catch (Exception $e) {
                Log::error($e);
                $data =  ['error_code' => 401, 'data' => 'noAuth'];
                return Response::json($data, FoundationResponse::HTTP_UNAUTHORIZED);
            }
        };

        $data =  ['error_code' => 401, 'data' => 'noAuth'];
        return Response::json($data, FoundationResponse::HTTP_UNAUTHORIZED);
    }
}
