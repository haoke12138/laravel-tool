<?php

namespace ZHK\Tool\Common;

class FileUpload
{
    private $file = null;
    private $basePath = null;
    private $filepath = null;
    public $extensions = [
        'image' => ['jpeg', 'jpg', 'png', 'gif', 'bmp'],
        'video' => ['mp4', 'avi', 'rm', 'rmvp', 'wmv', 'm4v', 'mov', '3gp'],
        'office' => ['csv', 'doc', 'docx', 'pdf', 'xlsx', 'xls', 'ppt', 'pptx'],
        'compress' => ['zip', 'rar', '7z']
    ];

    public static function make($isPublic = true)
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
        return $this->file($name, $options, 'image');
    }

    public function images($name = 'images', $options = [])
    {
        $urls = [];
        if (!is_array(head($this->file))) return [$this->image($name, $options)];
        $files = $this->multiFileProcessing();

        foreach ($files as $file) {
            $this->file = $file;
            $urls[] = $this->image($name, $options);
        }

        return $urls;
    }

    public function files($name = 'images', $options = [])
    {
        $urls = [];
        if (!is_array(head($this->file))) return [$this->file($name, $options)];

        $files = $this->multiFileProcessing();
        foreach ($files as $file) {
            $this->file = $file;
            $urls[] = $this->file($name, $options);
        }

        return $urls;
    }

    /**
     * @param $type 归类名称 默认为images
     * @param array $options 文件类型 默认为常用图片,视频,压缩包,办公格式
     * @param null $extensionName 格式类型, 当前有image, video, office, compress
     * @return string
     * @throws \Exception
     */
    public function file($type, $options = [], $extensionName = null)
    {
        $extension = pathinfo($this->file['name'])['extension'];
        $extensions = empty($extensionName) ? array_merge(...array_values($this->extensions)) : $this->extensions[$extensionName];
        $extensions = empty($options) ? $extensions : array_intersect($extensions, $options);
        if (!in_array($extension, $extensions)) {
            throw new \Exception('上传文件类型不正确, 请检查文件是否是以下类型' . join(',', $extensions));
        }
        return $this->save("$this->basePath/$type/", $extension, $type);
    }

    private function save($basePath, $extension, $name)
    {
        $dirPath = $basePath . date('Y') . '/' . date('m') . '/';
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $filename = date("YmdHis") . rand(0, 999999) . ".$extension";
        if (!move_uploaded_file($this->file['tmp_name'], $dirPath . $filename)) {
            throw new \Exception('保存失败!', 500);
        }

        return $name . last(explode($name, $dirPath)) . $filename;
    }

    private function multiFileProcessing()
    {
        $files = [];
        foreach (head($this->file) as $key => $file) {
            $files[] = [
                'name' => $this->file['name'][$key],
                'type' => $this->file['type'][$key],
                'tmp_name' => $this->file['tmp_name'][$key],
                'error' => $this->file['error'][$key],
                'size' => $this->file['size'][$key],
            ];
        }

        return $files;
    }
}
