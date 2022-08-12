<?php

use app\lib\exception\ExceptionHandle;

// 容器Provider定义文件
return [
    'think\exception\Handle' => ExceptionHandle::class,
    \app\api\servlet\contract\ServletFactoryInterface::class => \app\api\servlet\ServletFactory::class,
];
