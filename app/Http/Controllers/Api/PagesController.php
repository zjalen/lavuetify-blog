<?php


namespace App\Http\Controllers\Api;


use App\Models\Page;

class PagesController extends ApiBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Page::query();
        $this->setBuilder($builder);
        $this->setModel(new Page());
        $this->setAllowShowColumns(['id','title','name','content_html','created_at']);
    }

    /**
     * 获取文章详情
     * @param $name
     * @return mixed
     */
    public function show($name)
    {
        $model = $this->getModel();
        $res = $model->where('name', $name)->first();
        if ($res) {
            return $this->success($res);
        }else {
            return $this->notFond();
        }
    }
}
