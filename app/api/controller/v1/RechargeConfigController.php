<?php

namespace app\api\controller\v1;

use app\api\repositories\RechargeConfigRepositories;

class RechargeConfigController
{
    /**
     * @var RechargeConfigRepositories
     */
    protected RechargeConfigRepositories $rechargeConfigRepositories;

    /**
     * @param RechargeConfigRepositories $rechargeConfigRepositories
     */
    public function __construct(RechargeConfigRepositories $rechargeConfigRepositories)
    {
        $this->rechargeConfigRepositories = $rechargeConfigRepositories;
    }

    /**
     * @return \think\response\Json
     */
    public function getRechargeConfig()
    {
        return $this->rechargeConfigRepositories->getRechargeConfig();
    }

    /**
     * @param int $id
     * @return \app\common\model\RechargeConfigModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getConfigByID(int $id)
    {
        return $this->rechargeConfigRepositories->getConfigByID($id);
    }


}