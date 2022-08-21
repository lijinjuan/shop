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
    Route::post("goods-list", ":version.Goods/getGoodsListByPaginate");
    // 获取商品分类的接口
    Route::post("category-list", ":version.Category/getCategoryListByCategoryID");
    // 获取商品品牌的接口
    Route::post("brands-list", ":version.Brands/getBrandsList");
    // 创建商品的接口
    Route::post("add-goods", ":version.Goods/createGoods");

});

//总后台退款配置
Route::group(":version", function () {
    //新增退款配置
    Route::post("add-refund-config", ":version.RefundConfig/addRefundConfig");
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
    Route::post("agent-list", ":version.Agent/agentList");
    //层级代理商列表
    Route::post("tree-agent-list", ":version.Agent/treeAgentList");
    //新增代理商
    Route::post("add-agent", ":version.Agent/addAgent");
    //编辑代理商
    Route::put("edit-agent/:id", ":version.Agent/editAgent");
    //代理商统计
    Route::get("agent-statistics/:id", ":version.Agent/agentStatistics");
});

//总后台会员管理
Route::group(":version", function () {
    //会员列表
    Route::post("user-list/:type", ":version.User/userList");
    //修改用户信息
    Route::post("edit-user-info/:id", ":version.User/editUserInfo");
    //编辑真假人
    Route::post("edit-user-true2false/:id", ":version.User/editUserTrue2false");
    //修改用户备注
    Route::post("edit-user-remark/:id", ":version.User/editUserRemark");
    //修改虚拟访客
    Route::post("edit-user-visitors/:id", ":version.User/editVirtualVisitors");
    //店铺审核
    Route::post("check-store/:id", ":version.User/checkStore");
    //店铺统计
    Route::get("store-statistics/:id", ":version.User/storeStatistics");
    //资金统计
    Route::get("store-amountStatistics/:id", ":version.User/amountStatistics");
    //用户冻结
    Route::post("stop-store/:id", ":version.User/stopStore");
    //用户解冻
    Route::post("start-store/:id", ":version.User/startStore");
    //会员充值列表
    Route::post("recharge-list", ":version.User/rechargeList");
    //展示会员提交审核信息
    Route::get("get-recharge-info/:id", ":version.User/getCheckRechargeInfo");
    //会员充值审核
    Route::post("check-recharge/:id", ":version.User/checkRecharge");
    //查看审核
    Route::get("show-check-recharge/:id", ":version.User/showCheckRecharge");

    //提现列表
    Route::post("withdrawal-list", ":version.User/withdrawalList");
    //展示会员提现审核信息
    Route::get("get-withdrawal-info/:id", ":version.User/getCheckWithdrawalInfo");
    //会员提现审核
    Route::post("check-withdrawal/:id", ":version.User/checkWithdrawal");


});

//总后台分销管理
Route::group(":version", function () {
    //添加代理商佣金配置
    Route::post("add-commission-config", ":version.Commission/addCommission");
    //代理商佣金配置
    Route::get("get-commission-config/:type", ":version.Commission/getCommissionByType");
});

//总后台信息配置
Route::group(":version", function () {
    //添加banner
    Route::post("add-banner", ":version.ConfigInfo/addBanner");
    //编辑banner
    Route::put("edit-banner/:id", ":version.ConfigInfo/editBanner");
    //删除banner
    Route::delete("delete-agent/:id", ":version.ConfigInfo/deleteBanner");
    //banner列表
    Route::post("banner-list", ":version.ConfigInfo/bannerList");

    //添加充值渠道
    Route::post("add-recharge-config", ":version.ConfigInfo/addRechargeConfig");
    //编辑充值渠道
    Route::put("edit-recharge-config/:id", ":version.ConfigInfo/editRechargeConfig");
    //删除充值渠道
    Route::delete("delete-recharge-config/:id", ":version.ConfigInfo/deleteRechargeConfig");
    //充值渠道列表
    Route::post("recharge-config-list", ":version.ConfigInfo/RechargeConfigList");


});