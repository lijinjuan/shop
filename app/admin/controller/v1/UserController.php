<?php

namespace app\admin\controller\v1;

use app\admin\repositories\UsersRepositories;
use think\Request;

class UserController
{
    /**
     * @var UsersRepositories
     */
    protected UsersRepositories $usersRepositories;

    /**
     * @param UsersRepositories $usersRepositories
     */
    public function __construct(UsersRepositories $usersRepositories)
    {
        $this->usersRepositories = $usersRepositories;
    }


    /**
     * @param int $type
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DbException
     */
    public function userList(int $type, Request $request)
    {
        $pageSize = $request->post('pageSize');
        return $this->usersRepositories->userList($type, $pageSize);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editUserInfo(int $id, Request $request)
    {
        $data = $request->only(['loginPassword', 'payPassword', 'storeLevel', 'isRealPerson', 'creditScore', 'userName', 'remark', 'sort']);
        return $this->usersRepositories->editUserInfo($id, $data);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editUserTrue2false(int $id, Request $request)
    {
        $isRealPeople = $request->post('isRealPeople');
        return $this->usersRepositories->modifyUserInfo($id, ['isRealPeople' => $isRealPeople]);

    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editUserRemark(int $id, Request $request)
    {
        $remark = $request->post('remark');
        return $this->usersRepositories->modifyUserInfo($id, ['remark' => $remark]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editVirtualVisitors(int $id, Request $request)
    {
        $visitors = $request->post('visitors');
        return $this->usersRepositories->modifyUserInfo($id, ['increaseUV' => $visitors]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function checkStore(int $id, Request $request)
    {
        $checkData = $request->post(['status', 'remark', 'reason']);
        return $this->usersRepositories->checkStore($id, $checkData);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     */
    public function storeStatistics(int $id)
    {
        return $this->usersRepositories->storeStatistics($id);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function amountStatistics(int $id)
    {
        return $this->usersRepositories->amountStatistics($id);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function stopStore(int $id)
    {
        return $this->usersRepositories->stopStore($id);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function startStore(int $id)
    {
        return $this->usersRepositories->startStore($id);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function rechargeList(Request $request)
    {
        $keywords = $request->post('keywords', '');
        $pageSize = $request->post('pageSize', 20);
        return $this->usersRepositories->rechargeList($keywords, $pageSize);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCheckRechargeInfo(int $id)
    {
        return $this->usersRepositories->getRechargeInfoByID($id);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function checkRecharge(int $id, Request $request)
    {
        $status = $request->post('status');
        $refuseReason = $request->post('reason', '');
        return $this->usersRepositories->checkRecharge($id, compact('status', 'refuseReason'));

    }

    /**
     * @param int $id
     * @return \app\common\model\RechargeModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function showCheckRecharge(int $id)
    {
        return $this->usersRepositories->getRechargeInfoByID($id);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function withdrawalList(Request $request)
    {
        $pageSize = $request->post('pageSize',20);
        return $this->usersRepositories->withdrawalList($pageSize);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCheckWithdrawalInfo(int $id)
    {
        return $this->usersRepositories->getCheckWithdrawalInfo($id);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function checkWithdrawal(int $id,Request $request)
    {
        $status = $request->post('status');
        $refuseReason = $request->post('reason');
        return $this->usersRepositories->checkWithdrawalByID($id,compact('status','refuseReason'));
    }


}