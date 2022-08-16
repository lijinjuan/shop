<?php

namespace app\api\repositories;

use app\lib\exception\ParameterException;

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
    public function getGoodsDetailByGoodsID(int $goodsID)
    {
        $goodsInfo = $this->servletFactory->GoodsServ()->getGoodsDetailByGoodsID($goodsID);
        return renderResponse($goodsInfo);
    }

    /**
     * getPlatformGoodsList
     * @return \think\response\Json
     */
    public function getPlatformGoodsList()
    {
        $platformGoodsList = $this->servletFactory->GoodsServ()->getPlatformGoodsList();
        $myStoreGoodsID = $this->servletFactory->shopServ()->getGoodsIDsByMyStore();
        $platformGoodsList->each(fn($item) => $item["status"] = in_array($item["id"], $myStoreGoodsID));

        return renderPaginateResponse($platformGoodsList);
    }

    /**
     * getPlatformGoodsListByItem
     * @param string $itemType
     * @return \think\response\Json
     */
    public function getPlatformGoodsListByItem(string $itemType)
    {
        $itemFields = match ($itemType) {
            "rank" => ["isRank" => 1],
            "new" => ["isNew" => 1],
            "item" => ["isItem" => 1],
            default => throw new ParameterException(["errMessage" => "参数异常..."])
        };

        $itemLimit = match ($itemType) {
            "rank" => 3,
            "new" => 6,
            "item" => 6,
            default => throw new ParameterException(["errMessage" => "参数异常..."])
        };

        $goodsList = $this->servletFactory->GoodsServ()->getGoodsListByGoodsItem($itemFields, ["goodsSalesAmount" => "desc"], $itemLimit);
        return renderResponse($goodsList);
    }

    /**
     * getPlatformGoodsListByRecommended
     * @return \think\response\Json
     */
    public function getPlatformGoodsListByRecommended()
    {
        $recommendList = $this->servletFactory->GoodsServ()->getGoodsListByGoodsRecommend(["goodsSalesAmount" => "desc"]);
        return renderPaginateResponse($recommendList);
    }


}