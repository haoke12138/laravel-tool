<?php

namespace ZHK\Tool\Http\Controllers;

use ZHK\Tool\Services\Media;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class DcatMediaSelectorController extends Controller
{

    /**
     * @var Media
     */
    protected $mediaService;

    public function __construct()
    {
        $this->mediaService = service('ZHK.Tool:Media');
    }

    /**
     * 列表 ✓
     *
     * @param Request $request
     * @return false|string
     *
     * @bodyParam keyword string required 关键字
     * @bodyParam type string required 类型
     * @bodyParam order string required 排序方式
     * @bodyParam sort string required 排序字段名
     * @bodyParam limit string required 分页大小
     */
    public function getMediaList(Request $request)
    {
        $keyword = $request->get('keyword', '');
        $conditions = ['keyword' => ["%$keyword%", "%$keyword%"]];
        if (!empty($type = $request->get('type'))) $conditions['type'] = $type;
        if (!empty($groupId = $request->get('group_id'))) $conditions['media_group_id'] = $groupId;

        $data = $this->mediaService->getMediaList(
            $conditions,
            $request->get('sort', 'id'),
            $request->get('order', 'desc'),
            $request->get('limit', '25')
        );

        return response()->json(['code' => 200] + $data);
    }

    public function addGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails())
            return $this->failed($validator->errors()->first());

        return return_api(function ($obj) {
            $obj->data = $this->mediaService->addGroup($obj->request->get('name'));
            $obj->status = 'success';
        }, $request);
    }

    public function move(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
            'move_ids' => 'required',
        ]);

        if ($validator->fails())
            return $this->failed($validator->errors()->first());

        return return_api(function ($obj) {
            $obj->data = $this->mediaService->move($obj->request->get('group_id'), $obj->request->get('move_ids'));
            $obj->status = 'success';
        }, $request);
    }

    // 上传 ✓
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'move' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }

        return return_api(function ($obj) {
            $obj->data = $this->mediaService->upload(
                $obj->request->file('file'),
                $obj->request->get('media_group_id', 0),
                json_decode($obj->request->get('move'))
            );
            $obj->status = 'success';
        }, $request);
    }

    // 上传 删除✓
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delete_ids' => 'required|array',
            'delete_paths' => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }

        return return_api(function ($obj) {
            $obj->data = $this->mediaService->delete(
                $request->get('delete_ids'),
                $request->get('delete_paths')
            );
            $obj->status = 'success';
        }, $request);
    }
}
