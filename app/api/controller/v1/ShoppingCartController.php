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
     * @param Request $request
     * @return bool
     * @throws \app\lib\exception\ParameterException
     */
    public function addCart(Request $request)
    {
        $cartInfo  = $request->only(['goodsID','skuID','number']);
        return $this->shoppingCartRepositories->addShoppingCart($cartInfo);
    }

    public function editCart()
    {

    }

    public function removeCart()
    {

    }
}