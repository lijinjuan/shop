<?php

namespace app\api\repositories;

class RechargeRepositories extends AbstractRepositories
{
    /**
     * @param array $data
     * @return \think\response\Json
     */
    public function addRecharge(array $data)
    {
        $data['orderNo'] = makeOrderNo();
        $data['userID'] = app()->get('userProfile')->id;
        $store = app()->get('userProfile')->store;
        if ($store->id) {
            $data['storeID'] = $store->id;
            $data['agentID'] = $store->agentID;
            $data['agentAccount'] = NULL;
        };
        $this->servletFactory->rechargeServ()->addRecharge($data);
        return renderResponse();
    }
}