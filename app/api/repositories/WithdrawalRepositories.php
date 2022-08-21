<?php

namespace app\api\repositories;

use app\lib\exception\ParameterException;

class WithdrawalRepositories extends AbstractRepositories
{
    /**
     * @param array $data
     * @return \think\response\Json
     */
    public function addWithdrawal(array $data)
    {
        if ($data['withdrawalMoney'] > app()->get('userProfile')->balance){
            throw new ParameterException(['errMessage'=>'提现金额不得超过账户余额...']);
        }
        //Todo 判断提现账号是否存在
        $res = $this->servletFactory->userAmountServ()->getOneByTypeID($data['withdrawalType']);
        if (!$res){
            throw new ParameterException(['errMessage'=>'提现账号不存在...']);
        }
        //Todo 判断安全密码是否正确
        if (!password_verify($data['payPassword'],app()->get("userProfile")->payPassword)){
            throw new ParameterException(['errMessage'=>'安全密码错误...']);
        }
        $data['orderNo'] = makeOrderNo();
        $data['userID'] = app()->get('userProfile')->id;
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
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function withdrawalList(int $type)
    {
        return renderResponse($this->servletFactory->storeAccountServ()->withdrawalList($type));
    }

}