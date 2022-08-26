<?php

namespace app\store\controller\v1;

use app\admin\servlet\contract\ServletFactoryInterface;
use app\store\repositories\StoreRepositories;
use think\Request;

/**
 * \app\store\controller\v1\StoreController
 */
class StoreController
{

    /**
     * @var \app\store\repositories\StoreRepositories
     */
    protected StoreRepositories $storeRepositories;

    /**
     * @param \app\store\repositories\StoreRepositories $storeRepositories
     */
    public function __construct(StoreRepositories $storeRepositories)
    {
        $this->storeRepositories = $storeRepositories;
    }

    /**
     * getStoreBaseInfo
     * @return \think\response\Json
     */
    public function getStoreBaseInfo()
    {
        return $this->storeRepositories->getStoreBaseInfo();
    }

    /**
     * editStoreBaseInfo
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function editStoreBaseInfo(Request $request)
    {
        $storeProfile = $request->only(["storeName", "storeLogo", "mobile", "cardID", "storeRemark"]);
        return $this->storeRepositories->saveStoreBaseInfo(array_filter($storeProfile));
    }

    /**
     * getStoreList
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function getStoreList()
    {
        return $this->storeRepositories->getStoreList();
    }

    /**
     * getStoreTreeList
     * @return \think\response\Json
     */
    public function getStoreTreeList()
    {
        return $this->storeRepositories->getStoreTreeList();
    }

    /**
     * getStoreAccountList
     * @param \think\Request $request
     */
    public function getStoreAccountList(Request $request)
    {
        return $this->storeRepositories->getStoreAccountList($request);
    }

    /**
     * getStoreGoodsList
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function getStoreGoodsList(Request $request)
    {
        $condition = $request->only(["goodsName"]);
        return $this->storeRepositories->getStoreGoodsList($condition);
    }

    /**
     * takeDownStoreGoods
     * @param int $goodsID
     * @return \think\response\Json
     */
    public function takeDownStoreGoods(int $goodsID)
    {
        return $this->storeRepositories->takeDownStoreGoodsByGoodsID($goodsID);
    }

    /**
     * getStoreStatistics
     * @return mixed
     */
    public function getStoreStatistics(ServletFactoryInterface $adminServletFactory)
    {
        return $this->storeRepositories->getStoreStatistics($adminServletFactory);
    }
}