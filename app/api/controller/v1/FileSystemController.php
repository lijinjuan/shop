<?php

namespace app\api\controller\v1;

use app\api\repositories\FileSystemRepositories;
use app\lib\exception\ParameterException;
use think\facade\Filesystem;
use think\Request;


class FileSystemController
{
    /**
     * @var FileSystemRepositories
     */
    protected FileSystemRepositories $fileSystemRepositories;

    /**
     * @param FileSystemRepositories $fileSystemRepositories
     */
    public function __construct(FileSystemRepositories $fileSystemRepositories)
    {
        $this->fileSystemRepositories = $fileSystemRepositories;
    }


    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function directTransferBySignUrl(Request $request)
    {
        $userDir = $request->get('userDir','upload');
        $signature = $this->fileSystemRepositories->signature2DirectTransfer($userDir);
        return renderResponse($signature);
    }


    public function directTransferByCallback()
    {
        return renderResponse();
    }

    /**
     * uploadFile
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function uploadFile(Request $request)
    {
        $file = $request->file('image');
        $filePath = Filesystem::disk("public")->putFile("api", $file);
        if ($filePath == false)
            throw new ParameterException(["errMessage" => "上传文件失败..."]);
        $filePath = "/storage/" . $filePath;
        return renderResponse(compact('filePath'));
    }

}