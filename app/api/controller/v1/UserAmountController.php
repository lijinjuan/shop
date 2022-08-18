<?php

namespace app\api\controller\v1;

use app\api\repositories\UserAmountRepositories;
use think\Request;

class UserAmountController
{
    /**
     * @var UserAmountRepositories
     */
    protected UserAmountRepositories $amountRepositories;

    /**
     * @param UserAmountRepositories $amountRepositories
     */
    public function __construct(UserAmountRepositories $amountRepositories)
    {
        $this->amountRepositories = $amountRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */

    public function addAmount(Request $request)
    {

        $amountType = $request->post('amountType');
        //支行
        $subbranch = $request->post('subbranch');
        //户名
        $bankAmountName = $request->post('bankAmountName');
        //开户银行
        $bank = $request->post('bank');
        //银行卡账号
        $bankCard = $request->post('bankCard');
        //钱包地址
        $walletAddress = $request->post('walletAddress');
        //安全密码
        $password = $request->post('password');
        return $this->amountRepositories->addUserAmount(compact('amountType', 'subbranch', 'bankAmountName', 'bank', 'bankCard', 'walletAddress', 'password'));
    }


}