<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseController extends Controller
{
    use ApiResponse;

    private $allow_search_columns = [];
    private $allow_show_columns = [];
    private $builder = null;
    private $mp_info = null;
    private $model = null;
    private $append_condition = [];

    public function getAllowSearchColumns()
    {
        return $this->allow_search_columns;
    }

    public function getAllowShowColumns()
    {
        return $this->allow_show_columns;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getAppendCondition()
    {
        return $this->append_condition;
    }

    public function setAppendCondition(array $condition = [])
    {
        $this->append_condition = $condition;
    }

    public function setAllowSearchColumns(array $allow_search_columns = [])
    {
        $this->allow_search_columns = $allow_search_columns;
    }

    public function setAllowShowColumns(array $allow_show_columns = [])
    {
        $this->allow_show_columns = $allow_show_columns;
    }

    public function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    public function __construct()
    {

    }

    public function buildQuery(Builder $builder, array $filters, array $allow_search_columns = [], array $allow_show_columns = [], bool $withPage = false)
    {
        if ($withPage) {
            // 限制查询条数
            if (array_key_exists('_limit', $filters)) {
                $builder = $builder->limit($filters['_limit']);
            } else {
                $builder = $builder->limit(10);
            }
            // 跳过条数
            if (array_key_exists('_skip', $filters)) {
                $builder = $builder->skip($filters['_skip']);
            }
            // 排序
            if (array_key_exists('_orderBy', $filters) && array_key_exists('_orderByDesc', $filters)) {
                foreach ($filters['_orderBy'] as $key => $column) {
                    if (array_key_exists($key, $filters['_orderByDesc'])) {
                        $type = $filters['_orderByDesc'][$key] == 'true' ? 'DESC' : 'ASC';
                        $builder = $builder->orderBy($column, $type);
                    }
                }
            } else {
                $builder = $builder->orderBy('id', 'DESC');
            }
        }
        // 查询条件
        if (array_key_exists('filters', $filters)) {
            $wheres = array_merge($filters['filters'], $this->getAppendCondition());
            foreach ($wheres as $k => $v) {
                if (is_array($v)) {
                    if (array_key_exists('value', $v) && array_key_exists('column', $v)) {
                        // 搜索列白名单
                        if (array_key_exists('sign', $v)) {
                            if (count($allow_search_columns) == 0 || array_key_exists($v['column'], $allow_search_columns)) {
                                switch ($v['sign']) {
                                    case 'whereBetween':
                                        $builder = $builder->whereBetween($v['column'], $v['value']);
                                        break;
                                    case 'whereIn':
                                        $builder = $builder->whereIn($v['column'], $v['value']);
                                        break;
                                    case 'where':
                                        if (array_key_exists('compare', $v)) {
                                            if ($v['compare'] == 'like') {
                                                $builder = $builder->where($v['column'], $v['compare'], '%' . $v['value'] . '%');
                                            } else {
                                                $builder = $builder->where($v['column'], $v['compare'], $v['value']);
                                            }
                                        } else {
                                            $builder = $builder->where($v['column'], $v['value']);
                                        }
                                        break;
                                    case 'relation':
                                        if (array_key_exists('relation_name', $v)) {
                                            if (array_key_exists('compare', $v)) {
                                                $relation_array = explode('.', $v['relation_name']);
                                                if (count($relation_array) == 1) {
                                                    $builder = $builder->whereHas($relation_array[0], function (Builder $query) use ($v) {
                                                        if ($v['compare'] == 'like') {
                                                            return $query->where($v['column'], $v['compare'], '%' . $v['value'] . '%');
                                                        } else {
                                                            return $query->where($v['column'], $v['compare'], $v['value']);
                                                        }
                                                    });
                                                } else if (count($relation_array) == 2) {
                                                    $builder = $builder->whereHas($relation_array[0], function (Builder $query) use ($v, $relation_array) {
                                                        return $query->whereHas($relation_array[1], function (Builder $query) use ($v) {
                                                            if ($v['compare'] == 'like') {
                                                                return $query->where($v['column'], $v['compare'], '%' . $v['value'] . '%');
                                                            } else {
                                                                return $query->where($v['column'], $v['compare'], $v['value']);
                                                            }
                                                        });
                                                    });
                                                }

                                            } else if (array_key_exists('value', $v)) {
                                                $relationName = $v['relation_name'];
                                                $builder = $builder->whereHas($relationName, function (Builder $query) use ($v) {
                                                    return $query->where($v['column'], $v['value']);
                                                });
                                            }
                                        }
                                        break;
                                    default:
                                        $builder = $builder->where($v['column'], $v['value']);
                                        break;
                                }
                            }
                        }else {
                            $builder = $builder->where($v['column'], $v['value']);
                        }
                    }
                }
            }
        }
        // 显示列白名单
        if (count($allow_show_columns) > 0) {
            $builder = $builder->select($allow_show_columns);
        }
        return $builder;
    }

    public function index()
    {
        $filters = request()->query();

        $query = $this->buildQuery($this->getBuilder(), $filters, $this->getAllowSearchColumns(), $this->getAllowShowColumns());
        $total_count = $query->count();
        $query = $this->buildQuery($this->getBuilder(), $filters, $this->getAllowSearchColumns(), $this->getAllowShowColumns(), true);
        $items = $query->get();
        return $this->success(['items' => $items, 'total_count' => $total_count]);
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
        $res = $model->where('id', $id)->first();
        if ($res) {
            return $this->success($res);
        }else {
            return $this->notFond();
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
        $model->fill($params);
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
        $model = $model->where('id', $id)->first();
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
