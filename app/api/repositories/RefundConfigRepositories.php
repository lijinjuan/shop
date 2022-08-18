<?php

namespace app\api\repositories;

class RefundConfigRepositories extends AbstractRepositories
{

    /**
     * @param int $id
     * @return \app\common\model\RechargeConfigModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getConfigByID(int $id)
    {
        return $this->servletFactory->refundConfigServ()->getConfigByID();
    }

}