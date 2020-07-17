<?php


namespace App\Http\Controllers\Admin;


use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class AdminUsersController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $builder = AdminUser::query();
        $this->setBuilder($builder);
        $this->setModel(new AdminUser());
    }

    public function store()
    {
        $params = request()->input();
        $model = $this->getModel();
        $model->password = Hash::make($params['password']);
        if (request()->hasFile('avatar')) {
            $file = request()->file('avatar');
            $res = $this->saveImageFile($file, 'images/avatar');
            if (!$res) {
                return $this->failed('图片异常');
            }
            $params['avatar'] = $res;
        }else {
            unset($params['avatar']);
        }
        $model->fill($params);
        $res = $model->save();
        if ($res) {
            return $this->success($model);
        }else {
            return $this->failed('保存失败');
        }
    }

    public function update($id)
    {
        $params = request()->input();
        $model = $this->getModel();
        $model = $model->where('id', $id)->first();
        if (!$model) {
            return $this->notFond();
        }
        if (array_key_exists('password', $params)) {
            $model->password = Hash::make($params['password']);
        }
        if (request()->hasFile('avatar')) {
            $file = request()->file('avatar');
            $res = $this->saveImageFile($file, 'images/avatar');
            if (!$res) {
                return $this->failed('图片异常');
            }
            $params['avatar'] = $res;
            Storage::disk('public')->delete($model->avatar);
        }else {
            unset($params['avatar']);
        }
        $model->fill($params);
        $res = $model->save();
        if (!$res) {
            return $this->failed('修改失败');
        }else {
            return $this->success('修改成功');
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

    // TODO 删除时脏文件图片处理
}
