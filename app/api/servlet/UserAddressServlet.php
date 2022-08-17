<?php

namespace app\api\servlet;

use app\common\model\UserAddressModel;

/**
 * \app\api\servlet\UserAddressServlet
 */
class UserAddressServlet
{
    /**
     * @var \app\common\model\UserAddressModel
     */
    protected UserAddressModel $userAddressModel;

    /**
     * @param \app\common\model\UserAddressModel $userAddressModel
     */
    public function __construct(UserAddressModel $userAddressModel)
    {
        $this->userAddressModel = $userAddressModel;
    }

}