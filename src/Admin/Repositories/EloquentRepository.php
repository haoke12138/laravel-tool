<?php

namespace ZHK\Tool\Admin\Repositories;

use Dcat\Admin\Form;
use Dcat\Admin\Repositories\EloquentRepository as BaseRepository;

class EloquentRepository extends BaseRepository
{
    public function store(Form $form)
    {
        if ($res = $this->saving($form)) {
            return $res;
        }

        $id = parent::store($form);
        if ($res = $this->saved($form, $id)) {
            return $res;
        }

        return $id;
    }

    public function update(Form $form)
    {
        if ($res = $this->saving($form)) {
            return $res;
        }

        $parentRes =  parent::update($form);
        if ($res = $this->saved($form, $form->getKey())) {
            return $res;
        }

        return $parentRes;
    }

    public function delete(Form $form, array $originalData)
    {
        if ($res = $this->saving($form)) {
            return $res;
        }

        $parentRes =  parent::delete($form, $originalData);
        if ($res = $this->saved($form, $form->getKey())) {
            return $res;
        }

        return $parentRes;
    }

    /**
     * 数据保存前
     * @param Form $form
     * @return \Dcat\Admin\Http\JsonResponse|null
     */
    protected function saving(Form $form)
    {

    }

    /**
     * 数据保存后
     * @param Form $form
     * @param int $id
     * @return \Dcat\Admin\Http\JsonResponse|null
     */
    protected function saved(Form $form, $id = null)
    {

    }

    /**
     * 设置后台不填数据为空字符串
     * @param Form $form
     * @param $columns
     */
    protected function setNullString(Form $form, $columns)
    {
        $columns = is_array($columns) ? $columns : [$columns];
        $fields = [];
        foreach ($columns as $column) {
            if (is_null($form->input($column))) {
                $fields[$column] = '';
            }
        }
        $form->updates($fields);
    }
}
