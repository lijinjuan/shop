<?php
// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------
define('REQUEST_URL',env('REQUEST.URL'));
return [
    // 是否强制使用路由
    'url_route_must'        => true,
    // 路由是否完全匹配
    'route_complete_match'  => true,
    // 是否使用控制器后缀
    'controller_suffix'     => 'Controller',
];
