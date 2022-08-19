<?php

namespace app\api\controller\v1;

use app\api\repositories\FileSystemRepositories;
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

}