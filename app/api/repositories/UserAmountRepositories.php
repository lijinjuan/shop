<?php

namespace app\api\repositories;

use app\lib\exception\ParameterException;

class UserAmountRepositories extends AbstractRepositories
{

    /**
     * @param array $data
     * @return \think\response\Json
     */
    public function addUserAmount(array $data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        if (!password_verify($data['password'],app()->get("userProfile")->payPassword)){
                throw new ParameterException(['errMessage'=>'安全密码错误...']);
        }
        $data['userID'] = app()->get("userProfile")->id;
        $data['moneyMinLimit'] = 100;
        $data['moneyMaxLimit'] = 99999;
        $this->servletFactory->userAmountServ()->addUserAmount($data);
        return renderResponse();
    }

}