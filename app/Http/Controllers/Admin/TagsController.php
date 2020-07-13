<?php


namespace App\Http\Controllers\Admin;


use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class TagsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Tag::query();
        $this->setBuilder($builder);
        $this->setModel(new Tag());
    }

    public function destroy($id)
    {
        $model = $this->getModel();
        $model = $model->where('id', $id)->first();
        if (!$model) {
            return $this->notFond();
        } else {
            try {
                DB::transaction(function () use ($model) {
                    $model->articles()->detach();
                    $model->delete();
                });
            } catch (\Throwable $e) {
                return $this->failed('删除失败');
            }
        }
        return $this->success();
    }
}
