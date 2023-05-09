<?php

namespace App\Admin\Repositories;

use App\Models\Article\Article as Model;
use ZHK\Tool\Admin\Repositories\Article as EloquentRepository;
use Dcat\Admin\Form;

class Article extends EloquentRepository
{
    /**
     * 数据保存前
     * @param Form $form
     * @return \Dcat\Admin\Http\JsonResponse|null
     */
    public function saving(Form $form)
    {
        if ($form->input('category_id')) {
            $cate = model('Article.Category')->find($form->input('category_id'));
            if ($cate) {
                $form->updates(['article_type' => $cate->type]);
            }
        }
        if (is_null($form->input('desc'))) {
            $form->updates(['desc' => ' ']);
        }
        if (is_null($form->input('source'))) {
            $form->updates(['source' => ' ']);
        }
        if (is_null($form->input('author'))) {
            $form->updates(['author' => ' ']);
        }
        if (!$form->isDeleting() && $form->input('attachment')) {
            $fileType = \Storage::disk('public')->getMimetype($form->input('attachment'));
            $form->updates(['file_type' => $fileType]);
        }
//        $this->setThumbnailInfo($form);
    }
}
