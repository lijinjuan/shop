<?php

namespace app\api\servlet;

use app\common\model\UsersShoppingCartModel;

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
     * @return bool
     */
    public function addShoppingCart2GoodsSku(int $userID, array $goodsDatum)
    {
        $shoppingCart = $this->usersShoppingCartModel->where([['userID', '=', $userID], ['goodsID', '=', $goodsDatum['goodsID'], ['skuID', '=', $goodsDatum['skuID']]]])->find();
        $goodsDatum['number'] += ($shoppingCart->number ?? 0);
        $goodsDatum['number'] = min($goodsDatum['stock'], $goodsDatum['number']);
        $goodsDatum['number'] = max(1, $goodsDatum['number']);
        if ($shoppingCart){
            $shoppingCart->number = $goodsDatum['number'];
        }else{
            $shoppingCart = new UsersShoppingCartModel();
            $shoppingCart->goodsNum = $goodsDatum['number'];
            $shoppingCart->goodsID = $goodsDatum['goodsID'];
            $shoppingCart->skuID = $goodsDatum['skuID'];
            $shoppingCart->userID = $userID;
        }
        return $shoppingCart->save();
    }
}