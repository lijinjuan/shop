<?php

namespace app\api\repositories;

/**
 * \app\api\repositories\GoodsRepositories
 */
class GoodsRepositories extends AbstractRepositories
{

    /**
     * @param int $goodsID
     * @return \app\common\model\GoodsModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function  getGoodsDetailByGoodsID(int $goodsID)
    {
        $goodsInfo = $this->servletFactory->GoodsServ()->getGoodsDetailByGoodsID($goodsID);
        return renderResponse($goodsInfo);
    }


}