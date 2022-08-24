<?php

namespace app\api\servlet;

use app\common\model\MessagesModel;
use app\lib\exception\ParameterException;

class MessageServlet
{
    /**
     * @var MessagesModel
     */
    protected MessagesModel $messagesModel;

    /**
     * @param MessagesModel $messagesModel
     */
    public function __construct(MessagesModel $messagesModel)
    {
        $this->messagesModel = $messagesModel;
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function messageList(int $pageSize)
    {
        return $this->messagesModel->where('userID',app()->get('userProfile')->id)->order('createdAt','desc')->paginate($pageSize);
    }

    /**
     * @param int $id
     * @return MessagesModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMessageByID(int $id)
    {
        return $this->messagesModel->where('id',$id)->find();
    }

    /**
     * @param array $data
     * @return MessagesModel|\think\Model
     * @throws ParameterException
     */
    public function addMessage(array $data)
    {
        try {
            return $this->messagesModel::create($data);
        }catch (\Throwable $e){
            throw new ParameterException(['errMessage'=>'站内信写入失败...'.$e->getMessage()]);
        }
    }

}