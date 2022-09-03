<?php

use Dcat\Admin\Form;
use Dcat\Admin\Show;
use Dcat\Admin\Grid;
use Dcat\Admin\Widgets\Modal;

if (!function_exists('setAdminFile')) {
    /**
     * @param Form|Form\NestedForm|ToolForm $form
     * @param $column
     * @param null $alice
     * @param string $extensions
     * @return Form\Field\File
     */
    function setAdminVideo($form, $column, $alice = null, $extensions = 'mp4,rm,rmvb,avi', $help = '建议使用MP4的视频格式')
    {
        $form = $form->file($column, $alice)
            ->autoUpload()
            ->chunked()                   // 开启分块传输
            ->chunkSize(1024 * 2)  // 设置分块传输的文件大小
            ->threads(5)            // 设置5个线程进行传输
            ->maxSize(1024 * 700)    // 设置最大传输大小
            ->accept($extensions);

        if (empty($help)) {
            return $form;
        }

        return $form->help($help);
    }
}

if (!function_exists('setAdminImage')) {
    /**
     * 设置图片上传
     * @param Form| $form
     * @param $column
     * @param null $required
     * @return Form\Field\Image
     */
    function setAdminImage($form, $column, $alice = null)
    {
        return $form->image($column, $alice)->autoUpload()->accept('jpg,png,gif,jpeg')->removable(false)->retainable()
            ->chunked()                   // 开启分块传输
            ->chunkSize(1024 * 2)  // 设置分块传输的文件大小
            ->threads(5)            // 设置5个线程进行传输
            ->maxSize(1024 * 700);    // 设置最大传输大小
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
        return $form->multipleImage($column, $alice)->autoUpload()->accept('jpg,png,gif,jpeg')->sortable()
            ->chunked()                   // 开启分块传输
            ->chunkSize(1024 * 2)  // 设置分块传输的文件大小
            ->threads(5)            // 设置5个线程进行传输
            ->maxSize(1024 * 700);    // 设置最大传输大小
    }
}

if (!function_exists('setAdminTextarea')) {
    /**
     * textarea显示修改
     * @param $show
     * @param $column
     * @return Show\Field
     */
    function setAdminTextarea(Show $show, $column, $alice = null)
    {
        return $show->field($column, $alice)->unescape()->as(function () use ($column) {
            return str_replace("\n", '<br>', $this->$column);
        });
    }
}

if (!function_exists('setActionModel')) {
    /**
     * 添加模态框按钮
     * @param $show
     * @param $column
     * @return mixed
     */
    function setActionModel(Grid\Displayers\Actions $actions, $object, $title = '', $param = [], $iconClass = 'feather icon-award')
    {
        $actions->append(
            Modal::make()->title($title)->body($object->payload($param))->lg()
                ->button("<i title='$title' class='$iconClass' style='color: #4c60a3'></i>&nbsp;")
        );
    }
}
