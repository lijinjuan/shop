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
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBannerType()
    {
        return $this->configInfoRepositories->getBannerType();
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function addBanner(Request $request)
    {
        //type 1->首页轮播图 2->首页广告位 3->精品推荐
        $data = $request->only(['bannerName', 'type', 'imgUrl', 'link', 'sort']);
        return $this->configInfoRepositories->addBanner($data);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editBanner(int $id, Request $request)
    {
        $data = $request->only(['type', 'imgUrl', 'link', 'sort']);
        return $this->configInfoRepositories->editBanner($id, $data);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deleteBanner(int $id)
    {
        return $this->configInfoRepositories->delBanner($id);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBannerInfo(int $id)
    {
        return $this->configInfoRepositories->getBannerInfoByID($id);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function bannerList(Request $request)
    {
        $pageSize = $request->post('pageSize', 20);
        return $this->configInfoRepositories->bannerList($pageSize);

    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function addRechargeConfig(Request $request)
    {
        $data = $request->only(['rechargeName', 'QRCode', 'walletAddress']);
        return $this->configInfoRepositories->addRechargeConfig($data);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editRechargeConfig(int $id, Request $request)
    {
        $data = $request->only(['rechargeName', 'QRCode', 'walletAddress']);
        return $this->configInfoRepositories->editRechargeConfig($id, $data);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deleteRechargeConfig(int $id)
    {
        return $this->configInfoRepositories->delRechargeConfig($id);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function rechargeConfigList(Request $request)
    {
        $pageSize = $request->post('pageSize');
        return $this->configInfoRepositories->rechargeConfigList($pageSize);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRechargeInfoByID(int $id)
    {
        return $this->configInfoRepositories->getRechargeConfigByID($id);

    }


}