<?php

namespace app\api\controller\v1;

use app\api\repositories\BannersRepositories;

/**
 * \app\api\controller\v1\BannerController
 */
class BannerController
{
    /**
     * @var \app\api\repositories\BannersRepositories
     */
    protected BannersRepositories $bannersRepositories;

    /**
     * @param \app\api\repositories\BannersRepositories $bannersRepositories
     */
    public function __construct(BannersRepositories $bannersRepositories)
    {
        $this->bannersRepositories = $bannersRepositories;
    }

    /**
     * getBannerByID
     * @param int $bannerID
     * @return mixed
     */
    public function getBannerByID(int $bannerID)
    {
        return $this->bannersRepositories->getBannerDetailByID($bannerID);
    }

}