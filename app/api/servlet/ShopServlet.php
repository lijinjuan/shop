<?php

namespace app\api\servlet;

use app\common\model\StoresModel;
use app\lib\exception\ParameterException;

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
     * getShopInfoByShopID
     * @param int $shopID
     * @param bool $passable
     * @return \app\common\model\StoresModel|array|mixed|\think\Model|null
     */
    public function getShopInfoByShopID(int $shopID, bool $passable = true)
    {
        $shopModel = $this->storesModel->where("id", $shopID)->where("status", 1)->find();

        if (is_null($shopModel) && $passable) {
            throw new ParameterException(["errMessage" => "店铺不存在，或已被删除..."]);
        }

        return $shopModel;
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
        if (isset($shopInfo["inviteCode"])) {
            $parentStores = $this->getShopByInviteCode($shopInfo["inviteCode"]);
        }
        return app()->get("userProfile")->store()->save($shopInfo);
    }

    /**
     * getGoodsListByMyStore
     * @return \think\Paginator
     */
    public function getGoodsListByMyStore()
    {
        /**
         * @var StoresModel $storeModel
         */
        $storeModel = app()->get("userProfile")->store;

        return $storeModel->goods()->where("s_goods.status", 1)
            ->field(["s_goods.id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "s_goods.createdAt"])
            ->hidden(["pivot", "updatedAt", "deletedAt", "brandID", "goodsContent", "goodsStock", "isRank", "isNew", "isItem"])->paginate((int)request()->param("pageSize"));
    }

    /**
     * getGoodsIDsByMyStore
     * @return mixed
     */
    public function getGoodsIDsByMyStore()
    {
        return app()->get("userProfile")?->store?->goods->column("id") ?? [];
    }

    /**
     * getStore2List
     * @return \think\Paginator
     */
    public function getStore2List()
    {
        return $this->storesModel->field(["id", "storeName", "storeLogo", "storeDesc", "createdAt"])->order("createdAt", "desc")->paginate((int)request()->param("pageSize"));
    }

    /**
     * getStore2ListLimit10
     * @return \app\common\model\StoresModel[]|array|\think\Collection
     */
    public function getStore2ListLimit10()
    {
        return $this->storesModel->field(["id", "storeName", "storeLogo", "storeDesc", "createdAt"])->order("createdAt", "desc")->limit(10)->select();
    }

    /**
     * searchShopListByKeywords
     * @param string $keywords
     * @return \think\Paginator
     */
    public function searchShopListByKeywords(string $keywords)
    {
        return $this->storesModel->whereLike("storeName", "%$keywords%")->field(["id", "storeName", "storeLogo", "storeDesc", "createdAt"])->order("createdAt", "desc")->paginate((int)request()->param("pageSize"));
    }

    /**
     * getShopByInviteCode
     * @param string $inviteCode
     * @return \app\common\model\StoresModel|array|mixed|\think\Model|null
     *
     */
    public function getShopByInviteCode(string $inviteCode)
    {
        return $this->storesModel->where("status", 1)->where("inviteCode", $inviteCode)->find();
    }

}