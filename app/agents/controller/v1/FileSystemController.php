<?php

namespace app\agents\controller\v1;

use app\lib\exception\ParameterException;
use think\facade\Filesystem;
use think\Request;

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
        $filePath = Filesystem::disk("public")->putFile("agents", $file);
        if ($filePath == false)
            throw new ParameterException(["errMessage" => "上传文件失败..."]);
        $filePath = "/storage/" . $filePath;
        return renderResponse(compact('filePath'));
    }

}