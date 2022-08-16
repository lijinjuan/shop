<?php

namespace app\api\servlet;

use app\common\model\UsersShoppingCartModel;
use app\lib\exception\ParameterException;

/**
 * \app\api\servlet\UsersShoppingCartServlet
 */
class UsersShoppingCartServlet
{
    /**
     * @var UsersShoppingCartModel
     */
    protected UsersShoppingCartModel $usersShoppingCartModel;

    /**
     * @param UsersShoppingCartModel $usersShoppingCartModel
     */
    public function __construct(UsersShoppingCartModel $usersShoppingCartModel)
    {
        $this->usersShoppingCartModel = $usersShoppingCartModel;
    }

    /**
     * @param int $userID
     * @param array $goodsDatum
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addShoppingCart2GoodsSku(int $userID, array $goodsDatum)
    {
        $shoppingCart = $this->usersShoppingCartModel->where([['userID', '=', $userID], ['goodsID', '=', $goodsDatum['goodsID'], ['skuID', '=', $goodsDatum['skuID']]]])->find();
        $goodsDatum['number'] += ($shoppingCart->goodsNum ?? 0);
        $goodsDatum['number'] = min($goodsDatum['stock'], $goodsDatum['number']);
        $goodsDatum['number'] = max(1, $goodsDatum['number']);
        if ($shoppingCart) {
            $shoppingCart->goodsNum = $goodsDatum['number'];
        } else {
            $shoppingCart = new UsersShoppingCartModel();
            $shoppingCart->goodsNum = $goodsDatum['number'];
            $shoppingCart->goodsID = $goodsDatum['goodsID'];
            $shoppingCart->skuID = $goodsDatum['skuID'];
            $shoppingCart->userID = $userID;
        }
        return $shoppingCart->save();
    }

    /**
     * @param int $id
     * @return UsersShoppingCartModel|array|mixed|\think\Model
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDetailByID(int $id)
    {
        $cart = $this->usersShoppingCartModel->where('id', $id)->find();
        if (!$cart) {
            throw new ParameterException();
        }
        return $cart;
    }

    /**
     * @param int $id
     * @param array $updateData
     * @return UsersShoppingCartModel
     */
    public function editCartByID(int $id, array $updateData)
    {
        return $this->usersShoppingCartModel::update($updateData, ['id' => $id]);
    }

    /**
     * @param int $userID
     * @param array $cartIDs
     * @return bool
     */
    public function removeCartByIDs(int $userID,array $cartIDs)
    {
        return $this->usersShoppingCartModel->where('userID', $userID)->whereIn('id', array_unique($cartIDs))->delete();
    }
}