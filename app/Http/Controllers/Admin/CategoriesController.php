<?php


namespace App\Http\Controllers\Admin;


use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoriesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Category::query();
        $this->setBuilder($builder);
        $this->setModel(new Category());
    }

    public function index()
    {
        $items = Category::where('level', 1)->orderBy('order')->get();
        return $this->success(['items'=> $items]);
    }

    public function store()
    {
        $params = request()->input();
        if (array_key_exists('parent_id', $params)) {
            $parent = Category::find($params['parent_id']);
            if (!$parent) {
                return $this->notFond('未找到父节点');
            }
            $params['level'] = $parent->level + 1;
        }
        $model = $this->getModel();
        $model->fill($params);
        try {
            DB::transaction(function () use ($model) {
                // 插入序列变动
                if (DB::table('categories')->where('level', $model->level)->where('order', $model->order)->count() > 0) {
                    DB::table('categories')->where('level', $model->level)->where('order', '>=', $model->order)->increment('order');
                }
                $model->save();
            });
            return $this->created($model);
        } catch (\Throwable $e) {
            return $this->failed('添加失败');
        }
    }

    public function update($id)
    {
        $model = $this->getModel();
        $model = $model->where('id', $id)->first();
        if (!$model) {
            return $this->notFond();
        }
        $params = request()->input();
        if (array_key_exists('parent_id', $params)) {
            $parent = Category::find($params['parent_id']);
            if (!$parent) {
                return $this->notFond('未找到父节点');
            }
            $params['level'] = $parent->level + 1;
        }
        $model->fill($params);
        try {
            DB::transaction(function () use ($model) {
                // 插入序列变动
                if (DB::table('categories')->where('level', $model->level)->where('order', $model->order)->count() > 0) {
                    DB::table('categories')->where('level', $model->level)->where('order', '>=', $model->order)->increment('order');
                }
                $model->save();
            });
            return $this->success($model);
        } catch (\Throwable $e) {
            return $this->failed('更新失败');
        }
    }

    public function destroy($id)
    {
        $model = $this->getModel();
        $model = $model->where('id', $id)->first();
        if (!$model) {
            return $this->notFond();
        } else {
            if ($model->articles()->count() > 0) {
                return $this->failed('分类下有文章不能删除');
            } else if ($model->children()->count() > 0) {
                return $this->failed('请先删除子分类');
            }
        }
        try {
            DB::transaction(function () use ($model) {
                // 插入序列变动
                DB::table('categories')->where('level', $model->level)->where('order', '>=', $model->order)->decrement('order');
                $model->delete();
            });
            return $this->success('删除成功');
        } catch (\Throwable $e) {
            return $this->failed('删除失败');
        }
    }
}
