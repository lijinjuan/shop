<?php

use app\admin\middleware\JwtAuthMiddleware;
use think\facade\Route;

// 获取图片的验证码的接口
Route::get(":version/create/captcha", ":version.Entry/createCaptcha");
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
    // 编辑商品的接口
    Route::post("edit-goods/:goodsID", ":version.Goods/editGoodsByGoodsID");
    // 获取商品详情的接口
    Route::post("goods-details/:goodsID", ":version.Goods/getGoodsDetailByGoodsID");
    // 新增商品规格的接口
    Route::post("add-goods-sku/:goodsID", ":version.Goods/addGoodsSkuByGoodsID");
    // 编辑商品规格的接口
    Route::post("edit-goods-sku/:skuID", ":version.Goods/editGoodsSkuBySkuID");
    // 删除商品规格的接口
    Route::post("del-goods-sku/:skuID", ":version.Goods/deleteGoodsSkuBySkuID");

    // 获取商品分类列表的接口
    Route::post("goods-category-list", ":version.Category/getCategoryList");
    // 新建商品分类的接口
    Route::post("goods-category-add", ":version.Category/addGoodsCategory");
    // 获取商品分类的详情的接口
    Route::post("goods-category-details/:categoryID", ":version.Category/getGoodsCategoryDetail");
    // 编辑商品分类的接口
    Route::post("goods-category-edit/:categoryID", ":version.Category/editGoodsCategory");
    // 删除商品分类的接口
    Route::post("goods-category-del/:categoryID", ":version.Category/deleteGoodsCategory");

    // 获取商品品牌列表的接口
    Route::post("brand-category-list", ":version.Brands/getBrandsListByPaginate");
    // 新建品牌的接口
    Route::post("brand-category-add", ":version.Brands/createBrands");
    // 获取品牌的详情的接口
    Route::post("brand-category-details/:brandID", ":version.Brands/getBrandDetailByBrandID");
    // 编辑品牌的接口
    Route::post("brand-category-edit/:brandID", ":version.Brands/editBrandsDetailByBrandID");
    // 删除品牌的接口
    Route::post("brand-category-del/:brandID", ":version.Brands/deleteBrandsDetailByBrandID");
    // 查看订单列表的接口
    Route::post("order-list", ":version.Order/getOrderListByPaginate");
    // 根据订单编号查询店铺信息
    Route::post("order-store-info/:orderNo", ":version.Order/getStoreInfoByOrderNo");
    // 订单的立即发货接口
    Route::post("order-ship", ":version.Order/ship2OrderByOrderNo");
    // 订单退款审核的接口
    Route::post("order-ship", ":version.Order/ship2OrderByOrderNo");
    // 查看退款详情的接口
    Route::post("order-refund-info", ":version.Order/getOrderRefundDetail");
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
    //代理商详情
    Route::get("agent-detail/:id", ":version.Agent/getAgentDetailByID");
    //代理商统计
    Route::get("agent-statistics/:id", ":version.Agent/agentStatistics");
});

//总后台会员管理
Route::group(":version", function () {
    //会员列表
    Route::post("user-list/:type", ":version.User/userList");
    //修改用户信息
    Route::post("edit-user-info/:id", ":version.User/editUserInfo");
    //用户详情
    Route::post("user-info/:id", ":version.User/userDetail");
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
    //获取轮播图类型
    Route::get("get-banner-type", ":version.ConfigInfo/getBannerType");
    //编辑banner
    Route::put("edit-banner/:id", ":version.ConfigInfo/editBanner");
    //banner详情
    Route::get("get-banner-info/:id", ":version.ConfigInfo/getBannerInfo");
    //删除banner
    Route::delete("delete-banner/:id", ":version.ConfigInfo/deleteBanner");
    //banner列表
    Route::post("banner-list", ":version.ConfigInfo/bannerList");

    //添加充值渠道
    Route::post("add-recharge-config", ":version.ConfigInfo/addRechargeConfig");
    //编辑充值渠道
    Route::put("edit-recharge-config/:id", ":version.ConfigInfo/editRechargeConfig");
    //删除充值渠道

    Route::delete("delete-recharge-config/:id", ":version.ConfigInfo/deleteRechargeConfig");
    //单个充值渠道信息
    Route::get("get-recharge-config/:id", ":version.ConfigInfo/getRechargeInfoByID");
    //充值渠道列表
    Route::post("recharge-config-list", ":version.ConfigInfo/RechargeConfigList");



});

//总后台账变管理
Route::group(":version", function () {
    //账变列表
    Route::post("admin-account-list", ":version.AdminAccount/accountList");

});
