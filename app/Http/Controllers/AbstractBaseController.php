<?php


namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseController extends Controller
{
    /** @var array 搜索列白名单 */
    private $allow_search_columns = [];
    /** @var array 显示列白名单 */
    private $allow_show_columns = [];
    /** @var array 附加搜索条件 */
    private $append_condition = [];
    /** @var Builder 模型筛选构造器 */
    private $builder = null;
    /** @var Model 模型 */
    private $model = null;
    /** @var array 输出内容 */
    private $base_params = [];

    public function setAllowSearchColumns(array $allow_search_columns = [])
    {
        $this->allow_search_columns = $allow_search_columns;
    }

    public function getAllowSearchColumns() :array
    {
        return $this->allow_search_columns;
    }

    public function setAllowShowColumns(array $allow_show_columns = [])
    {
        $this->allow_show_columns = $allow_show_columns;
    }

    public function getAllowShowColumns() :array
    {
        return $this->allow_show_columns;
    }

    public function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function getBuilder() :Builder
    {
        return $this->builder;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    public function getModel() :Model
    {
        return $this->model;
    }

    public function setAppendCondition(array $condition)
    {
        $this->append_condition = $condition;
    }

    public function getAppendCondition() :array
    {
        return $this->append_condition;
    }

    public function getBaseParams() :array
    {
        return $this->base_params;
    }

    public function __construct()
    {

    }

    public function buildQuery(Builder $builder, array $filters, bool $to_get_total_count = false) :Builder
    {
        // 附加查询条件
        if (count($this->getAppendCondition()) > 0) {
            $conditions = $this->getAppendCondition();
            foreach ($conditions as $cond) {
                $builder->where($cond['column'], $cond['value']);
            }
        }

        /** 获取总数则不考虑分页排序 */
        if (!$to_get_total_count) {
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
            if (array_key_exists('_orderBy', $filters)) {
                foreach ($filters['_orderBy'] as $key => $column) {
                    $builder = $builder->orderBy($column);
                }
            }
            // 倒序
            if (array_key_exists('_orderByDesc', $filters)) {
                foreach ($filters['_orderByDesc'] as $key => $column) {
                    $builder = $builder->orderByDesc($column);
                }
            }
        }

        // 查询条件
        if (array_key_exists('filters', $filters)) {
            $wheres = $filters['filters'];
            foreach ($wheres as $k => $v) {
                if (is_array($v)) {
                    if (array_key_exists('value', $v) && array_key_exists('column', $v) && array_key_exists('sign', $v)) {
                        // 搜索列白名单
                        if (count($this->getAllowSearchColumns()) == 0 || array_key_exists($v['column'], $this->getAllowSearchColumns())) {
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

                                        } else {
                                            $builder = $builder->whereHas($v['relation_name'], function (Builder $query) use ($v) {
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

                    }
                }
            }
        }
        // 显示列白名单
        if (count($this->getAllowShowColumns()) > 0) {
            $builder = $builder->select($this->getAllowShowColumns());
        }
        return $builder;
    }
}
