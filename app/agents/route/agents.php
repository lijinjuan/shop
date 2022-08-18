<?php


// 登录
use app\agents\middleware\JwtAuthMiddleware;
use think\facade\Route;

// 获取图片的验证码的接口
Route::post(":version/create/captcha", ":version.Entry/createCaptcha");

// 代理商登录的接口
Route::post(":version/launch", ":version.Entry/userLaunch");

// 代理商后台操作的apis
Route::group(":version", function () {
    //新增代理商
    Route::post("add-agent", ":version.Agent/createAgents");
    //代理商列表
    Route::post("agent-list",":version.Agent/agentList");
    //代理商层级列表
    Route::post("agent-tree-list",":version.Agent/agentTree-list");

})->middleware(JwtAuthMiddleware::class);