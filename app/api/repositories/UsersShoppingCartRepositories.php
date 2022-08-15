<?php

namespace app\api\repositories;

use app\lib\exception\ParameterException;

class UsersShoppingCartRepositories extends AbstractRepositories
{

    public function addShoppingCart(array $goodsDatum)
    {
        $goodsDetail = $this->servletFactory->goodsServ()->getGoodsDetailByGoodsID($goodsDatum['goodsID']);
        $goodsSkuDetail = $goodsDetail->goodsSku()->where('id', $goodsDatum['skuID'])->find();
        if (is_null($goodsSkuDetail)) {
            throw new ParameterException();
        }
        $goodsDatum['stock'] = (int)$goodsSkuDetail->skuStock;
        $this->servletFactory->usersShoppingCartServ()->addShoppingCart2GoodsSku(app()->get("userProfile")->id, $goodsDatum);
        return renderResponse();
    }

}