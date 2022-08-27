<?php

namespace app\store\repositories;

use think\Request;

/**
 * \app\store\repositories\GoodsRepositories
 */
class GoodsRepositories extends AbstractRepositories
{

    /**
     * getPlatformGoodsList
     * @param array $condition
     * @return \think\response\Json
     */
    public function getPlatformGoodsList(array $condition)
    {
        $platformGoodsList = $this->servletFactory->goodsServ()->getGoodsList($condition);
        $storeGoodsList = app()->get("storeProfile")?->goods->column("id") ?? [];
        $commissionRate = $this->servletFactory->commissionServ()->getCommissionByType(2);
        $rateArr = json_decode($commissionRate, true);
        $rate = $rateArr["goodsCommission"] ?? 0;
        $platformGoodsList = $platformGoodsList->each(function ($item) use ($rate, $storeGoodsList) {
            $item->commission = bcmul($item->goodsDiscountPrice, $rate / 100, 2);
            $item->status = (int)in_array($item["id"], $storeGoodsList);
            return $item;
        });

        return renderPaginateResponse($platformGoodsList);
    }

    /**
     * onSaleGoods2Store
     * @param int $goodsID
     * @return \think\response\Json
     */
    public function onSaleGoods2Store(int $goodsID)
    {
        /**
         * @var \app\common\model\StoresModel $storeModel
         */
        $storeModel = app()->get("storeProfile");
        $storeModel->goods()->sync([$goodsID => ["agentID" => $storeModel->agentID]], false);
        return renderResponse();
    }
}