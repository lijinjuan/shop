<?php

namespace app\common\model;

use think\Model;

class UsersShoppingCartModel extends  Model
{
    /**
     * @var string
     */
    protected $table = 's_users_shopping_cart';

    /**
     * @var string
     */
    protected $createTime = "createdAt";

    /**
     * @var string
     */
    protected $autoWriteTimestamp = "timestamp";


}