<?php


namespace App\Http\Controllers\Admin;


use App\Models\Article;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

class TopicsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Topic::query();
        $this->setBuilder($builder);
        $this->setModel(new Topic());
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
                    DB::table('articles')->where('topic_id', $model->id)->update(['topic_id' => null]);
                    $model->delete();
                });
            } catch (\Throwable $e) {
                return $this->failed('删除失败');
            }
        }
        return $this->success();
    }
}
