<?php

namespace app\store\controller\v1;

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
    public function getStoreList(Request $request)
    {
        return $this->storeRepositories->getStoreList();
    }

    public function getStoreTreeList()
    {
        return $this->storeRepositories->getStoreTreeList();
    }
}