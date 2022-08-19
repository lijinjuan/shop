<?php

namespace app\api\repositories;

use AlphaSnow\OSS\AppServer\Factory;
use think\facade\Config;

class FileSystemRepositories extends AbstractRepositories
{
    public function signature2DirectTransfer(string $userDir)
    {
        //读取配置
        $aliyunOssConfigure = Config::get('oss');
        $userDir && $aliyunOssConfigure['user_dir'] = $userDir;
        $aliyunOssToken = new Factory($aliyunOssConfigure);
        $res = $aliyunOssToken->makeToken();
        return $res->response()->toArray();
    }

}