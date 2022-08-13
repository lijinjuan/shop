<?php

namespace app\api\repositories;

/**
 * \app\api\repositories\BrandsRepositories
 */
class BrandsRepositories extends AbstractRepositories
{
    /**
     * getBrandsList
     * @return \think\response\Json
     */
    public function getBrandsList()
    {
        $brandsList = $this->servletFactory->brandsServ()->getBrandsList();
        return renderResponse($brandsList);
    }
}