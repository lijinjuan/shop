<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;
use think\facade\Db;

class ConfigInfoRepositories extends AbstractRepositories
{
    /**
     * @param array $data
     * @return \think\response\Json
     */
    public function addRechargeConfig(array $data)
    {
        $this->servletFactory->rechargeConfigServ()->addRechargeConfig($data);
        return renderResponse();
    }

    /**
     * @param int $id
     * @param array $data
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editRechargeConfig(int $id, array $data)
    {
        $model = $this->servletFactory->rechargeConfigServ()->getRechargeInfoByID($id);
        if ($model) {
            $model::update($data, ['id' => $model->id]);
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '充值配置不存在...']);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delRechargeConfig(int $id)
    {
        $model = $this->servletFactory->rechargeConfigServ()->getRechargeInfoByID($id);
        if ($model) {
            $model->where('id', $id)->delete();
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => '充值配置不存在...']);
    }

    /**
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function rechargeConfigList(int $pageSize)
    {
        return renderPaginateResponse($this->servletFactory->rechargeConfigServ()->rechargeConfigList($pageSize));
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRechargeConfigByID(int $id)
    {
        $model = $this->servletFactory->rechargeConfigServ()->getRechargeInfoByID($id);
        if ($model) {
            return renderResponse($model);
        }
        throw new ParameterException(['errMessage' => '充值配置不存在...']);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBannerType()
    {
        return renderResponse($this->servletFactory->bannerServ()->getBannerType());
    }

    /**
     * @param array $data
     * @return \think\response\Json
     */
    public function addBanner(array $data)
    {
        $type = $this->servletFactory->bannerServ()->getBannerTypeByID($data['type']);
        if ($type) {
            Db::transaction(function () use ($data) {
                //$res = $this->servletFactory->imageServ()->addImage(['imgUrl' => $data['imgUrl'], 'imgFrom' => 1]);
                $addData = ['bannerID' => $data['type'], 'imgUrl' => $data['imgUrl'], 'sort' => $data['sort'], 'itemAction' => $data['link'], 'itemType' => 0];
                $this->servletFactory->bannerItemServ()->addBannerItem($addData);
            });
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => 'banner类型不存在...']);

    }

    /**
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function bannerList(int $pageSize)
    {
        return renderPaginateResponse($this->servletFactory->bannerItemServ()->bannerList($pageSize));
    }

    /**
     * @param int $id
     * @param array $data
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editBanner(int $id, array $data)
    {
        $model = $this->servletFactory->bannerItemServ()->getBannerByID($id);
        if ($model) {
            $data['bannerID'] = $data['type'];
            $data['itemAction'] = $data['link'];
            unset($data['type'],$data['link']);
            $model::update($data, ['id' => $model->id]);
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => 'banner不存在...']);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delBanner(int $id)
    {
        $model = $this->servletFactory->bannerItemServ()->getBannerByID($id);
        if ($model) {
            $model->where('id',$model->id)->delete();
            return renderResponse();
        }
        throw new ParameterException(['errMessage' => 'banner不存在...']);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBannerInfoByID(int $id)
    {
        $model = $this->servletFactory->bannerItemServ()->getBannerByID($id);
        if ($model) {
            return renderResponse($model);
        }
        throw new ParameterException(['errMessage' => 'banner不存在...']);
    }

}