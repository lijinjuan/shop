<?php

namespace app\agents\repositories;

class OrderRepositories extends AbstractRepositories
{
    /**
     * @param int $pageSize
     * @param array $conditons
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function orderList(int $pageSize, array $conditions)
    {
        return renderPaginateResponse($this->servletFactory->orderServ()->orderList($pageSize, $conditions));
    }

    /**
     * @param array $orderID
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderShip(array $orderID)
    {
        return renderResponse($this->servletFactory->orderServ()->orderShip($orderID));
    }
}