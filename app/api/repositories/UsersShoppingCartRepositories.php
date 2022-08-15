<?php

namespace app\api\repositories;

use app\common\model\UsersModel;
use app\lib\exception\ParameterException;

class UsersShoppingCartRepositories extends AbstractRepositories
{

    /**
     * @param array $goodsDatum
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addShoppingCart(array $goodsDatum)
    {
        $goodsDetail = $this->servletFactory->goodsServ()->getGoodsDetailByGoodsID($goodsDatum['goodsID']);
        $goodsSkuDetail = $goodsDetail->goodsSku()->where('id', $goodsDatum['skuID'])->find();
        if (is_null($goodsSkuDetail)) {
            throw new ParameterException();
        }
        $goodsDatum['stock'] = (int)$goodsSkuDetail->skuStock;
        $this->servletFactory->usersShoppingCartServ()->addShoppingCart2GoodsSku(app()->get("userProfile")->id, $goodsDatum);
        return renderResponse();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCartList()
    {
        /**
         * @var UsersModel $userProfile
         */
        $userProfile = app()->get("userProfile");
        $shoppingCart = $userProfile->shoppingCart()->order('createdAt', 'desc')->with([
            'goods' => fn($goods) => $goods->field(['id', 'goodsName', 'goodsCover', 'status']),
            'sku' => fn($goodsSku) => $goodsSku->field(['id', 'skuName', 'sku', 'skuImg', 'skuDiscountPrice', 'skuStock']),
        ])->field(['id', 'goodsID', 'skuID', 'goodsNum'])->select();
        return renderResponse($shoppingCart);

    }

    /**
     * @param int $id
     * @param array $updateData
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editCartByID(int $id, array $updateData)
    {
        $cartInfo = $this->servletFactory->usersShoppingCartServ()->getDetailByID($id);
        if ($cartInfo) {
            if ($cartInfo->userID != app()->get("userProfile")->id) {
                throw new ParameterException(["errMessage" => '无权更新购物车']);
            }
            $this->servletFactory->usersShoppingCartServ()->editCartByID($id, $updateData);
        }
        return renderResponse();
    }

    /**
     * @param array $cartIDs
     * @return mixed
     */
    public function removeCartByIDs(array $cartIDs)
    {
        $this->servletFactory->usersShoppingCartServ()->removeCartByIDs(app()->get("userProfile")->id, $cartIDs);
        return  renderResponse();
    }

    /**
     * @return \think\response\Json
     */
    public function countCart()
    {
        $count = $this->servletFactory->userServ()->getUserShoppingCount();
        return renderResponse($count);
    }

}