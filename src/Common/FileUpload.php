<?php

namespace ZHK\Tool\Common;

use Illuminate\Http\UploadedFile;

class FileUpload
{
    private $file = null;
    private $basePath = null;
    private $filepath = null;
    public static $extensions = [
        'image' => ['jpeg', 'jpg', 'png', 'gif', 'bmp'],
        'audio' => ['mp3', 'wav', 'flac', '3pg', 'aa', 'aac', 'ape', 'au', 'm4a', 'mpc', 'ogg'],
        'video' => ['mp4', 'avi', 'rm', 'rmvp', 'wmv', 'm4v', 'mov', '3gp'],
        'office' => ['csv', 'doc', 'docx', 'pdf', 'xlsx', 'xls', 'ppt', 'pptx'],
        'compress' => ['zip', 'rar', '7z'],
        'text' => ['txt', 'pac', 'log', 'md'],
        'code' => ['php', 'js', 'java', 'python', 'ruby', 'go', 'c', 'cpp', 'sql', 'm', 'h', 'json', 'html', 'aspx'],
    ];

    public static function make($isPublic = false, UploadedFile $file = null)
    {
        $self = new static();
        $self->file = $file ? $file : $_FILES['file'];
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

    /**
     * @param string $type 归类名称 默认为images
     * @param array $options 文件类型 默认为常用图片,视频,压缩包,办公格式
     * @param null $extensionName 格式类型, 当前有image, video, office, compress
     * @return string
     * @throws \Exception
     */
    public function file($type, $options = [], $extensionName = null)
    {
        $extension = pathinfo($this->file['name'])['extension'];
        $extensions = empty($extensionName) ? array_merge(...array_values(self::$extensions)) : self::$extensions[$extensionName];
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
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $dirPath . $filename)) {
            throw new \Exception('保存失败!', 500);
        }

        return $name . last(explode($name, $dirPath)) . $filename;
    }

    /**
     * 文件上传
     * 支持版本 >=7.2
     *
     * @param string $dir
     * @param string $isEncrypt
     */
    public function uoload($dir, $isEncrypt)
    {
        $file = $this->file;
        #文件类型
        $mime_type = $file->getMimeType();

        #保存文件夹(默认为upload_files)
        $folder = $dir . '/' . date('Ym'); //保存文件夹

        #生成文件名
        $file_name = $this->_getFileName($isEncrypt);

        //配置上传信息
        config([
            'filesystems.default' => config('admin.upload.disk', 'admin')
        ]);

        return $file->storeAs($folder, $file_name);
    }

    private function _getFileName($isEncrypt = true)
    {
        $fileName = $this->file->getClientOriginalName();
        if ($isEncrypt)
            $fileName = md5(rand(1, 99999) . $this->file->getClientOriginalName()) . "." . $this->file->getClientOriginalExtension();

        return $fileName;
    }

    /**
     * @param $filePath | 文件绝对路径
     * @return bool|int|string
     */
    public static function getFileType($filePath)
    {
        foreach (self::$extensions as $type => $extensions) {
            if (in_array(pathinfo($filePath, PATHINFO_EXTENSION), $extensions)) {
                return $type;
            }
        }
        return 'other';
    }
}
