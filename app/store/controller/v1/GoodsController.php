<?php

namespace app\store\controller\v1;

use app\store\repositories\GoodsRepositories;
use think\Request;

/**
 * \app\store\controller\v1\GoodsController
 */
class GoodsController
{
    /**
     * @var \app\store\repositories\GoodsRepositories
     */
    protected GoodsRepositories $goodsRepositories;

    /**
     * @param \app\store\repositories\GoodsRepositories $goodsRepositories
     */
    public function __construct(GoodsRepositories $goodsRepositories)
    {
        $this->goodsRepositories = $goodsRepositories;
    }

    /**
     * getPlatformGoodsList
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function getPlatformGoodsList(Request $request)
    {
        $condition = $request->only(["goodsName"]);
        return $this->goodsRepositories->getPlatformGoodsList($condition);
    }

    /**
     * onSaleGoods2Store
     * @param int $goodsID
     * @return \think\response\Json
     */
    public function onSaleGoods2Store(int $goodsID)
    {
        return $this->goodsRepositories->onSaleGoods2Store($goodsID);
    }
}