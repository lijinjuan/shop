<?php

namespace app\admin\servlet;

use app\common\model\CommissionModel;
use app\lib\exception\ParameterException;

class CommissionServlet
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
     * @param array $data
     * @return CommissionModel|\think\Model
     */
    public function addCommission(array $data)
    {
        try{
            return $this->commissionModel::create($data);
        }catch (\Throwable $e){
            throw new ParameterException(['errMessage'=>'已存在当前佣金类型不能重复设置...']);
        }
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