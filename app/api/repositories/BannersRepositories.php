<?php

namespace app\api\repositories;

/**
 * \app\api\repositories\BannersRepositories
 */
class BannersRepositories extends AbstractRepositories
{

    /**
     * getBannerDetailByID
     * @param int $bannerId
     * @return mixed
     */
    public function getBannerDetailByID(int $bannerId)
    {
        $banner = $this->servletFactory->bannerServ()->getBannerById($bannerId);
        return renderResponse($banner);
    }
}