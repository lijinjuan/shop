<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;

/**
 * \app\admin\repositories\UsersRepositories
 */
class UsersRepositories extends AbstractRepositories
{

    /**
     * @param int $type
     * @param $pageSize
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DbException
     */
    public function userList(int $type, $pageSize)
    {
        if (!in_array($type, [1, 2])) {
            throw new ParameterException(['errMessage' => '参数错误...']);
        }
        return renderPaginateResponse($this->servletFactory->userServ()->userList($type, $pageSize));
    }

    /**
     * @param int $id
     * @param array $data
     * @return \think\response\Json|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editUserInfo(int $id, array $data)
    {
        $userModel = $this->servletFactory->userServ()->getUserInfoByID($id);
        if ($userModel) {
            if ($data['userName']) {
                $userModel->userName = $data['userName'];
            }
            if ($data['loginPassword']) {
                $userModel->password = $data['loginPassword'];
            }
            if ($data['payPassword']) {
                $userModel->payPassword = $data['payPassword'];
            }
            if ($data['isRealPerson']) {
                $userModel->isRealPerson = $data['isRealPerson'];
            }
            if ($data['remark']) {
                $userModel->remark = $data['remark'];
            }
            if ($userModel->isStore == 1) {
                $storeData = [
                    'storeLevel' => $data['storeLevel'],
                    'storeRemark' => $data['remark'],
                    'isRealPeople' => $data['isRealPeople'],
                    'creditScore' => $data['creditScore'],
                    'sort' => $data['sort'],
                ];
                $userModel->store()->update($storeData);
            }
            $userModel->save();
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '用户不存在...']);
    }


    /**
     * @param int $id
     * @param array $data
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function modifyUserInfo(int $id, array $data)
    {
        $userModel = $this->servletFactory->userServ()->getUserInfoByID($id);
        if ($userModel) {
            if (!isset($data['increaseUV'])) {
                $userModel::update($data, ['id' => $userModel->id]);
            }
            if ($userModel->isStore == 1) {
                if (!empty($data['remark'])) {
                    $data['storeRemark'] = $data['remark'];
                    unset($data['remark']);
                }
                $userModel->store()->update($data);
            }
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '用户不存在...']);
    }

    /**
     * @param int $id
     * @param array $checkData
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkStore(int $id, array $checkData)
    {
        //0->待审核 1->成功 2->失败
        $store = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if (!$store || $store->status != 0) {
            throw new ParameterException(['errMessage' => '店铺不存在或状态异常...']);
        }
        //Todo 拿不到当前登录用户信息
        $update = [
            'storeRemark' => $checkData['remark'] ?? '',
            'status' => $checkData['status'],
            'checkID' => '0',//app()->get("adminProfile")->id,
            'checkAt' => date('Y-m-d H:i:s'),
        ];
        $update['reason'] = $checkData['status'] == 2 ? $checkData['reason'] : '';
        $store::update($update, ['id' => $id]);
        return renderResponse();
    }

    public function storeStatistics(int $id)
    {
        $store = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if ($store) {
            return renderResponse($this->servletFactory->storeServ()->storeStatistics($id));
        }
        throw  new ParameterException(['errMessage' => '用户不存在...']);

    }

    public function amountStatistics(int $id)
    {
        //普通用户：总充值金额 总提现金额
        //店铺用户：总充值金额 总提现金额 总交易佣金 总推广佣金
        $store = $this->servletFactory->userServ()->getUserInfoByID($id);
        if ($store) {
            if ($store->isStore) {
                $data = $this->servletFactory->storeAccountServ()->getStoreStatisticsByID($id);
            } else {
                $recharge = $this->servletFactory->rechargeServ()->getRechargeByID($id);
                $withdrawal = $this->servletFactory->withdrawalServ()->getWithdrawalByID($id);
                $data = compact('recharge', 'withdrawal');
            }
            return renderResponse($data);
        }
        throw  new ParameterException(['errMessage' => '用户不存在...']);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function stopStore(int $id)
    {
        $store = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if ($store) {
            if ($store->status != 3){
                $store::update(['status' => 3], ['id' => $id]);
            }
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '店铺不存在...']);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function startStore(int $id)
    {
        $store = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if ($store) {
            if ($store->status == 2){
                throw new ParameterException(['errMessage' => '审核失败的店铺不能解冻...']);
            }
            $store::update(['status' => 1], ['id' => $id]);
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '店铺不存在...']);

    }
}