<?php

use think\facade\Route;

// 获取图片的验证码的接口
Route::post(":version/create/captcha", ":version.Entry/createCaptcha");
// 代理商登录的接口
Route::post(":version/launch", ":version.Entry/admin2Launch");