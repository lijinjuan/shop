<?php

namespace app\api\servlet;

use app\common\model\StoreAccountModel;
use app\common\model\UsersAmountModel;

class StoreAccountServlet
{
    /**
     * @var StoreAccountModel
     */
    protected StoreAccountModel $storeAccountModel;

    /**
     * @param StoreAccountModel $storeAccountModel
     */
    public function __construct(StoreAccountModel $storeAccountModel)
    {
        $this->storeAccountModel = $storeAccountModel;
    }

    /**
     * @param int $type
     * @return StoreAccountModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function withdrawalList(int $type)
    {
        $fmtData = [];
        $id = app()->get('userProfile')->id;
        $model = $this->storeAccountModel->field(['id','title','changeBalance','action','type','createdAt'])->where('userID',$id);
        if ($type){
            $model->where('type',$type);
        }
        $data = $model->append(['monthTime'])->select()->toArray();
        foreach ($data as $key =>$item){
            $fmtData[$item['monthTime']][$key] = $item;
        }
        return $fmtData;
    }
}