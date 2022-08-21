<?php

namespace app\admin\servlet;

use app\common\model\BannerItemsModel;

class BannerItemsServlet
{
    /**
     * @var BannerItemsModel
     */
    protected BannerItemsModel $bannerItemsModel;

    /**
     * @param BannerItemsModel $bannerItemsModel
     */
    public function __construct(BannerItemsModel $bannerItemsModel)
    {
        $this->bannerItemsModel = $bannerItemsModel;
    }

    public function addBannerItem(array $data)
    {
        return $this->bannerItemsModel::create($data);
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function bannerList(int $pageSize)
    {
        return $this->bannerItemsModel->with(['banner'=>function($query){
            $query->field(['id','bannerName']);
        }])->hidden(['deletedAt'])->order('createdAt','desc')->paginate($pageSize);
    }

    /**
     * @param int $id
     * @return BannerItemsModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBannerByID(int $id)
    {
        return $this->bannerItemsModel->where('id',$id)->hidden(['deletedAt'])->with(['banner'=>function($query){
            $query->field(['id','bannerName']);
        }])->find();

    }


}