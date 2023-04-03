<?php

namespace ZHK\Tool\Services;

use Dcat\Admin\Admin;
use DeMemory\DcatMediaSelector\Helpers\ApiResponse;
use DeMemory\DcatMediaSelector\Helpers\FileUtil;
use DeMemory\DcatMediaSelector\Models\MediaGroup;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use ZHK\Tool\Common\FileUpload;

class Media extends Service
{
    use ApiResponse;

    /**
     * @var \ZHK\Tool\Models\Media\Media
     */
    private $model;

    /**
     * @var \ZHK\Tool\Models\Media\Group
     */
    private $groupModel;

    public function __construct()
    {
        $this->model = model('ZHK.Tool:Media.Media');
        $this->groupModel = model('ZHK.Tool:Media.Group');
    }

    public function getMediaList($conditions, $sort, $order, $limit)
    {
        $list = $this->model->query()->cwhere($conditions)->orderBy($sort, $order)->paginate($limit);

        $dataList = [];
        foreach ($list as $value) {
            $dataList[] = array(
                'id' => $value->id,
                'media_group_name' => empty($value->mediaGroup) ? '无' : $value->mediaGroup->name,
                'media_type' => $value->type,
                'path' => $value->path,
                'url' => $value->url,
                'size' => $value->size,
                'file_ext' => $value->file_ext,
                'name' => $value->file_name,
                'created_at' => $value->created_at,
            );
        }

        return ['data'=> $dataList, 'limit' => $list->total()];
    }

    public function addGroup($name)
    {
        $model = $this->groupModel->where('name', $name)->first();
        if ($model) throw $this->notFound('该名称已存在!');
        $time = date('Y-m-d H:i:s');
        $id = $this->groupModel->insertGetId([
//            'admin_id' => Admin::user()->id,
            'admin_id' => 0,
            'name' => $name,
            'created_at' => $time,
            'updated_at' => $time
        ]);

        return $id;
    }

    public function move($groupId, $moveIds)
    {
        $this->model->query()->whereIn('id', explode(',', $moveIds))
            ->update(['media_group_id' => $groupId]);

        return true;
    }

    public function upload(UploadedFile $file, $mediaGroupId, $move)
    {
        #保存文件,并返回存储路径
        $path = FileUpload::make(true, $file)->uoload($move->dir, $move->fileNameIsEncrypt);

        #获取文件在类型数组中属于哪一类 Storage::disk(config('admin.upload.disk'))->url($path)
        $getFileType = FileUpload::getFileType(Storage::disk(config('admin.upload.disk'))->url($path));

        #文件类型拆分(类型,前缀['type' => 'image', 'suffix' => 'jpeg'])
        $type_info = $this->_getTypeInfoByMimeType($file->getMimeType());

        #组装文件信息
        $meta = $this->_getMeta($file, $getFileType, $type_info['type']);

        $time = date('Y-m-d H:i:s');
        $data = [
//            'admin_id' => Admin::user()->id,
            'admin_id' => 0,
            'media_group_id' => $mediaGroupId,
            'path' => $path,
            'file_name' => last(explode('/', $path)),
            'size' => $file->getSize(),
            'type' => $getFileType,
            'file_ext' => $file->getClientOriginalExtension(),
            'disk' => config('filesystems.default'),
            'meta' => json_encode($meta),
            'created_at' => $time,
            'updated_at' => $time
        ];

        return $this->model->query()->create($data);
    }

    public function delete($deleteIds, $deletePaths)
    {
        $this->model->query()->whereIn('id', $deleteIds)->delete();

        foreach ($deletePaths as $v) {
            $disk = Storage::disk(config('admin.upload.disk'));
            $exists = $disk->exists($v);
            if ($exists)
                $disk->delete($v);
        }

        return true;
    }

    private function _getMeta($file, $getFileType, $format)
    {
        switch ($getFileType) {
            case 'image':
                $manager = new ImageManager();
                $image = $manager->make($file);
                $meta = [
                    'format' => $format,
                    'suffix' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'width' => $image->getWidth(),
                    'height' => $image->getHeight()
                ];
                break;
            case 'video':
            case 'audio':
            case 'powerpoint':
            case 'code':
            case 'zip':
            case 'text':
                $meta = [
                    'format' => $format,
                    'suffix' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'width' => 0,
                    'height' => 0
                ];
                break;
            default:
                $meta = [
                    'format' => $format,
                    'suffix' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'width' => 0,
                    'height' => 0
                ];
        }
        return $meta;
    }

    private function _getTypeInfoByMimeType($mt)
    {
        $arr = explode('/', $mt);
        return [
            'type' => $arr[0],
            'suffix' => $arr[1]
        ];
    }

//    private function _getFileName($move, $file)
//    {
//        $fileName = $file->getClientOriginalName();
//        if ($move->fileNameIsEncrypt)
//            $fileName = md5(rand(1, 99999) . $file->getClientOriginalName()) . "." . $file->getClientOriginalExtension();
//        dd($fileName);
//
//        return $fileName;
//    }
}
