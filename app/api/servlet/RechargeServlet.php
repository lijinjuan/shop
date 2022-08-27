<?php

namespace app\api\servlet;

use app\common\model\RechargeModel;
use app\lib\exception\ParameterException;

class RechargeServlet
{
    /**
     * @var RechargeModel
     */
    protected RechargeModel $rechargeModel;

    /**
     * @param RechargeModel $rechargeModel
     */
    public function __construct(RechargeModel $rechargeModel)
    {
        $this->rechargeModel = $rechargeModel;
    }

    /**
     * @param array $data
     * @return RechargeModel|\think\Model
     */
    public function addRecharge(array $data)
    {
        try {
            return $this->rechargeModel::create($data);
        } catch (\Throwable $e) {
            throw new ParameterException(['errMessage' => '提交充值申请失败...']);
        }
    }

    /**
     * @param int $status
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(int $status, int $pageSize)
    {
        return $this->rechargeModel->where('userID', app()->get('userProfile')->id)->where('status', $status)->field(['id', 'rechargeType', 'rechargeMoney', 'createdAt'])->append(['rechargeName'])->order('createdAt', 'desc')->paginate($pageSize);
    }

    /**
     * @param int $id
     * @return RechargeModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function rechargeDetail(int $id)
    {
        return $this->rechargeModel->where('id', $id)->where('userID',app()->get('userProfile')->id)->field(['id', 'orderNo', 'rechargeMoney', 'createdAt', 'status', 'rechargeVoucher'])->append(['rechargeName', 'orderStatus'])->find();
    }




}