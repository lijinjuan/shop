<?php

namespace app\admin\servlet;

use app\common\model\AdminsModel;

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


}