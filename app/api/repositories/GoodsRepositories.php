<?php

namespace app\api\repositories;

/**
 * \app\api\repositories\GoodsRepositories
 */
class GoodsRepositories extends AbstractRepositories
{

    public function getPlatformGoodsList()
    {
        $goodsList = $this->servletFactory->goodsServ()->getPlatformGoodsList();
    }
}