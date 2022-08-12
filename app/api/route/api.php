<?php

use app\api\middleware\JwtAuthMiddleware;
use think\facade\Route;

// 登录
Route::post(":version/launch", ":version.Entry/userLaunch")->json();
// 注册
Route::post(":version/register", ":version.Entry/registerNewUser")->json();

// 用户收货地址的管理
Route::group(":version", function () {
    // 获取用户收货地址的接口
    Route::get("ship-address", ":version.UserAddress/getUserAddressListByToken");
    // 新增用户收货地址的接口
    Route::post("add-ship-address", ":version.UserAddress/createUserAddress");
    // 编辑用户收货地址的接口
    Route::put("edit-ship-address/:addressID", ":version.UserAddress/editUserAddress");
    // 删除用户收货地址的接口
    Route::delete("del-ship-address/:addressID", ":version.UserAddress/deleteUserAddress");

})->middleware(JwtAuthMiddleware::class)->json();


Route::group(":version", function () {
    // test token
    Route::post("test/token", ":version.Entry/testToken")->middleware(JwtAuthMiddleware::class);

    // banner api
})->json();

Route::get(":version/banner/:bannerID", ":version.Banner/getBannerByID");


