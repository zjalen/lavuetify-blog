<?php


namespace App\Http\Controllers\Admin;


use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class TopicsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Topic::query();
        $this->setBuilder($builder);
        $this->setModel(new Topic());
    }

    /**
     * 新建
     * @return mixed
     */
    public function store()
    {
        $params = request()->input();
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');
            $res = $this->saveImageFile($file, 'images/topic');
            if (!$res) {
                return $this->failed('图片异常');
            }
            $params['cover'] = $res;
        }else {
            $params['cover'] = '';
        }
        $topic = new Topic();
        $topic->fill($params);
        $res = $topic->save();
        if ($res) {
            return $this->created($topic);
        }else {
            Storage::disk('public')->delete($params['cover']);
            return $this->failed('更新失败');
        }
    }

    /**
     * 更新
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $model = Topic::find($id);
        if (!$model) {
            $this->notFond('未找到相关文章');
        }
        $params = request()->input();
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');
            $res = $this->saveImageFile($file, 'images/topic');
            if (!$res) {
                return $this->failed('图片异常');
            }
            $params['cover'] = $res;
            Storage::disk('public')->delete($model->cover);
        }else {
            unset($params['cover']);
        }
        $model->fill($params);
        $res = $model->save();
        if ($res) {
            return $this->success($model);
        }else {
            Storage::disk('public')->delete($params['cover']);
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

    /**
     * 保存图片
     * @param File $file
     * @param string $path
     * @param string $disk
     * @return bool|false|string
     */
    protected function saveImageFile(File $file, $path = 'images', $disk = 'public')
    {
        $allowed_ext = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];
        // 如果上传的不是图片将终止操作
        if ( !in_array($file->getClientOriginalExtension(), $allowed_ext)) {
            return false;
        }
        $path = Storage::disk($disk)->putFile($path, $file);
        if (!$path) {
            return false;
        }
        return $path;
    }

    // TODO 删除时脏文件图片处理
}
