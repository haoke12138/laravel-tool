<?php

use Dcat\Admin\Form;
use Dcat\Admin\Show;


if (!function_exists('setAdminImage')) {
    /**
     * 设置图片上传
     * @param $form
     * @param $column
     * @param null $required
     * @return Form\Field\Image
     */
    function setAdminImage(Form $form, $column, $alice = '')
    {
        return $form->image($column, $alice)->autoUpload()->accept('jpg,png,gif,jpeg')->removable(false)->retainable();
    }
}

if (!function_exists('setAdminMultiImage')) {
    /**
     * 设置多图上传
     * @param $form
     * @param $column
     * @param null $required
     * @return Form\Field\MultipleImage
     */
    function setAdminMultiImage(Form $form, $column, $alice = null)
    {
        return $form->multipleImage($column, $alice)->autoUpload()->accept('jpg,png,gif,jpeg')->sortable();
    }
}

if (!function_exists('setAdminTextarea')) {
    /**
     * textarea显示修改
     * @param $show
     * @param $column
     * @return Show\Field
     */
    function setAdminTextarea(Show $show, $column)
    {
        return $show->field($column)->unescape()->as(function () use ($column) {
            return str_replace("\n", '<br>', $this->$column);
        });
    }
}
