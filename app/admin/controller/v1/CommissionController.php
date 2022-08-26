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
        //1->推广佣金 2->商品佣金
        $type = $request->post('type', 1);
        $goodsCommission = $request->post('goodsCommission');
        $firstLevel = $request->post('firstLevel');
        $thirdLevel = $request->post('thirdLevel');
        $secondLevel = $request->post('secondLevel');
        $fourthLevel = $request->post('fourthLevel');
        $content = match ($type) {
            2 => ['goodsCommission' => $goodsCommission],
            1 => ['firstLevel' => $firstLevel, 'secondLevel' => $secondLevel, 'thirdLevel' => $thirdLevel, 'fourthLevel' => $fourthLevel]
        };
        return $this->commissionRepositories->addCommission($content, (int)$type);
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