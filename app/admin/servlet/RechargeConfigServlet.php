<?php

namespace app\admin\servlet;

use app\common\model\RechargeConfigModel;

class RechargeConfigServlet
{
    /**
     * @var RechargeConfigModel
     */
    protected RechargeConfigModel $rechargeConfigModel;

    /**
     * @param RechargeConfigModel $rechargeConfigModel
     */
    public function __construct(RechargeConfigModel $rechargeConfigModel)
    {
        $this->rechargeConfigModel = $rechargeConfigModel;
    }

    /**
     * @param array $data
     * @return RechargeConfigModel|\think\Model
     */
    public function addRechargeConfig(array $data)
    {
       return  $this->rechargeConfigModel::create($data);
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
   public function rechargeConfigList(int $pageSize = 20)
   {
       return $this->rechargeConfigModel->where('id','>',0)->order('createdAt','desc')->paginate($pageSize);
   }

    /**
     * @param int $id
     * @return RechargeConfigModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
   public function getRechargeInfoByID(int $id)
   {
       return $this->rechargeConfigModel->where('id',$id)->find();
   }


}