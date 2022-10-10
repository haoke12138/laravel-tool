<?php

namespace ZHK\Tool\Admin\Repositories;

use App\Models\Article\Article as Model;
use Dcat\Admin\Form;

class Article extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;

    public function setThumbnailInfo(Form $form)
    {
        $imageInfo = GetImageSize(public_path('storage/' . $form->thumbnail));
        $form->width = $imageInfo[0];
        $form->height = $imageInfo[1];
    }
}
