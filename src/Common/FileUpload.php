<?php

namespace ZHK\Tool\Common;

class FileUpload
{
    private $file = null;
    private $basePath = null;

    public static function make($isPublic = false)
    {
        $self = new static();
        $self->file = $_FILES['file'];
        $self->basePath = $isPublic ? storage_path("app/public") : $_SERVER['DOCUMENT_ROOT'];

        return $self;
    }

    public function setBasePath($path)
    {
        $this->basePath = $path;
        return $this;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $name 归类名称 默认为images
     * @param array $options 文件类型 默认为jpeg, jpg, png, gif, bmp 只能在其中选择
     * @return string
     * @throws \Exception
     */
    public function image($name = 'images', $options = [])
    {
        $extension = pathinfo($this->file['name'])['extension'];

        $extensions = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
        $extensions = empty($options) ? $extensions : array_intersect($extensions, $options);
        if (!in_array($extension, $extensions)) {
            throw new \Exception('上传文件类型不正确, 请检查文件是否是以下类型' . join(',', $extensions));
        }

        return $this->save("$this->basePath/$name/", $extension, $name);
    }

    /**
     * @param string $type 归类名称
     * @return mixed|string
     * @throws \Exception
     */
    public function file($type)
    {
        $extension = pathinfo($this->file['name'])['extension'];
        $path = $this->save($this->basePath, $extension, $type);

        return count(explode($type, $this->basePath)) > 1 ? $path : last(explode($type, $path));
    }

    private function save($basePath, $extension, $name)
    {
        $dirPath = $basePath . date('Y') . '/' . date('m') . '/';
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $filename = date("YmdHis") . rand(0, 999999) . ".$extension";
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $dirPath . $filename)) {
            throw new \Exception('保存失败!', 500);
        }

        return $name . last(explode($name, $dirPath)) . $filename;
    }
}
