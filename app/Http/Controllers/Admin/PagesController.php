<?php


namespace App\Http\Controllers\Admin;


use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class PagesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = Page::query();
        $this->setBuilder($builder);
        $this->setModel(new Page());
        $this->setAllowShowColumns(['id','title','name','url','content_md','content_html']);
    }

    /**
     * markdown 上传图片
     * @return array
     */
    public function uploadImage()
    {
        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $path = $this->saveImageFile($file, 'images/page');
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
}
