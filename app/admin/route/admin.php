<?php

use app\admin\middleware\JwtAuthMiddleware;
use think\facade\Route;

// 获取图片的验证码的接口
Route::post(":version/create/captcha", ":version.Entry/createCaptcha");
// 总后台登录的接口
Route::post(":version/launch", ":version.Entry/admin2Launch");

// 总后台的操作
Route::group(":version", function () {
    // 获取商品列表的接口
    Route::post("goods-list",":version.Goods/getGoodsListByPaginate");
    // 新增代理商的接口
    Route::post("add-goods", ":version.Goods/createGoods");
    // 获取商品分类的接口
    Route::post("category-list", ":version.Goods/getCategoryListByCategoryID");

});

//总后台退款配置
Route::group(":version", function () {
    //新增退款配置
    Route::post("add-refund-config",":version.RefundConfig/addRefundConfig");
    // 编辑退款配置
    Route::put("edit-refund-config/:id", ":version.RefundConfig/editRefundConfig");
    // 删除退款配置
    Route::delete("delete-refund-config/:id", ":version.RefundConfig/delRefundConfig");
    //退款配置列表
    Route::get("refund-config-list/:type", ":version.RefundConfig/refundConfigList");

});

//总后台代理商管理
Route::group(":version", function () {
    //代理商列表
    Route::post("agent-list",":version.Agent/agentList");
    //层级代理商列表
    Route::post("tree-agent-list", ":version.Agent/treeAgentList");
    //新增代理商
    Route::post("add-agent",":version.Agent/addAgent");
    //编辑代理商
    Route::put("edit-agent/:id",":version.Agent/editAgent");
    //代理商统计
    Route::get("agent-statistics/:id",":version.Agent/agentStatistics");
});

//总后台会员管理
Route::group(":version", function () {
    //会员列表
    Route::post("user-list/:type",":version.User/userList");
    //修改用户信息
    Route::post("edit-user-info/:id", ":version.User/editUserInfo");
    //编辑真假人
    Route::post("edit-user-true2false/:id", ":version.User/editUserTrue2false");
    //修改用户备注
    Route::post("edit-user-remark/:id",":version.User/editUserRemark");
    //修改虚拟访客
    Route::post("edit-user-visitors/:id",":version.User/editVirtualVisitors");
    //店铺审核
    Route::post("check-store/:id",":version.User/checkStore");
    //店铺统计
    Route::get("store-statistics/:id",":version.User/storeStatistics");
    //资金统计
    Route::get("store-amountStatistics/:id",":version.User/amountStatistics");
    //用户冻结
    Route::post("stop-store/:id",":version.User/stopStore");
    //用户解冻
    Route::post("start-store/:id",":version.User/startStore");
});