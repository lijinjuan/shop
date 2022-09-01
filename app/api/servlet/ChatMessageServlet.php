<?php

namespace app\api\servlet;

use app\common\model\ChatMessageModel;
use app\lib\exception\ParameterException;

class ChatMessageServlet
{
    /**
     * @var ChatMessageModel
     */
    protected ChatMessageModel $chatMessageModel;

    /**
     * @param ChatMessageModel $chatMessageModel
     */
    public function __construct(ChatMessageModel $chatMessageModel)
    {
        $this->chatMessageModel = $chatMessageModel;
    }

    /**
     * @param array $messageData
     * @return ChatMessageModel|\think\Model
     * @throws ParameterException
     */
    public function addChatMessage(array $messageData)
    {
        try {
            return $this->chatMessageModel::create($messageData);
        } catch (\Throwable $e) {
            throw  new ParameterException(['errMessage' => '聊天信息添加失败...'.$e->getMessage()]);
        }

    }

    /**
     * @param int $id
     * @return ChatMessageModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOneMessageByID(int $id)
    {
        return $this->chatMessageModel->where('id', $id)->find();
    }

    /**
     * @param int $userID
     * @return ChatMessageModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNoReadMessageByID(int $userID)
    {
        return $this->chatMessageModel->where('toUserID', $userID)->select();
    }

    /**
     * @param int $userID
     * @return mixed
     */
    public function getMessageCountByUserID(int $userID)
    {
        $field = ['fromUserID', 'fromUserAvatar','fromUserName','toUserID', 'toUserAvatar','toUserName','count(*) as messageCount', 'createdAt'];
        return $this->chatMessageModel->where('toUserID', $userID)->where('isRead', 0)->field($field)->group(['fromUserID'])->order('createdAt', 'desc')->select();
    }

    /**
     * @param int $userID
     * @return ChatMessageModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLastConnect(int $userID)
    {
        return $this->chatMessageModel->where('toUserID', $userID)->where('isRead', 0)->order('createdAt', 'desc')->find();
    }

    /**
     * @param int $fromUserID
     * @param int $toUserID
     * @param int $limit
     * @return ChatMessageModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLastMessage(int $fromUserID, int $toUserID, int $limit = 10)
    {
        return $this->chatMessageModel->where('toUserID', $toUserID)->where('isRead', 0)->where('fromUserID', $fromUserID)->limit($limit)->select();

    }

    /**
     * @param int $fromUserID
     * @param int $toUserID
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getMessageList(int $fromUserID, int $toUserID,int $pageSize=20)
    {
        return  $this->chatMessageModel->where('toUserID', $toUserID)->where('isRead', 0)->where('fromUserID', $fromUserID)->where('isRead',0)->paginate($pageSize);

    }

    /**
     * @param int $fromUserID
     * @param int $toUserID
     * @return ChatMessageModel
     */
    public function setRead(int $fromUserID,int $toUserID)
    {
        return $this->chatMessageModel->where('fromUserID',$fromUserID)->where('toUserID',$toUserID)->update(['isRead' => 1]);
    }


}