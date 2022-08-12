<?php

use app\api\middleware\JwtAuthMiddleware;
use think\facade\Route;

// 登录
Route::post(":version/launch", ":version.Entry/userLaunch")->json();


Route::group(":version", function () {
    // test token
    Route::post("test/token", ":version.Entry/testToken")->middleware(JwtAuthMiddleware::class, "12323","1312");


})->json();

