<?php


// 登录
use app\agents\middleware\JwtAuthMiddleware;
use think\facade\Route;

// 获取图片的验证码的接口
Route::post(":version/create/captcha", ":version.Entry/createCaptcha")->allowCrossDomain();
// 代理商登录的接口
Route::post(":version/launch", ":version.Entry/userLaunch")->allowCrossDomain();

// 代理商后台操作的apis
Route::group(":version", function () {
    //新增代理商
    Route::post("add-agent", ":version.Agent/createAgents");
    //代理商列表
    Route::post("agent-list",":version.Agent/agentList");
    //代理商层级列表
    Route::post("agent-tree-list",":version.Agent/agentTreeList");

})->middleware(JwtAuthMiddleware::class)->allowCrossDomain();

//店铺相关接口
Route::group(":version", function () {
    //下级店铺列表
    Route::post("agent-store-list", ":version.Store/storeList");
    //获取店铺用户信息
    Route::get("agent-store-user-info/:id",":version.Store/storeUserInfo");
    //设置店内的备注内容
    Route::post("agent-store-remark",":version.Store/storeRemark");
    //冻结店铺
    Route::post("agent-stop-store",":version.Store/storeStop");
    //解冻店铺
    Route::post("agent-start-store",":version.Store/storeStart");
    //获取店铺基本信息
    Route::get("agent-store-info/:id",":version.Store/storeInfo");
    //审核店铺
    Route::post("agent-check-store",":version.Store/storeCheck");
    //获取店铺统计数据
    Route::get("agent-store-statistics/:id",":version.Store/storeStatistics");

})->middleware(JwtAuthMiddleware::class)->allowCrossDomain();

//订单相关数据
Route::group(":version", function () {
    //订单列表
    Route::post("agent-order-list", ":version.Order/orderList");

    //发货
    Route::post("agent-order-ship",":version.Order/orderShip");

})->middleware(JwtAuthMiddleware::class)->allowCrossDomain();

//会员记录相关列表
Route::group(":version", function () {
    //会员充值记录
    Route::post("agent-recharge-list", ":version.Store/rechargeList");
    //会员提现记录
    Route::post("agent-withdraw-list",":version.Store/withdrawList");


})->middleware(JwtAuthMiddleware::class)->allowCrossDomain();