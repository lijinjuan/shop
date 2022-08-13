<?php

namespace app\api\repositories;

/**
 * \app\api\repositories\ShopRepositories
 */
class ShopRepositories extends AbstractRepositories
{

    /**
     * getStoreByBasicInfo
     * @return \think\response\Json
     */
    public function getStoreByBasicInfo()
    {
        $storeInfo = $this->servletFactory->shopServ()->getStoreByBasicInfo();
        return renderResponse($storeInfo);
    }

    /**
     * getBasicStatisticsByStore
     * @return \think\response\Json
     */
    public function getBasicStatisticsByStore()
    {
        $storeInfo = $this->servletFactory->shopServ()->getStoreByBasicStatistics();
        return renderResponse($storeInfo);
    }

    /**
     * apply2OpenStore
     * @param array $shopInfo
     * @return \think\response\Json
     */
    public function apply2OpenStore(array $shopInfo)
    {
        $this->servletFactory->shopServ()->apply2CreateStore($shopInfo);
        return renderResponse();
    }

}