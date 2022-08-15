<?php

namespace app\api\controller\v1;

use app\api\repositories\ShopRepositories;
use think\Request;

/**
 * 店铺的控制器
 * \app\api\controller\v1\ShopController
 */
class ShopController
{
    /**
     * @var \app\api\repositories\ShopRepositories
     */
    protected ShopRepositories $shopRepositories;

    /**
     * @param \app\api\repositories\ShopRepositories $shopRepositories
     */
    public function __construct(ShopRepositories $shopRepositories)
    {
        $this->shopRepositories = $shopRepositories;
    }

    /**
     * getStoreByBasicInfo
     * @return \think\response\Json
     */
    public function getStoreByBasicInfo()
    {
        return $this->shopRepositories->getStoreByBasicInfo();
    }

    /**
     * getStoreByBasicStatistics
     * @return \think\response\Json
     */
    public function getStoreByBasicStatistics()
    {
        return $this->shopRepositories->getBasicStatisticsByStore();
    }

    /**
     * apply2OpenStore
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function apply2OpenStore(Request $request)
    {
        $shopInfo = $request->only(["storeName", "storeLogo", "storeDesc", "storeRemark", "mobile", "cardID", "frontPhoto", "backPhoto"]);
        return $this->shopRepositories->apply2OpenStore($shopInfo);
    }

    public function getGoodsListByMyStore()
    {
        return $this->shopRepositories->getGoodsListByMyStore();
    }

}