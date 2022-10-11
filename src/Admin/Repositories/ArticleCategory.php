<?php

namespace ZHK\Tool\Admin\Repositories;

use App\Admin\Repositories\EloquentRepository as EloquentRepository;
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
