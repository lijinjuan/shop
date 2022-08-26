<?php

use app\store\middleware\JwtAuthMiddleware;
use think\facade\Route;

//  获取图片的验证码的接口
Route::post(":version/create/captcha", ":version.Entry/createCaptcha")->allowCrossDomain();

//  店铺登录的接口
Route::post(":version/launch", ":version.Entry/storeLaunch")->allowCrossDomain();
Route::post(":version/upload-file", ":version.FileSystem/uploadFile");

// 店铺后台的基本接口
Route::group(":version", function () {
    // 获取店铺的基本信息的接口
    Route::post("store-base-info", ":version.Store/getStoreBaseInfo");
    // 保存店铺的基本信息的接口
    Route::post("edit-store-base-info", ":version.Store/editStoreBaseInfo");
    // 修改用户密码的接口
    Route::post("alter-password", ":version.Entry/alterUserPassword");
    // 获取店铺列表的接口
    Route::post("store-list", ":version.Store/getStoreList");
    // 获取店铺下级树形列表的接口
    Route::post("store-tree-list", ":version.Store/getStoreTreeList");
    // 查看店铺的订单列表的接口
    Route::post("store-order-list", ":version.Order/getOrderListByStore");
    // 订单列表中支付操作的接口
    Route::post("store-order-pay", ":version.Order/order2PayByStore");
    // 店铺帐变日志的接口
    Route::post("store-account-list", ":version.Store/getStoreAccountList");
    // 店铺中商品列表的接口
    Route::post("store-goods-list", ":version.Store/getStoreGoodsList");
    // 店铺中商品下架的接口
    Route::post("goods-take-down/:goodsID", ":version.Store/takeDownStoreGoods");
    // 获取平台商品列表的接口
    Route::post("goods-list", ":version.Goods/getPlatformGoodsList");
    // 平台中商品上架的接口
    Route::post("goods-on-sale/:goodsID", ":version.Goods/onSaleGoods2Store");
    // 店铺的首页统计的接口
    Route::post("store-statistics", ":version.Store/getStoreStatistics");

})->middleware(JwtAuthMiddleware::class)->allowCrossDomain();

Route::miss(function () {
    return 404;
});