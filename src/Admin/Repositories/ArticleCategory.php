<?php

namespace ZHK\Tool\Admin\Repositories;

use App\Models\Article\Category as Model;
use Dcat\Admin\Form;

class ArticleCategory extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
