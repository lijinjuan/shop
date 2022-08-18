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
    // 获取首页轮播图的接口
    Route::get("banner/:bannerID", ":version.Banner/getBannerByID");

})->middleware(JwtAuthMiddleware::class);