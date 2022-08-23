<?php

namespace app\agents\servlet;

use app\common\model\StoresModel;

class StoreServlet
{
    /**
     * @var StoresModel
     */
    protected StoresModel $storesModel;

    /**
     * @param StoresModel $storesModel
     */
    public function __construct(StoresModel $storesModel)
    {
        $this->storesModel = $storesModel;
    }

    /**
     * @param int $pageSize
     * @param string $keywords
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function storeList(int $pageSize,string $keywords = '',int $type = 0)
    {
        //0->待审核 1->审核通过 2->审核失败 3->冻结
        $select = ["id", "storeName", "agentID","agentName","mobile", "storeDesc", "status", "storeRemark", "cardID","frontPhoto","backPhoto","isRealPeople","storeLevel","increaseUV","userID", "parentStoreID", "createdAt"];
        $agentID = app()->get("agentProfile")->id;
        //只有一级代理商能看到真家人
        if (!app()->get("agentProfile")->agentParentID == ','){
            unset($select['isRealPeople']);
        }
        $model = $this->storesModel->whereLike("agentID", "%,$agentID,%")
            ->field($select);
        if ($keywords){
            $model->with(["user" => function ($query) use($keywords) {
                $query->where('userName','like','%'.$keywords.'%')->field(["id", "userName"]);
            }]);
        }else{
           $model->with(["user" => function ($query) {
                $query->field(["id", "userName"]);
            }]);
        }
        if ($type){
            $model->where('status',$type);
        }
        return $model->append(["parentID",'statusName'])->paginate($pageSize);

    }

    /**
     * @param int $id
     * @return StoresModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreInfoByID(int $id)
    {
        return $this->storesModel->where('id',$id)->field(["id","userID","storeLevel","creditScore","parentStoreID","agentID","increaseUV","isRealPeople"])->with(["user" => function ($query) {
            $query->field(["id", "userName","password","payPassword"]);
        }])->append(["parentID","parentAgentID"])->find();
    }

    /**
     * @param int $id
     * @param array $columns
     * @return StoresModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreInfo(int $id,array $columns = ['*'])
    {
        return $this->storesModel->where('id',$id)->field($columns)->find();
    }

    /**
     * @param int $id
     * @param string $remark
     * @return StoresModel
     */
    public function setRemarkByID(int $id, string $remark)
    {
        return $this->storesModel::update(['remark'=>$remark],['id'=>$id]);
    }




}