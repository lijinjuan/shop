<?php

namespace app\admin\controller\v1;

use app\lib\exception\ParameterException;
use think\facade\Filesystem;
use think\Request;

/**
 * \app\admin\controller\v1\FileSystemController
 */
class FileSystemController
{
    /**
     * uploadFile
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function uploadFile(Request $request)
    {
        $file = $request->file('image');
        $filePath = Filesystem::disk("public")->putFile("admin", $file);
        if ($filePath == false)
            throw new ParameterException(["errMessage" => "上传文件失败..."]);
        $filePath = "/storage/" . $filePath;
        return renderResponse(compact('filePath'));
    }
}