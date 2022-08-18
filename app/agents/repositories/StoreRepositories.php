<?php

namespace app\agents\repositories;

use app\lib\exception\ParameterException;

class StoreRepositories extends AbstractRepositories
{
    /**
     * @param int $pageSize
     * @param string $keywords
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function storeList(int $pageSize, string $keywords = '', int $type = 0)
    {
        return renderPaginateResponse($this->servletFactory->storeServ()->storeList($pageSize, $keywords, $type));
    }

    public function storeInfo(int $id)
    {
        $storeInfo = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if (!$storeInfo) {
            throw new ParameterException(['errMessage' => '店铺不存在...']);
        }
        return renderResponse($storeInfo);
    }

    /**
     * @param int $id
     * @param string $remark
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setRmarkByID(int $id, string $remark)
    {
        $store = $this->servletFactory->storeServ()->getStoreInfo($id);
        if (!$store) {
            throw new ParameterException(['errMessage' => '店铺不存在...']);
        }
        $store::update(['remark' => $remark], ['id' => $id]);
        return renderResponse();
    }

    /**
     * @param int $id
     * @param string $action
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function stopOrStartStore(int $id, string $action)
    {
        $store = $this->servletFactory->storeServ()->getStoreInfo($id);
        if (!$store) {
            throw new ParameterException(['errMessage' => '店铺不存在...']);
        }
        $store::update(['status' => $action == 'stop' ? 3 : 1], ['id' => $id]);
        return renderResponse();
    }

    /**
     * @param int $id
     * @param array $checkData
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkStore(int $id, array $checkData)
    {
        //0->待审核 1->成功 2->失败
        //Todo 判断当前审核人是不是一级代理商
        $store = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if (!$store) {
            throw new ParameterException(['errMessage' => '店铺不存在...']);
        }
        if ($store->parentID != app()->get("agentProfile")->id ){
            throw new ParameterException(['errMessage' => '当前账户无权审核店铺请更换账号...']);
        }
        $updata = [
            'storeRemark' => $checkData['remark']??'',
            'status' => $checkData['status'],
            'checkID' => app()->get("agentProfile")->id,
            'checkAt' => date('Y-m-d H:i:s'),
        ];
        $updata['reason'] = $checkData['status'] == 2 ? $checkData['reason'] : '';
        $store::update($updata, ['id' => $id]);
        return renderResponse();
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreInfoByID(int $id)
    {
        //Todo 缺少直属代理商账号
        $select = ['storeName', 'mobile', 'cardID', 'frontPhoto', 'backPhoto', 'storeDesc','status','checkAt','storeRemark'];
        return renderResponse($this->servletFactory->storeServ()->getStoreInfo($id, $select));
    }

    /**
     * @param int $id
     * @return \think\response\Json
     */
    public function storeStatistics(int $id)
    {
        return renderResponse($this->servletFactory->storeAmountServ()->getStoreStatisticsByID($id));
    }

    /**
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(int $pageSize)
    {
        return renderPaginateResponse($this->servletFactory->rechargeServ()->rechargeList($pageSize));
    }

    /**
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function withdrawList(int $pageSize)
    {
        return renderPaginateResponse($this->servletFactory->withdrawalServ()->withdralList($pageSize));

    }
}