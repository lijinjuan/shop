<?php

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