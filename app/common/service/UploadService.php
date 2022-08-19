<?php

namespace app\common\service;

use app\lib\exception\ParameterException;
use http\Client\Request;
use OSS\Core\OssException;
use OSS\OssClient;

class UploadService
{
    public function uploadFile($filePath)
    {
        $accessKeyId = env('oss.oss_access_key_id', 'LTAI5tBHz9jugfz2PKSfUue5');
        $accessKeySecret = env('oss.oss_access_key_secret', 'ZGrVXxfhdPB6noThyq3nAAu3peIPsB');
        $endpoint = env('oss.oss_endpoint', 'oss-cn-beijing.aliyuncs.com');
        $bucket = env('oss.oss_bucket', 'jiazhuangbang');
        $fileName = date('Y-m-d', time()) . '/' . md5(time() . rand(1111, 9999999)) . '.png';
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $result = $ossClient->uploadFile($bucket, $fileName, file_get_contents($filePath));
        } catch (OssException $e) {
            throw new ParameterException(['errMessage' => $e->getMessage()]);
        }
        return $result['info']['url'];
    }

}