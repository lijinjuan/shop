<?php

namespace app\agents\repositories;

use app\common\service\InviteServiceInterface;
use app\lib\exception\ParameterException;
use think\facade\Db;

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
        if ($store->parentAgentID != app()->get("agentProfile")->id) {
            throw new ParameterException(['errMessage' => '当前账户无权设置备注请更换账号...']);
        }
        $store::update(['storeRemark' => $remark], ['id' => $id]);
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
        if ($store->parentAgentID != app()->get("agentProfile")->id) {
            throw new ParameterException(['errMessage' => '当前账户无权冻结/解冻店铺请更换账号...']);
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
        $store = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if (!$store) {
            throw new ParameterException(['errMessage' => '店铺不存在...']);
        }
        if ($store->parentAgentID != app()->get("agentProfile")->id) {
            throw new ParameterException(['errMessage' => '当前账户无权审核店铺请更换账号...']);
        }
        $update = [
            'storeRemark' => $checkData['remark'] ?? '',
            'status' => $checkData['status'],
            'checkID' => app()->get("agentProfile")->id,
            'checkAt' => date('Y-m-d H:i:s'),
        ];
        $update['reason'] = $checkData['status'] == 2 ? $checkData['reason'] : '';

        //审核成功之后生成邀请码
        if ($update['status'] == 1) {
            $update['inviteCode'] = app()->get(InviteServiceInterface::class)->agentInviteCode();;
        }
        Db::transaction(function () use ($store, $update, $id) {
            $store::update($update, ['id' => $id]);
            $store->user()->update(['isStore' => 1]);
        });

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
        $select = ['storeName', 'mobile', 'cardID', 'frontPhoto', 'backPhoto', 'storeDesc', 'status', 'checkAt', 'storeRemark', 'isRealPeople', 'increaseUV'];
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
    public function rechargeList(int $pageSize, string $keywords)
    {
        return renderPaginateResponse($this->servletFactory->rechargeServ()->rechargeList($pageSize, $keywords));
    }

    /**
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function withdrawList(int $pageSize, array $conditions)
    {
        return renderPaginateResponse($this->servletFactory->withdrawalServ()->withdralList($pageSize, $conditions));

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
    public function editStoreByID(int $id, array $data)
    {
        $storeInfo = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if (!$storeInfo) {
            throw new ParameterException(['errMessage' => '店铺不存在...']);
        }
        if ($storeInfo->parentAgentID != app()->get("agentProfile")->id) {
            throw new ParameterException(['errMessage' => '当前账户无权更改用户信息请更换账号...']);
        }
        if (!empty($data['isRealPeople']) && !in_array($data['isRealPeople'], [1, 2])) {
            throw new ParameterException(['errMessage' => '真假人参数错误...']);
        }
        //'password','payPassword','storeLevel','isRealPeople','creditScore','userName','remark'
        Db::transaction(function () use ($data, $storeInfo) {
            $storeInfo::update(['storeLevel' => $data['storeLevel'], 'isRealPeople' => $data['isRealPeople'], 'creditScore' => $data['creditScore'], 'remark' => $data['remark']], ['id' => $storeInfo->id]);
            $storeInfo->user()->update(['password' => $data['password'], 'payPassword' => $data['payPassword'], 'userName' => $data['userName']]);
        });
        return renderResponse();
    }

    /**
     * @param int $id
     * @param int $num
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editVirtualVisitors(int $id, int $num)
    {

        $storeInfo = $this->servletFactory->storeServ()->getStoreInfoByID($id);
        if (!$storeInfo) {
            throw new ParameterException(['errMessage' => '店铺不存在...']);
        }
        if ($storeInfo->parentAgentID != app()->get("agentProfile")->id) {
            throw new ParameterException(['errMessage' => '当前账户无权更改虚拟访客请更换账号...']);
        }
        $storeInfo::update(['increaseUV' => $num], ['id' => $storeInfo->id]);
        return renderResponse();

    }
}