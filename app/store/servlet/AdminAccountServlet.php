<?php

namespace app\store\servlet;

use app\common\model\AdminsAccountModel;

/**
 * \app\admin\servlet\AdminAccountServlet
 */
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