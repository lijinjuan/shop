<?php

namespace app\api\servlet;

use app\common\model\StoresModel;

/**
 * \app\api\servlet\ShopServlet
 */
class ShopServlet
{
    /**
     * @var \app\common\model\StoresModel
     */
    protected StoresModel $storesModel;

    /**
     * @param \app\common\model\StoresModel $storesModel
     */
    public function __construct(StoresModel $storesModel)
    {
        $this->storesModel = $storesModel;
    }

    /**
     * getStoreByBasicInfo
     * @return mixed
     */
    public function getStoreByBasicInfo()
    {
        return app()->get("userProfile")->store()->field(["id", "storeName", "storeLogo", "storeDesc", "storeRemark", "mobile", "cardID", "frontPhoto", "backPhoto"])->find();
    }

    /**
     * getStoreByBasicStatistics
     * @return string
     */
    public function getStoreByBasicStatistics()
    {
        return app()->get("userProfile")->store()->field(["id", "storeName", "storeLogo", "storeLevel", "creditScore", "todayUV", "totalUV", "increaseUV"])->find();
    }

    /**
     * apply2CreateStore
     * @param array $shopInfo
     * @return mixed
     */
    public function apply2CreateStore(array $shopInfo)
    {
        return app()->get("userProfile")->store()->save($shopInfo);
    }

    public function getGoodsListByMyStore()
    {
        /**
         * @var \app\common\model\UsersModel $userModel
         */
        $userModel = app()->get("userProfile");

        /**
         * @var StoresModel $storeModel
         */
        $storeModel = $userModel->store;
        return $storeModel->goods()->visible(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->where("s_goods.status", 1)->hidden(["pivot"])->paginate();
    }

}