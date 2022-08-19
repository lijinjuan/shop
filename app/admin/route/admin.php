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
    Route::post("category-list/", ":version.Goods/createGoods");

});