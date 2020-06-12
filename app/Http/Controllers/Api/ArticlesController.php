<?php


namespace App\Http\Controllers\Api;


use App\Models\Article;
use Illuminate\Support\Facades\DB;

class ArticlesController extends ApiBaseController
{
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
}
