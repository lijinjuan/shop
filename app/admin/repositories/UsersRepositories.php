<?php

namespace app\admin\repositories;

use app\common\service\InviteServiceInterface;
use app\lib\exception\ParameterException;
use think\facade\Db;

/**
 * \app\admin\repositories\UsersRepositories
 */
class UsersRepositories extends AbstractRepositories
{

    /**
     * @param int $type
     * @param int $pageSize
     * @param string $userAccount
     * @param int $status
     * @param string $agentName
     * @param string $storeName
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DbException
     */
    public function userList(int $type, int $pageSize, string $userAccount, int $status, string $agentName = '', string $storeName = '')
    {
        if (!in_array($type, [1, 2])) {
            throw new ParameterException(['errMessage' => '参数错误...']);
        }
        //1->普通会员 2->店铺
        if ($type == 1) {
            $list = $this->servletFactory->userServ()->userList($pageSize, $userAccount);
        } else {
            $list = $this->servletFactory->storeServ()->storeList($pageSize, $status, $userAccount, $agentName, $storeName);
        }
        return renderPaginateResponse($list);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function userDetail(int $id, int $type)
    {
        //1->普通会员 2->店铺
        if ($type == 1) {
            return renderResponse($this->servletFactory->userServ()->getUserInfoByID($id));
        } else {
            return renderResponse($this->servletFactory->storeServ()->getStoreInfoByID($id));
        }

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
        if ($data['type'] == 1) {
            $userModel = $this->servletFactory->userServ()->getUserInfoByID($id);
            if (!$userModel) {
                throw new ParameterException(['errMessage' => '用户不存在...']);
            }
            if (!empty($data['userName'])) {
                $updateData['userName'] = $data['userName'];
            }
            if (!empty($data['loginPassword'])) {
                $updateData['password'] = password_hash($data['loginPassword'], PASSWORD_DEFAULT);
            }
            if (!empty($data['payPassword'])) {
                $updateData['payPassword'] = password_hash($data['payPassword'], PASSWORD_DEFAULT);
            }
            if (!empty($data['isRealPerson'])) {
                $updateData['isRealPeople'] = $data['isRealPerson'];
            }
            if (!empty($data['remark'])) {
                $updateData['remark'] = $data['remark'];
            }
            if (!empty($data['sort'])) {
                $updateData['sort'] = $data['sort'];
            }
            if (!empty($data['balance'])) {
                $updateData['balance'] = $data['balance'];
            }
            $userModel::update($updateData, ['id' => $userModel->id]);

        } elseif ($data['type'] == 2) {
            $storeModel = $this->servletFactory->storeServ()->getStoreInfoByID($id);
            if (!$storeModel) {
                throw new ParameterException(['errMessage' => '用户不存在...']);
            }
            $storeData = [
                'storeLevel' => isset($data['storeLevel']) ? $data['storeLevel'] : 0,
                'storeRemark' => isset($data['remark']) ? $data['remark'] : '',
                'isRealPeople' => isset($data['isRealPerson']) ? $data['isRealPerson'] : 0,
                'creditScore' => isset($data['creditScore']) ? $data['creditScore'] : 0,
                'sortID' => isset($data['sort']) ? $data['sort'] : 0,
            ];
            $storeModel::update(array_filter($storeData), ['id' => $storeModel->id]);
            $storeModel->user()->update(['password' => password_hash($data['loginPassword'], PASSWORD_DEFAULT), 'payPassword' => password_hash($data['payPassword'], PASSWORD_DEFAULT), 'userName' => $data['userName'], 'balance' => !empty($data['balance']) ? $data['balance'] : $storeModel->user->balance]);

        }
        return renderResponse();

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
     * 更改虚拟访客数
     * @param int $id
     * @param int $num
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editVisitor(int $id, int $num)
    {
        $storeModel = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if (!$storeModel) {
            throw new ParameterException(['errMessage' => '店铺不存在...']);
        }
        $storeModel::update(['increaseUV' => $num], ['id' => $storeModel->id]);
        return renderResponse();
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
        $update = [
            'storeRemark' => $checkData['remark'] ?? '',
            'status' => $checkData['status'],
            'checkID' => app()->get("adminProfile")->id,
            'checkAt' => date('Y-m-d H:i:s'),
        ];
        
        $update['reason'] = $checkData['status'] == 2 ? $checkData['reason'] : '';
        //审核成功之后生成邀请码
        if ($update['status'] == 1) {
            $update['inviteCode'] = app()->get(InviteServiceInterface::class)->agentInviteCode();;
        }
        Db::transaction(function () use ($store, $update, $id) {
            $store::update($update, ['id' => $id]);
            $store->user()->update(['isStore' => 1]);
            //发送站内信
            //$content = sprintf('您的店铺申请%s', '已通过');
            $content = $title = 'Store approval notice';
            if ($update['status'] == 2) {
                //$content .= sprintf(',驳回理由%s。', !empty($update['reason']) ? $update['reason'] : '');
                $content = $title = 'Store review rejection notice';

            }
            $this->servletFactory->messageServ()->addMessage(['title' => $title, 'content' => $content, 'userID' => $store->user->id]);

        });

        return renderResponse();
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storeStatistics(int $id)
    {
        $store = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if ($store) {
            //访客数 totalUV
            //下级店铺数 childStore
            //今日预估佣金 todayCommission 用户购买商品获取到的佣金
            //已结算佣金 totalCommission ?????
            //推广奖励 extensionMoney 我是别人的上级获取的奖励
            //提现总金额 withdrawal
            //总订单金额 totalOrderMoney
            //在途订单金额 noReceivedMoney
            //今日订单金额 todayOrderMoney
            //月订单金额 monthOrderMoney
            //已完成订单数 finishedOrderCount
            //已发货订单数 shipOrderCount
            //待支付订单数 noPayOrderCount
            //待发货订单数 noShipOrderCount
            $storeStatistics = $this->servletFactory->storeServ()->storeStatistics($id);
            $totalUV = $storeStatistics['totalUV'];
            $childStore = $storeStatistics['childStore'];
            $beginTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d", time())));
            $endTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d", time())) + 60 * 60 * 24);
            $todayCommission = $this->servletFactory->storeAccountServ()->getCommissionByID($id, 3, $beginTime, $endTime);
            $extensionMoney = $this->servletFactory->storeAccountServ()->getCommissionByID($id, 4);
            $totalCommission = $this->servletFactory->storeAccountServ()->getTotalCommissionByID($id);
            $withdrawal = $this->servletFactory->withdrawalServ()->getStatisticsByID($id);
            $totalOrderMoney = $this->servletFactory->orderServ()->getStatisticsByStoreID($id);
            $noReceivedMoney = $this->servletFactory->orderServ()->getStatisticsByStoreID($id, 3);
            $todayOrderMoney = $this->servletFactory->orderServ()->getStatisticsByStoreID2Time($id, $beginTime, $endTime);
            $monthOrderMoney = $this->servletFactory->orderServ()->getStatisticsByStoreID2Time($id, date("Y-m-01", time()), date("Y-m-t", time()));
            $finishedOrderCount = $this->servletFactory->orderServ()->getStatisticsNumByStoreID($id, 6);
            $shipOrderCount = $this->servletFactory->orderServ()->getStatisticsNumByStoreID($id, 4);
            $noPayOrderCount = $this->servletFactory->orderServ()->getStatisticsNumByStoreID($id, 1);
            $noShipOrderCount = $this->servletFactory->orderServ()->getStatisticsNumByStoreID($id, 3);
            return renderResponse(compact('totalUV', 'childStore', 'todayCommission', 'totalCommission', 'extensionMoney', 'withdrawal', 'totalOrderMoney', 'noReceivedMoney', 'todayOrderMoney', 'monthOrderMoney', 'finishedOrderCount', 'shipOrderCount', 'noPayOrderCount', 'noShipOrderCount'));
        }
        throw  new ParameterException(['errMessage' => '店铺不存在...']);

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
            if ($store->status != 3) {
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
            if ($store->status == 2) {
                throw new ParameterException(['errMessage' => '审核失败的店铺不能解冻...']);
            }
            $store::update(['status' => 1], ['id' => $id]);
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '店铺不存在...']);

    }

    /**
     * @param string $keywords
     * @param string $agentAccount
     * @param int $pageSize
     * @param string $storeName
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(string $keywords, string $agentAccount, int $pageSize,string $storeName)
    {
        return renderPaginateResponse($this->servletFactory->rechargeServ()->rechargeList($keywords, $agentAccount, $pageSize,$storeName));
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRechargeInfoByID(int $id)
    {
        $recharge = $this->servletFactory->rechargeServ()->getRechargeInfoByID($id);
        if (!$recharge) {
            throw new ParameterException(['errMessage' => '用户不存在...']);
        }
        return renderResponse($recharge);

    }

    public function checkRecharge(int $id, array $data)
    {
        $model = $this->servletFactory->rechargeServ()->getRechargeInfoByID($id);
        if ($model) {
            $data['checkID'] = app()->get("adminProfile")->id;
            $data['checkName'] = app()->get("adminProfile")->adminName;
            $data['checkAt'] = date('Y-m-d H:i:s');
            Db::transaction(function () use ($model, $data) {
                $model::update($data, ['id' => $model->id]);
                //充值成功 写入用户账变表
                if ($data['status'] == 1) {
                    $userInfo = $this->servletFactory->userServ()->getUserInfoByID($model->userID);
                    $userInfo::update(['balance' => $userInfo->balance + $model->rechargeMoney], ['id' => $userInfo->id]);
                    $currentBalance = $userInfo->balance;
                    $changeData = [
                        'title' => '充值',
                        'storeID' => !empty($model->storeID) ? $model->storeID : 0,
                        'userID' => $model->userID,
                        'balance' => $currentBalance + $model->rechargeMoney,
                        'changeBalance' => !empty($model->rechargeMoney) ? $model->rechargeMoney : 0.00,
                        'action' => 1,
                        'remark' => '会员充值',
                        'type' => 1
                    ];
                    $this->servletFactory->storeAccountServ()->addStoreAccount($changeData);
                }
            });
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '充值记录不存在...']);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function showCheckRecharge(int $id)
    {
        return renderResponse($this->servletFactory->rechargeServ()->getRechargeInfoByID($id));
    }

    /**
     * @param int $pageSize
     * @param array $conditions
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function withdrawalList(int $pageSize = 20, array $conditions = [])
    {
        return renderPaginateResponse($this->servletFactory->withdrawalServ()->withdralList($pageSize, $conditions));
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
        return renderResponse($this->servletFactory->withdrawalServ()->getWithdrawalInfoByID($id));
    }

    public function checkWithdrawalByID(int $id, array $data)
    {
        $model = $this->servletFactory->withdrawalServ()->getOneWithdrawal($id);
        if ($model) {
            $data['checkID'] = app()->get("adminProfile")->id;
            $data['checkName'] = app()->get("adminProfile")->adminName;
            $data['checkAt'] = date('Y-m-d H:i:s');
            Db::transaction(function () use ($model, $data) {
                $model::update($data, ['id' => $model->id]);
                $userInfo = $this->servletFactory->userServ()->getUserInfoByID($model->userID);
                if ($data['status'] == 1) {
                    $userInfo::update(['balance' => $userInfo->balance - $model->withdrawalMoney], ['id' => $userInfo->id]);
                    $currentBalance = $userInfo->balance;
                    $changeData = [
                        'title' => '提现值',
                        'storeID' => $model->storeID,
                        'userID' => $model->userID,
                        'balance' => $currentBalance - $model->withdrawalMoney,
                        'changeBalance' => !empty($model->withdrawalMoney) ? $model->withdrawalMoney : 0,
                        'action' => 2,
                        'remark' => '会员提现',
                        'type' => 2
                    ];
                    $this->servletFactory->storeAccountServ()->addStoreAccount($changeData);
                }
            });

            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '提现记录不存在...']);

    }
}