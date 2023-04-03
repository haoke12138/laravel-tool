<?php

namespace ZHK\Tool\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ZHK\Tool\Common\FileUpload;

class FileController extends Controller
{
    public function image(Request $request)
    {
        return \return_api(function ($obj) {
            $obj->data = ['url' => FileUpload::make(true)->image()];
        }, $request);
    }

    public function file(Request $request)
    {
        return \return_api(function ($obj) {
            $type = $obj->request->get('type', 'files');
            $obj->data = ['url' => FileUpload::make(true)->file($type)];
        }, $request);
    }
}