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
        $model = $this->storeAccountModel->field(['id', 'title', 'changeBalance', 'action', 'type', 'createdAt'])->where('userID', $id);
        if ($type) {
            //1->充值 2->提现 3->佣金 4->推广 5->消费
            $type = match ($type) {
                1 => 5,
                2 => 1,
                3 => 2,
                4 => 3,
                5 => 4
            };
            $model->where('type', $type);
        }
        $data = $model->append(['monthTime'])->select()->order('createdAt', 'desc')->toArray();
        foreach ($data as $key => $item) {
            $fmtData[$item['monthTime']][$key] = $item;
        }
        return $fmtData;
    }

    /**
     * @param array $data
     * @return StoreAccountModel|\think\Model
     */
    public function addAccount(array $data)
    {
        return $this->storeAccountModel::create($data);

    }
}