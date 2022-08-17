<?php

namespace app\api\servlet;

use app\common\model\CommissionModel;

class CommissionConfigServlet
{
    /**
     * @var CommissionModel
     */
     protected CommissionModel $commissionModel;

    /**
     * @param CommissionModel $commissionModel
     */
    public function __construct(CommissionModel $commissionModel)
    {
        $this->commissionModel = $commissionModel;
    }

    /**
     * @param int $type
     * @return CommissionModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCommissionByType(int $type)
    {
        return $this->commissionModel->where('type',$type)->find();
    }


}