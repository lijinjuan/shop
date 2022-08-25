<?php

namespace app\api\controller\v1;

use think\facade\Cache;

/**
 * \app\api\controller\v1\AboutUsController
 */
class AboutUsController
{
    /**
     * @return mixed
     */
    public function getAboutUsContent(): mixed
    {
        $content = Cache::get("relationShip");
        return renderResponse(compact("content"));
    }
}