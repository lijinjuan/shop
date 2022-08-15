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

// 客户端首页接口
Route::group(":version", function () {
    // 获取首页轮播图的接口
    Route::get("banner/:bannerID", ":version.Banner/getBannerByID");
    // 获取首页品牌列表的接口
    Route::get("brands-list", ":version.Brand/getBrandsList");

    Route::get("goods", ":version.Goods/getPlatformGoods");
})->json();


// 客户端店铺接口
Route::group(":version", function () {
    // 获取店铺基本信息的接口
    Route::get("shop-base-info", ":version.Shop/getStoreByBasicInfo");
    // 获取店铺统计信息的接口
    Route::get("shop-statistics", ":version.Shop/getStoreByBasicStatistics");
    // 提交开店铺的信息
    Route::post("shop-apply", ":version.Shop/apply2OpenStore");
    // 获取我的店铺的商品列表的接口
    Route::get("shop-goods-list", ":version.Shop/getGoodsListByMyStore");

})->middleware(JwtAuthMiddleware::class)->json();


Route::group(":version", function () {
    // test token
    Route::post("test/token", ":version.Entry/testToken")->middleware(JwtAuthMiddleware::class);

    // banner api
})->json();




