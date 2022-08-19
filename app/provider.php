<?php

use app\lib\exception\ExceptionHandle;

// 容器Provider定义文件
return [
    'think\exception\Handle' => ExceptionHandle::class,
    \app\api\servlet\contract\ServletFactoryInterface::class => \app\api\servlet\ServletFactory::class,
    \app\agents\servlet\contract\ServletFactoryInterface::class => \app\agents\servlet\ServletFactory::class,
    \app\store\servlet\contract\ServletFactoryInterface::class => \app\store\servlet\ServletFactory::class,
    \app\admin\servlet\contract\ServletFactoryInterface::class => \app\admin\servlet\ServletFactory::class,
    \app\common\service\InviteServiceInterface::class => \app\common\service\InviteCodeService::class,
];
