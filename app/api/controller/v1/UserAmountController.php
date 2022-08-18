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
//        "amountType":1,
//"bank":"交通银行",
//"subbranch":"郑州高新区支行",
//"bankAmountName":"张三",
//"bankCard":"1222567899000778",
//"password":"123456"
//        "amountType":2,
//        "walletAddress":"1234567890",
//        "password":"1234",
        //1->银行卡 2->TRC20 3->ERC20
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