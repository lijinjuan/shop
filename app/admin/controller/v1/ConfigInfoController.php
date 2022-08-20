<?php

namespace app\admin\controller\v1;

use app\admin\repositories\ConfigInfoRepositories;
use think\Request;

class ConfigInfoController
{
    /**
     * @var ConfigInfoRepositories
     */
    protected ConfigInfoRepositories $configInfoRepositories;

    /**
     * @param ConfigInfoRepositories $configInfoRepositories
     */
    public function __construct(ConfigInfoRepositories $configInfoRepositories)
    {
        $this->configInfoRepositories = $configInfoRepositories;
    }

    /**
     * @param Request $request
     * @return void
     */
    public function addBanner(Request $request)
    {

    }

    public function editBanner()
    {

    }

    public function deleteBanner()
    {

    }

    public function bannerList()
    {

    }

    public function addRechargeConfig()
    {

    }

    public function editRechargeConfig()
    {

    }

    public function deleteRechargeConfig()
    {

    }

    public function rechargeConfigList()
    {

    }

    public function getRechargeInfoByID()
    {



    }


}