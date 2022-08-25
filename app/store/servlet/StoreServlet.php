<?php

namespace app\store\servlet;

use app\common\model\StoresModel;
use app\lib\exception\ParameterException;
use think\model\Collection;

/**
 * \app\store\servlet\StoreServlet
 */
class StoreServlet
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
     * getStoreProfileByFields
     * @param array $whereFields
     * @param bool $passable
     * @return \app\common\model\StoresModel|array|mixed|\think\Model|null
     */
    public function getStoreProfileByFields(array $whereFields, bool $passable = true)
    {
        $storeProfile = $this->storesModel->where($whereFields)->where("status", 1)->find();

        if (is_null($storeProfile) && $passable) {
            throw new ParameterException(["errMessage" => "店铺不存在或数据异常..."]);
        }

        return $storeProfile;
    }

    /**
     * getStoreList
     * @param int $storeID
     * @return \think\Paginator
     */
    public function getStoreList(int $storeID)
    {
        $userEmail = request()->param("email", "");
        $storeList = $this->storesModel->whereLike("parentStoreID", "%,$storeID,%");

        if ($userEmail != "")
            $storeList->whereLike("userEmail", "%" . $userEmail . "%");

        return $storeList->field(["id", "storeName", "mobile", "storeDesc", "status", "storeRemark", "userID", "userEmail", "parentStoreID", "createdAt"])
            ->where("status", 1)->append(["parentID"])->paginate((int)request()->param("pageSize"), 20);
    }

    /**
     * getStoreTreeList
     * @param int $storeID
     * @return array
     */
    public function getStoreTreeList(int $storeID)
    {
        return $this->storesModel->whereLike("parentStoreID", "%,$storeID,%")
            ->field(["id", "storeName", "mobile", "storeDesc", "status", "storeRemark", "userID", "parentStoreID", "createdAt"])
            ->with(["user" => function ($query) {
                $query->field(["id", "userName"]);
            }])
            ->append(["parentID"])->select()->toArray();
    }

}