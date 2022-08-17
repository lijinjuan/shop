<?php


// 登录
use think\facade\Route;


Route::post(":version/launch", ":version.Entry/userLaunch");