<?php


namespace App\Http\Controllers\Admin;


use App\Models\Category;

class CategoriesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Category::query();
        $this->setBuilder($builder);
        $this->setModel(new Category());
    }

    public function destroy($id)
    {
        $model = $this->getModel();
        $model = $model->where('id', $id)->first();
        if (!$model) {
            return $this->notFond();
        } else {
            if ($model->articles) {
                return $this->failed('分类下有文章不能删除');
            } else if ($model->children) {
                return $this->failed('请先删除子分类');
            }
        }
        $res = $model->delete();
        if (!$res) {
            return $this->failed('删除失败');
        }else {
            return $this->success();
        }
    }
}
