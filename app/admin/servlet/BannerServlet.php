<?php

namespace app\admin\servlet;

use app\common\model\BannersModel;

class BannerServlet
{
    /**
     * @var BannersModel
     */
    protected BannersModel $bannersModel;

    /**
     * @param BannersModel $bannersModel
     */
    public function __construct(BannersModel $bannersModel)
    {
        $this->bannersModel = $bannersModel;
    }

    /**
     * @param array $data
     * @return BannersModel|\think\Model
     */
    public function addBanner(array $data)
    {
        return $this->bannersModel::create($data);
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function bannerList(int $pageSize = 20)
    {
        return $this->bannersModel->where('id','>',0)->paginate($pageSize);
    }


}