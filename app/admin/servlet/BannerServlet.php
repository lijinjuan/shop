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
        //先添加图片表再写入banner
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

    /**
     * @return BannersModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBannerType()
    {
        return $this->bannersModel->where('id','>',0)->hidden(['updatedAt','deletedAt'])->select();
    }

    /**
     * @param int $id
     * @return BannersModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBannerTypeByID(int $id)
    {
        return $this->bannersModel->where('id',$id)->find();
    }


}