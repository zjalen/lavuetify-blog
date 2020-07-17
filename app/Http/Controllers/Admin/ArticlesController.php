<?php


namespace App\Http\Controllers\Admin;


use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

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

    public function count() {
        $total = Article::count();
        $draft = Article::where('is_draft', 1)->count();
        $top = Article::where('is_top', 1)->count();
        $publish = Article::where('is_draft', 0)->count();

        return $this->success(['total' => $total, 'draft' => $draft, 'top' => $top, 'publish' => $publish]);
    }

    /**
     * 新建文章
     * @return mixed
     */
    public function store()
    {
        $params = request()->input();
        $params['author_id'] = 1;
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');
            $res = $this->saveImageFile($file);
            if (!$res) {
                return $this->failed('图片异常');
            }
            $params['cover'] = $res;
        }else {
            $params['cover'] = '';
        }
        if (array_key_exists('description', $params)) {
            $params['description'] = $params['description'] == '' ? substr(strip_tags($params['content_html']), 60) : $params['description'];
        }
        $article = new Article();
        $article->fill($params);
        $res = $article->save();
        if ($res) {
            if ($params['tag_names']) {
                $tagsArray = explode(',',$params['tag_names']);
                $tagIds = array_map(function ($v){
                    $model = Tag::firstOrCreate(['name' => $v]);
                    return $model->id;
                }, $tagsArray);
                $article->tags()->sync($tagIds);
            }
            return $this->created($article);
        }else {
            Storage::disk('public')->delete($params['cover']);
            return $this->failed('更新失败');
        }
    }

    /**
     * 更新文章
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $article = Article::find($id);
        if (!$article) {
            $this->notFond('未找到相关文章');
        }
        $params = request()->input();
        if (request()->hasFile('cover')) {
            $file = request()->file('cover');
            $res = $this->saveImageFile($file);
            if (!$res) {
                return $this->failed('图片异常');
            }
            $params['cover'] = $res;
            Storage::disk('public')->delete($article->cover);
        }else {
            unset($params['cover']);
        }
        $params['description'] = $params['description'] ?: substr(strip_tags($params['content_html']), 0, 60);
        $params = array_map(function ($v) {
            return $v == 'null' ? null : $v;
        }, $params);
        $article->fill($params);
        $res = $article->save();
        if ($res) {
            $tagIds = [];
            if ($params['tag_names']) {
                $tagsArray = explode(',',$params['tag_names']);
                $tagIds = array_map(function ($v){
                    $model = Tag::firstOrCreate(['name' => $v]);
                    return $model->id;
                }, $tagsArray);
            }
            $article->tags()->sync($tagIds);
            return $this->success($article);
        }else {
            Storage::disk('public')->delete($params['cover']);
            return $this->failed('更新失败');
        }
    }

    /**
     * markdown 上传图片
     * @return array
     */
    public function uploadImage()
    {
        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $path = $this->saveImageFile($file);
            if ($path) {
                return $this->success(Storage::url($path));
            }else {
                return $this->failed('上传失败');
            }
        }
        return $this->failed('文件无效');
    }

    /**
     * markdown 删除图片
     * @return mixed
     */
    public function deleteImage()
    {
        $image_url = request()->input('image_url');
        if ($image_url) {
            $pathArray = explode('storage', $image_url);
            $res = Storage::disk('public')->delete($pathArray[count($pathArray) - 1]);
            if ($res) {
                return $this->success();
            }else {
                return $this->failed('删除失败');
            }
        }else {
            return $this->failed('图片地址有误');
        }
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

    public function tags()
    {
        return $this->success(['items' => Tag::all()->pluck('name')]);
    }

    public function categories()
    {
        return $this->success(['items' => Category::where('level', 1)->orderBy('order')->get(['id', 'name'])]);
    }

    public function topics()
    {
        return $this->success(['items' => Topic::all(['id', 'name'])]);
    }

    // TODO 删除时脏文件图片处理
}
