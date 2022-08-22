<?php

namespace app\api\servlet;

use app\common\model\AdminsAccountModel;

class AdminAccountServlet
{
    /**
     * @var AdminsAccountModel
     */
    protected AdminsAccountModel $adminsAccountModel;

    /**
     * @param AdminsAccountModel $adminsAccountModel
     */
    public function __construct(AdminsAccountModel $adminsAccountModel)
    {
        $this->adminsAccountModel = $adminsAccountModel;
    }


    /**
     * @param array $data
     * @return AdminsAccountModel|\think\Model
     */
    public function addAdminAccount(array $data)
    {
        return $this->adminsAccountModel::create($data);
    }

}