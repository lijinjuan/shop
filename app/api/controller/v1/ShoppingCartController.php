<?php

namespace app\api\controller\v1;

use app\api\repositories\UsersShoppingCartRepositories;
use think\Request;

class ShoppingCartController
{
    /**
     * @var UsersShoppingCartRepositories
     */
    protected UsersShoppingCartRepositories $shoppingCartRepositories;

    /**
     * @param UsersShoppingCartRepositories $shoppingCartRepositories
     */
    public function __construct(UsersShoppingCartRepositories $shoppingCartRepositories)
    {
        $this->shoppingCartRepositories = $shoppingCartRepositories;
    }

    /**
     * 添加购物车
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addCart(Request $request)
    {
        $cartInfo = $request->only(['goodsID', 'skuID', 'number']);
        return $this->shoppingCartRepositories->addShoppingCart($cartInfo);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editCart(int $id, Request $request)
    {
        $updateData = $request->only(['goodsID', 'skuID', 'goodsNum']);
        return $this->shoppingCartRepositories->editCartByID($id, $updateData);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function removeCart(Request $request)
    {
        $cartIDs = $request->put('cartIDs');
        return $this->shoppingCartRepositories->removeCartByIDs($cartIDs);
    }

    /**
     * 购物车数量
     * @return \think\response\Json
     */
    public function countCart()
    {
        return $this->shoppingCartRepositories->countCart();
    }

    /**
     * 购物车列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCartList()
    {
        return $this->shoppingCartRepositories->getCartList();
    }
}