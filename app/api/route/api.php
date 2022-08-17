<?php

use app\api\middleware\JwtAuthMiddleware;
use think\facade\Route;

// 登录
Route::post(":version/launch", ":version.Entry/userLaunch")->json()->allowCrossDomain();
// 注册
Route::post(":version/register", ":version.Entry/registerNewUser")->json()->allowCrossDomain();

// 用户收货地址的管理
Route::group(":version", function () {
    // 用户修改密码
    Route::post("alter-password", ":version.Entry/alterUserPassword");
    // 获取用户收货地址的接口
    Route::get("ship-address", ":version.UserAddress/getUserAddressListByToken");
    // 获取用户的单个收货地址的接口
    Route::get("get-ship-address/:addressID", ":version.UserAddress/getUserAddressByAddressID");
    // 新增用户收货地址的接口
    Route::post("add-ship-address", ":version.UserAddress/createUserAddress");
    // 编辑用户收货地址的接口
    Route::put("edit-ship-address/:addressID", ":version.UserAddress/editUserAddress");
    // 删除用户收货地址的接口
    Route::delete("del-ship-address/:addressID", ":version.UserAddress/deleteUserAddress");

})->middleware(JwtAuthMiddleware::class)->json()->allowCrossDomain();

// 客户端首页接口
Route::group(":version", function () {
    // 获取首页轮播图的接口
    Route::get("banner/:bannerID", ":version.Banner/getBannerByID");
    // 获取首页品牌列表的接口
    Route::get("brands-list", ":version.Brand/getBrandsList");
    // 获取首页商铺列表的接口
    Route::get("home-shop", ":version.Shop/getStoreList2Limit10");
    // 获取全部分页商铺列表的接口
    Route::get("shop/:shopID/goods-list", ":version.Shop/getGoodsListByShopID");
    // 获取全部分页商铺列表的接口
    Route::get("shop-list", ":version.Shop/getStoreList");
    // 根据标签查询商品列表的接口
    Route::get(":itemType/goods-list", ":version.Goods/getPlatformGoodsListByItem");
    // 获取精品推荐列表的接口
    Route::get("recommend-goods-list", ":version.Goods/getPlatformGoodsListByRecommended");
    // 获取所有的分类的接口
    Route::get("category-list", ":version.Category/getCategoriesByAssert");
    // 获取所有的一级分类的接口
    Route::get("category-parent-list", ":version.Category/getParentCategories");
    // 根据一级分类获取商品的列表的接口
    Route::get(":categoryID/home-goods-list", ":version.Goods/getGoodsListByHomeCategoryID");
    // 根据二级分类获取商品的列表的接口
    Route::get("category/:categoryID/goods-list", ":version.Goods/getGoodsListByCategoryID");
    // 根据关键字搜索商品列表的接口
    Route::post("search/goods-list", ":version.Goods/getGoodsListByKeywords");
    // 根据关键字搜索店铺列表的接口
    Route::post("search/shop-list", ":version.Shop/getShopListByKeywords");
    // 获取商品详情的接口
    Route::post("goods-details/:goodsID", ":version.Goods/getGoodsDetailsByGoodsID");
    // 获取优品推荐的商品列表的接口
    Route::get("excellent-goods-list", ":version.Goods/getGoodsListByExcellent");

})->json()->allowCrossDomain();


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
    // 获取所有的商品列表的接口
    Route::get("goods", ":version.Goods/getPlatformGoods");

})->middleware(JwtAuthMiddleware::class)->json()->allowCrossDomain();

//客户端购物车接口
Route::group(":version", function () {
    // 添加购物车接口
    Route::post("add-shopping-cart", ":version.ShoppingCart/addCart");
    // 编辑购物车接口
    Route::put("edit-shopping-cart/:id", ":version.ShoppingCart/editCart");
    // 删除购物车接口
    Route::delete("remove-shopping-cart", ":version.ShoppingCart/removeCart");
    //购物车列表接口
    Route::get("list-shopping-cart", ":version.ShoppingCart/getCartList");
    //获取我的购物车的数量
    Route::get("count-shopping-cart", ":version.ShoppingCart/countCart");

})->middleware(JwtAuthMiddleware::class)->json()->allowCrossDomain();

//客户端订单相关模块
Route::group(":version", function () {
    // 下订单接口
    Route::post("place-order", ":version.Order/placeOrder");
    // 购买接口
    Route::post("payment-order", ":version.Order/payment");
    //订单列表
    Route::get("order-list/:type", ":version.Order/orderList");
    //订单详情
    Route::get("order-detail/:orderNo", ":version.Order/orderDetail");
    //订单统计数据
    Route::get("order-count", ":version.Order/orderCount");

})->middleware(JwtAuthMiddleware::class)->json()->allowCrossDomain();

Route::group(":version", function () {
    // test token
    Route::post("test/token", ":version.Entry/testToken")->middleware(JwtAuthMiddleware::class);

    // banner api
})->json();




