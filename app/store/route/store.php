<?php

use app\store\middleware\JwtAuthMiddleware;
use think\facade\Route;

//  获取图片的验证码的接口
Route::post(":version/create/captcha", ":version.Entry/createCaptcha");

//  店铺登录的接口
Route::post(":version/launch", ":version.Entry/storeLaunch");

// 店铺后台的基本接口
Route::group(":version", function () {
    // 获取店铺的基本信息的接口
    Route::get("store-base-info", ":version.Store/getStoreBaseInfo");
    // 保存店铺的基本信息的接口
    Route::post("edit-store-base-info", ":version.Store/editStoreBaseInfo");
    // 获取店铺列表的接口
    Route::post("store-list", ":version.Store/getStoreList");
    // 获取店铺下级树形列表的接口
    Route::post("store-tree-list", ":version.Store/getStoreTreeList");
    // 查看店铺的订单列表的接口
    Route::post("store-order-list", ":version.Order/getOrderListByStore");
    // 订单列表中支付操作的接口
    Route::post("store-order-pay", ":version.Order/order2PayByStore");

})->middleware(JwtAuthMiddleware::class);