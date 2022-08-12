<?php

namespace app\api\servlet;

use app\common\model\BannersModel;

/**
 * \app\api\servlet\BannersServlet
 */
class BannersServlet
{

    /**
     * @var \app\common\model\BannersModel
     */
    protected BannersModel $bannersModel;

    /**
     * @param \app\common\model\BannersModel $bannersModel
     */
    public function __construct(BannersModel $bannersModel)
    {
        $this->bannersModel = $bannersModel;
    }

    /**
     * getBannerById
     * @param int $bannerId
     * @return mixed
     */
    public function getBannerById(int $bannerId)
    {
        return $this->bannersModel->with(['items', 'items.img'])->field(["id", "bannerName", "bannerDescription"])->find($bannerId);
    }


}