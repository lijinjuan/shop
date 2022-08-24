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
        $data['userEmail'] = app()->get('userProfile')->email;
        $store = app()->get('userProfile')->store;
        if ($store) {
            $data['storeID'] = $store->id;
            $data['agentID'] = $store->agentID;
            $data['agentAccount'] = $store->agentName;
        };
        $this->servletFactory->rechargeServ()->addRecharge($data);
        return renderResponse();
    }

    /**
     * @param int $status
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(int $status, int $pageSize = 20)
    {
        return renderPaginateResponse($this->servletFactory->rechargeServ()->rechargeList($status, $pageSize));
    }


    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function rechargeDetail(int $id)
    {
        return renderResponse($this->servletFactory->rechargeServ()->rechargeDetail($id));
    }
}