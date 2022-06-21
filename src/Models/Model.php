<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Exceptions\NotFoundException;
use App\Models\Exceptions\ParamException;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use HasFactory;

    /**
     * @param DateTimeInterface $date
     * @return string
     * @see 为 array / JSON 序列化准备日期格式
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @param array $conditions
     * @param string $name
     * @return string
     * @see 根据条件找到首条记录中的$name字段的值
     */
    public function getValue($conditions, $name = 'title')
    {
        $v = $this->where($conditions)->value($name);

        return isset($v) ? $v : '';
    }

    /**
     * @param array $conditions
     * @param string $key
     * @param string $value
     * @return array
     * @see 根据条件获取键值对同pluck()
     */
    public function getOptions($conditions = [], $key = 'id', $value = 'title')
    {
        return $this->where($conditions)->pluck($value, $key);
    }

    /**
     * @param array $conditions
     * @param $page
     * @param $limit
     * @param array $orderBy 排序 [字段名 => 'desc'/'asc']
     * @param array $with
     * @return array
     * @see 搜索
     */
    public function search(array $conditions, $page, $limit, $orderBy = [], $with = [])
    {
        $list = $this->cWhere($conditions)->offset(($page - 1) * $limit)->limit($limit);
        foreach ($orderBy as $key => $value) {
            $list->orderBy($key, $value);
        }
        foreach ($with as $value) {
            $list->with($value);
        }

        return [
            'list' => $list->get(),
            'count' => $count = $list->count(),
            'countPage' => ceil($count / $limit),
        ];
    }

    /**
     * @param string $message
     * @return NotFoundException
     * @see 找不到资源
     */
    public function notFound($message = '找不到资源！')
    {
        return new NotFoundException($message);
    }

    /**
     * @param string $message
     * @return ParamException
     * @see 参数错误
     */
    public function paramError($message = '参数错误！')
    {
        return new ParamException($message);
    }

    /**
     * 查询拼装作用域
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCustomWhere($query, array $conditions)
    {
        foreach ($conditions as $key => $condition) {
            $dec = $this->declares();
            if (empty($dec[$key])) {
                continue;
            }
            if (is_array($dec[$key])) {
                $query->whereRaw(last($dec[$key]), $condition, head($dec[$key]));
            } else {
                $query->whereRaw((string)$dec[$key], [$condition]);
            }
        }

        return $query;
    }

    /**
     * 查询拼装作用域
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCWhere($query, array $conditions)
    {
        return $this->CustomWhere($conditions);
    }

    /**
     * 查询变量声明
     *     Example:
     *         return [
     *             'liketitle' => 'title like  ?',
     *             'eqTitle' => 'title = ?',
     *             'actor.name.eq' => 'actor.name = ?',
     *             'OrTitle' => ['or', 'title = ?'],
     *         ];
     *     use query:
     *         $conditions = [
     *             'liketitle' => '%susan%',
     *             'eqTitle' => '张三',
     *             'actor.name.eq' => '苏三',
     *             'OrTitle' => '里斯',
     *         ];
     *         Model::customWhere($conditions);
     *          or
     *         (new Model)->customWhere();
     * @return array
     */
    protected function declares()
    {
        return [];
    }
}
