<?php


namespace App\Http\Controllers\Api;


use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class ArticlesController extends ApiBaseController
{
    private $comments;
    public function __construct()
    {
        parent::__construct();
        $builder = Article::query();
        $this->setBuilder($builder);
        $this->setModel(new Article());
        $this->setAllowShowColumns(['id','title','description','created_at','cover','category_id','topic_id','is_top']);
        $this->setAppendCondition([['column' => 'is_draft', 'value' => 0]]);
    }

    /**
     * 获取文章详情
     * @return mixed
     */
    public function show($id)
    {
        $this->setAllowShowColumns(['id','cover','category_id','topic_id','title','created_at','content_md','content_html','pageviews']);
        $model = $this->getModel();
        $res = $model->find($id);
        if ($res) {
            $res->pageviews += 1;
            $res->save();
            // 获取 “上一篇” 的 ID
            $previousPostID = Article::where('id', '<', $id)->where('is_draft','not',0)->max('id');
            // 同理，获取 “下一篇” 的 ID
            $nextPostId = Article::where('id', '>', $id)->where('is_draft','not',0)->min('id');
            $prev_article = Article::find($previousPostID);
            if ($prev_article) {
                $res->prev = ['id'=>$previousPostID,'title'=>$prev_article->title,'category'=>$prev_article->category()->first(['id','name'])];
            }
            $next_article = Article::find($nextPostId);
            if ($next_article) {
                $res->next = ['id'=>$nextPostId,'title'=>$next_article->title,'category'=>$next_article->category()->first(['id','name'])];
            }
            return $this->success($res);
        }else {
            return $this->notFond();
        }
    }

    public function ids()
    {
        return Article::where('is_draft', 0)->get(['id','cover','category_id','topic_id','title','created_at','content_md','content_html','pageviews']);
    }

    public function comments($article_id)
    {
        $result = [];
        $this->comments = Comment::where('article_id', $article_id)->orderByDesc('id')->get();
        $top_comments = $this->comments->where('level', 0)->sortByDesc('id')->collect();
        foreach($top_comments as $top_comment) {
            $top_comment->children = $this->getNodeTree($top_comment->id);
            $res = json_decode($top_comment, true);
            unset($res['user']);
            array_push($result, $res);
        }
        return $this->success($result);
    }

    public function getNodeTree($parent_id)
    {
        $children = [];
        $comments = $this->comments->where('parent_id', $parent_id)->all();
        if (count($comments) > 0) {
            foreach($comments as $comment){
                $comment->children = $this->getNodeTree($comment->id);
                $res = json_decode($comment, true);
                unset($res['user']);
                array_push($children, $res);
            }
        }
        return $children;
    }

    public function createComment()
    {
        $user = request()->get('user_info');
        $params = request()->only(['article_id', 'belong', 'parent_id', 'content']);
        if (!array_key_exists('belong', $params) 
        || !array_key_exists('parent_id', $params) 
        || !array_key_exists('article_id', $params) 
        || !array_key_exists('content', $params)) {
            return $this->failed('参数有误');
        }
        $params['user_id'] = $user->id;
        $comment = new Comment();
        if ($params['parent_id'] == null) {
            $params['level'] = 0;
        }else {
            $parent = Comment::find($params['parent_id']);
            if (!$parent) {
                return $this->notFond('未找到父级评论');
            }
            $params['level'] = $parent->level == 5 ? 5 : $parent->level + 1;
        }
        $comment->fill($params);
        $comment->save();
        return $this->created($comment);
    }
}
