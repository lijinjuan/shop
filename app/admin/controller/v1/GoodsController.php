<?php

namespace app\admin\controller\v1;

use app\admin\repositories\GoodsRepositories;
use think\Request;

/**
 * \app\admin\controller\v1\GoodsController
 */
class GoodsController
{

    /**
     * @var \app\admin\repositories\GoodsRepositories
     */
    protected GoodsRepositories $goodsRepositories;

    /**
     * @param \app\admin\repositories\GoodsRepositories $goodsRepositories
     */
    public function __construct(GoodsRepositories $goodsRepositories)
    {
        $this->goodsRepositories = $goodsRepositories;
    }

    /**
     * getGoodsListByPaginate
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function getGoodsListByPaginate(Request $request)
    {
        return $this->goodsRepositories->getGoodsListByPaginate($request);
    }

    public function createGoods(Request $request)
    {
        return $this->goodsRepositories->createGoods($request);
    }


}