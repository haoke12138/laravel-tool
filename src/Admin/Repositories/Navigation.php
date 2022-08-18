<?php

namespace ZHK\Tool\Admin\Repositories;

use App\Models\Navigation as Model;
use Dcat\Admin\Form;

class Navigation extends EloquentRepository
{
    protected $eloquentClass = Model::class;

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
}
