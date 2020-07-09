<?php


namespace App\Http\Controllers\Admin;


use App\Models\Article;

class ArticlesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Article::query();
        $this->setBuilder($builder);
        $this->setModel(new Article());
        $this->setAllowShowColumns(['id','title','description','created_at','cover','category_id','topic_id','is_top', 'is_draft', 'pageviews']);
    }
}
