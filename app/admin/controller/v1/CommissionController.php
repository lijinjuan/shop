<?php

namespace app\admin\controller\v1;

use app\admin\repositories\CommissionRepositories;
use app\admin\servlet\CommissionServlet;
use think\Request;

class CommissionController
{
    /**
     * @var CommissionRepositories
     */
    protected CommissionRepositories $commissionRepositories;

    /**
     * @param CommissionRepositories $commissionRepositories
     */
    public function __construct(CommissionRepositories $commissionRepositories)
    {
        $this->commissionRepositories = $commissionRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function addCommission(Request $request)
    {
//        {"firstLevel":5},
//        {"secondLevel":6},
//        {"thirdLevel":7},
//        {"fourthLevel":8}
        //1->推广佣金 2->商品佣金
        $type = $request->post('type', 1);
        $content = $request->post('content');
        return $this->commissionRepositories->addCommission($content, $type);
    }

    /**
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCommissionByType(int $type)
    {
        return $this->commissionRepositories->getCommission($type);
    }
}