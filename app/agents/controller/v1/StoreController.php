<?php

namespace app\agents\controller\v1;

use app\agents\repositories\StoreRepositories;
use think\Request;

class StoreController
{
    /**
     * @var StoreRepositories
     */
    protected StoreRepositories $storeRepositories;

    /**
     * @param StoreRepositories $storeRepositories
     */
    public function __construct(StoreRepositories $storeRepositories)
    {
        $this->storeRepositories = $storeRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function storeList(Request $request)
    {
        $pageSize = $request->post('pageSize',20);
        $keywords = $request->post('keywords','');
        $type = $request->post('keywords',0);
        return $this->storeRepositories->storeList($pageSize,$keywords,$type);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function storeUserInfo(int $id)
    {
        return $this->storeRepositories->storeInfo($id);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storeRemark(Request $request)
    {
        $remark = $request->post('remark','');
        $id = $request->post('id',0);
        return $this->storeRepositories->setRmarkByID($id,$remark);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storeStop(Request $request)
    {
        $id = $request->post('id');
        return $this->storeRepositories->stopOrStartStore($id,'stop');
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storeStart(Request $request)
    {
        $id = $request->post('id');
        return $this->storeRepositories->stopOrStartStore($id,'start');
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function storeInfo(int $id)
    {
        return $this->storeRepositories->getStoreInfoByID($id);

    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storeCheck(Request $request)
    {
        $id = $request->post('id');
        $checkData = $request->post(['status','remark','reason']);
        return $this->storeRepositories->checkStore($id,$checkData);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     */
    public function storeStatistics(int $id)
    {
        return $this->storeRepositories->storeStatistics($id);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(Request $request)
    {
        $pageSize = $request->post('pageSize',20);
        $keywords = $request->post('keywords');
        return $this->storeRepositories->rechargeList($pageSize,$keywords);

    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function withdrawList(Request $request)
    {
        $pageSize = $request->post('pageSize',20);
        //1->银行卡 2->ERC20 3->TRC20
        $type = $request->post('type');
        $keywords = $request->post('keywords');
        //1->待审核 2->提现成功 3->提现失败
        $status = $request->post('status');
        return $this->storeRepositories->withdrawList($pageSize,compact('type','keywords','status'));

    }

}