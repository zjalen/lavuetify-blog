<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractBaseController;
use App\Http\Controllers\Traits\ApiResponse;

abstract class ApiBaseController extends AbstractBaseController
{
    use  ApiResponse;

    public function index()
    {
        $filters = request()->query();

        $query = $this->buildQuery($this->getBuilder(), $filters, true);
        $total_count = $query->count();
        $query = $this->buildQuery($this->getBuilder(), $filters, false);
        $items = $query->get();
        $params['items'] = $items;
        $params['total_count'] = $total_count;
        return $this->success($params);
    }

    public function store()
    {
        $params = request()->input();
        $model = $this->getModel();
        $model->fill($params);
        $res = $model->save();
        if ($res) {
            return $this->success($model);
        }else {
            return $this->failed('保存失败');
        }
    }

    public function show($id)
    {
        $model = $this->getModel();
        $res = $model->find($id);
        if ($res) {
            return $this->success($res);
        }else {
            return $this->notFond();
        }
    }

    public function update($id)
    {
        $model = $this->getModel();
        $result = $model->find($id);
        if (!$result) {
            return $this->notFond();
        }
        $result->fill(request()->input());
        $res = $model->save();
        if (!$res) {
            return $this->failed('修改失败');
        }else {
            return $this->success('修改成功');
        }
    }

    public function destroy($id)
    {
        $model = $this->getModel();
        $model = $model->find($id);
        if (!$model) {
            return $this->notFond();
        }
        $res = $model->delete();
        if (!$res) {
            return $this->failed('删除失败');
        }else {
            return $this->success();
        }
    }
}
