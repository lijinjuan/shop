<?php

namespace app\api\repositories;

use app\lib\exception\ParameterException;

class WithdrawalRepositories extends AbstractRepositories
{
    /**
     * @param array $data
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function addWithdrawal(array $data)
    {
        if ($data['withdrawalMoney'] > app()->get('userProfile')->balance) {
            throw new ParameterException(['errMessage' => '提现金额不得超过账户余额...']);
        }
        $res = $this->servletFactory->userAmountServ()->getOneByTypeID($data['withdrawalType']);
        if (!$res) {
            throw new ParameterException(['errMessage' => '提现账号不存在...']);
        }
        if (!password_verify($data['payPassword'], app()->get("userProfile")->payPassword)) {
            throw new ParameterException(['errMessage' => '安全密码错误...']);
        }
        $data['orderNo'] = makeOrderNo();
        $data['userID'] = app()->get('userProfile')->id;
        $data['withdrawalAmount'] = $data['withdrawalType'] == 1 ? $res->bankCard : $res->walletAddress;
        $this->servletFactory->withdrawalServ()->addWithdrawal($data);
        return renderResponse();
    }

    /**
     * @param int $type
     * @return \think\response\Json
     */
    public function getWithdrawalInfoByID(int $type)
    {
        return renderResponse($this->servletFactory->userAmountServ()->getOneByTypeID($type));
    }

    /**
     * withdrawalList
     * @param int $type
     * @return \think\response\Json
     */
    public function withdrawalList(int $type = 1)
    {
        return renderResponse($this->servletFactory->storeAccountServ()->withdrawalList($type));
    }

}