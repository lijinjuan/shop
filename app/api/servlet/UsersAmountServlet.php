<?php

namespace app\api\servlet;

use app\common\model\UsersAmountModel;

class UsersAmountServlet
{
    /**
     * @var UsersAmountModel
     */
    protected UsersAmountModel $amountModel;

    /**
     * @param UsersAmountModel $amountModel
     */
    public function __construct(UsersAmountModel $amountModel)
    {
        $this->amountModel = $amountModel;
    }

    public function addUserAmount($data)
    {
        $model = $this->amountModel->where('userID',$data['userID'])->where('amountType',$data['amountType'])->find();
        if (!$model){
            $this->amountModel::create($data);
        }
        return true;
    }

    public function getOneByTypeID(int $typeID)
    {
        return $this->amountModel->where('amountType',$typeID)->where('userID',app()->get("userProfile")->id)->find();
    }

}