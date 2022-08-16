<?php

namespace app\api\repositories;

use app\lib\exception\ParameterException;

class OrderRepositories extends AbstractRepositories
{
    public function placeOrder(int $addressID, array $goodsInfo)
    {
        //{'goodsID':1,'skuID':2,'mumber':1},{'goodsID':1,'skuID':2,'mumber':1},
        $isValid = $this->servletFactory->userServ()->isValidAddress($addressID);
        if (!$isValid) {
            throw  new ParameterException();
        }
        //检测商品
        $this->checkGoods($goodsInfo);
        //检测库存
        $this->checkStock($goodsInfo);


    }

    /**
     * @param array $goods
     * @return array|void
     */
    protected function checkGoods(array $goods)
    {
        $input_goodsIDs = array_column($goods, 'goodsID');
        $goodsInfo = $this->servletFactory->goodsServ()->getGoodsListByGoodsID(array_unique($input_goodsIDs), ['id', 'status', 'goodsName']);
        if (is_null($goodsInfo)) {
            throw new ParametersException(['errMessage' => '商品不存在...']);
        }
        $goodsInfo = $goodsInfo->toArray();
        $newGoodsInfo = [];
        if ($goodsInfo) {
            foreach ($goodsInfo as $item) {
                $newGoodsInfo[$item['id']] = $item;
            }
            foreach ($input_goodsIDs as $item) {
                if ($goodsInfo) {
                    if (!isset($newGoodsInfo[$item])) {
                        throw new ParametersException(['errMessage' => '商品' . $item . '不存在...']);
                    }
                    if ($newGoodsInfo[$item]['status'] != 1) {
                        throw new ParametersException(['errMessage' => '商品' . $item['id'] . '已下架...']);
                    }
                }

            }
        }
        return $goodsInfo;
    }

    /**
     * 检测库存
     * @return bool
     * @throws ParametersException
     */
    protected function checkStock(array $goods)
    {
        $input_skuIDs = array_column($goods, 'number', 'skuID');
        // $skuID=>$number
        $stocksInfo = $this->servletFactory->goodsSkuServ()->getStockByID(array_keys($input_skuIDs));
        if (is_null($stocksInfo)) {
            throw new ParametersException(['errMessage' => '商品规格不存在...']);
        }
        foreach ($input_skuIDs as $key => $item) {
            if (!$stocksInfo->groupBy('id')->has(key: $key)) {
                throw new ParametersException(errMessage: '商品规格' . $item . '不存在...');
            }
        }
        foreach ($stocksInfo as $item) {
            if ($item->stock < 1 || $item->stock < $input_skuIDs[$item->id]) {
                throw new ParametersException(errMessage: '商品' . $item->goodsID . '库存不足...');
            }
        }
        return true;
    }

}