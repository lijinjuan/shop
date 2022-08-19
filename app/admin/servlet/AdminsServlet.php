<?php

namespace app\admin\servlet;

use app\common\model\AdminsModel;
use app\lib\exception\ParameterException;

/**
 * \app\admin\servlet\AdminsServlet
 */
class AdminsServlet
{
    /**
     * @var \app\common\model\AdminsModel
     */
    protected AdminsModel $adminsModel;

    /**
     * @param \app\common\model\AdminsModel $adminsModel
     */
    public function __construct(AdminsModel $adminsModel)
    {
        $this->adminsModel = $adminsModel;
    }

    /**
     * getAdminProfileByFields
     * @param array $whereFields
     * @param bool $passable
     * @return AdminsModel
     */
    public function getAdminProfileByFields(array $whereFields, bool $passable = true)
    {
        $adminProfile = $this->adminsModel->where($whereFields)->find();

        if (is_null($adminProfile) && $passable) {
            throw new ParameterException(["errMessage" => "管理员不存在或数据异常..."]);
        }

        return $adminProfile;
    }


}