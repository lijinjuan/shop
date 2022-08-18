<?php

namespace app\api\servlet;

use app\common\model\RechargeConfigModel;

class RechargeConfigServlet
{
    /**
     * @var RechargeConfigModel
     */
    protected RechargeConfigModel $rechargeConfigModel;

    /**
     * @param RechargeConfigModel $rechargeConfigModel
     */
    public function __construct(RechargeConfigModel $rechargeConfigModel)
    {
        $this->rechargeConfigModel = $rechargeConfigModel;
    }

    /**
     * @return RechargeConfigModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRechargeConfig()
    {
        return $this->rechargeConfigModel->field(['id','rechargeName','QRCode','walletAddress'])->select();
    }

    /**
     * @param int $id
     * @return RechargeConfigModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getConfigByID(int $id)
    {
        return $this->rechargeConfigModel->where('id',$id)->field(['id','rechargeName','QRCode','walletAddress'])->find();
    }


}