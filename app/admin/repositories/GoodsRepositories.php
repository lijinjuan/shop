<?php

namespace app\admin\repositories;

use think\Request;

/**
 * \app\admin\repositories\GoodsRepositories
 */
class GoodsRepositories extends AbstractRepositories
{

    /**
     * getGoodsListBYPaginate
     * @param $request
     * @return \think\response\Json
     */
    public function getGoodsListByPaginate($request)
    {
        $goodsList = $this->servletFactory->goodsServ()->getGoodsList($request);
        return renderPaginateResponse($goodsList);
    }

    public function createGoods(Request $request)
    {
        return renderResponse();
    }
}