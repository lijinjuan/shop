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


    /**
     * @param int $type
     * @return UsersAmountModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function withdrawalList(int $type)
    {
        $id = app()->get('userProfile')->id;
        $model = $this->amountModel->where('userID',$id);
        if ($type){
            $model->where('type',$type);
        }
        return $model->field(['id','title','changeBalance','action'])->select();
    }

}