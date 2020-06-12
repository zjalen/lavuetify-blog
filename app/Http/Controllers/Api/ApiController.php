<?php
/**
 * Created by PhpStorm.
 * User: jialinzhang
 * Date: 2018/9/5
 * Time: 23:07
 */

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Link;
use App\Models\Page;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * 前端获取菜单
     * @return array
     */
    public function menus()
    {
        $menus = Category::where('show_as_menu', 1)->where('level', 1)->orderBy('order')->get(['id','name']);
        foreach ($menus as $menu){
            $menu->children = $menu->children()->where('show_as_menu', 1)->orderBy('order')->get(['id','name']);
        }
        return $this->success(['menus'=>$menus]);
    }



    /**
     * 获取文章评论
     * @param $article_id
     * @return mixed
     */
    public function getComments($article_id)
    {
//        $article_id = $request->input('article_id');
        $comments = Article::find($article_id)->comments;
        $list = $comments->where('level', 0);
        foreach ($list as &$comment){
            $comment->children = $comments->where('belong',$comment->id);
            $comment->user =User::where('id',$comment->user_id)->first(['id','nickname','avatar']);
            foreach ($comment->children as &$sub_comment){
                $sub_comment->user = User::where('id',$sub_comment->user_id)->first(['id','nickname','avatar']);
            }
        }
        return $list;
    }

    /**
     * 评论文章
     * @param Request $request
     * @return Comment|array
     */
    public function comment(Request $request)
    {
        $comment = new Comment;
        $comment->user_id = $request->post('user_id');
        $comment->article_id = $request->post('article_id');
        $comment->belong = $request->post('belong');
        $comment->parent_id = $request->post('parent_id');
        if (!$comment->parent_id && !$comment->belong){
            $comment->level = 0;
        }else{
            $comment->level = 1;
        }
        $comment->content = $request->post('content');
        $comment->is_checked = 0;
        $res = $comment->save();
        if (!$res){
            return ['error_code'=>1, 'error_message'=>'提交失败'];
        }
        $comment=Comment::with('user')->find($comment->id);
//        $comment->user = User::where('id',$comment->user_id)->first(['id','nickname','avatar']);
        return $comment;
    }

    /**
     * 前端登出
     * @return array
     */
    public function logout()
    {
        session(['userInfo'=>'']);
        return ['error_code'=>0, 'message'=>'success'];
    }

//    /**
//     * 后台登录验证码
//     * @return mixed
//     */
//    public function captcha()
//    {
//        return Captcha::create();
//    }

//    /**
//     * 后台登录
//     * @param Request $request
//     * @return mixed
//     */
//    public function admin_login(Request $request)
//    {
//        // return dump($request);
//        $credentials = $request->only(['username', 'password','captcha']);
//
//        if ($request->getMethod() == 'POST') {
//            $rules = ['captcha' => 'required|captcha'];
//            $validator = Validator::make($credentials, $rules);
//            if ($validator->fails()) {
//                return ['error_code' => 2, 'error_message' => '验证码错误'];
//            }
//
//            unset($credentials['captcha']);
//
//            if (Auth::guard('auth')->attempt($credentials, true)) {
//                // 认证通过...
//                $user = Auth::guard('auth')->user();
//                $user->api_token = str_random(32);
//                $user->save();
//                return ['error_code' => 0, 'token' => $user->api_token];
//            } else {
//                return ['error_code' => 1, 'error_message' => '验证未通过'];
//            }
//        }
//
//        return ['error_code' => 404, 'error_message' => '请求错误'];
//    }
//
//    /**
//     * 后台注销登录
//     */
//    public function admin_logout()
//    {
//        Auth::guard('auth')->logout();
//        return ['error_code'=>0, 'message'=>'success'];
//    }
//
//    /**
//     * 图片验证码
//     * @return string
//     */
//    public function getCaptchaUrl()
//    {
//        return captcha_src();
//    }

    /**
     * 获取页面
     * @param Request $request
     * @return array
     */
    public function getPage(Request $request)
    {
        $name = $request->input('name');
        if (!$name){
            return ['error_code'=>1, 'error_message'=>'参数有误'];
        }
        $page = Page::where('name', $name)->first();
        if (!$page){
            return ['error_code'=>2, 'error_message'=>'页面不存在'];
        }

        return $page;
    }

    /**
     * 获取友链
     * @return Link[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getLinks()
    {
        $links = Link::orderBy('order')->get(['id','name','description','url']);
        return $links;
    }

}
