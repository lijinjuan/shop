<?php

namespace app\api\repositories;

class RechargeConfigRepositories extends AbstractRepositories
{
    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRechargeConfig()
    {
        return renderResponse($this->servletFactory->rechargeConfigServ()->getRechargeConfig());
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
        return $this->servletFactory->rechargeConfigServ()->getConfigByID($id);
    }

}