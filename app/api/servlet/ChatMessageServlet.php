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
            throw  new ParameterException(['errMessage' => '聊天信息添加失败...' . $e->getMessage()]);
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


    public function getMessageCountByUserID(int $userID,int $toUserRoleID)
    {
        $chatList = $this->chatMessageModel->where('toUserID', $userID)->where('toUserRoleID',$toUserRoleID)->select();
        $indexKey = "fromUserID";
        $userList = $chatList->dictionary($chatList, $indexKey);

        foreach ($userList as $userID => &$userItem) {
            $userIsNotReadCount = $chatList->where("fromUserID", $userID)->where("isRead", 0)->count();
            $userItem["messageCount"] = $userIsNotReadCount;
        }

        return array_values($userList);
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
        return $this->chatMessageModel->where('toUserID', $toUserID)->where('fromUserID', $fromUserID)->limit($limit)->select();

    }

    /**
     * @param int $fromUserID
     * @param int $toUserID
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getMessageList(int $fromUserID, int $toUserID, int $pageSize = 20)
    {
        $map1 = [
            ['fromUserID', '=', $fromUserID],
            ['toUserID', '=', $toUserID],
        ];
        $map2 = [
            ['fromUserID', '=', $toUserID],
            ['toUserID', '=', $fromUserID],
        ];
        return $this->chatMessageModel->whereOr([$map1, $map2])->paginate($pageSize);

    }

    /**
     * @param int $fromUserID
     * @param int $toUserID
     * @return ChatMessageModel
     */
    public function setRead(int $fromUserID, int $toUserID)
    {
        return $this->chatMessageModel->where('fromUserID', $fromUserID)->where('toUserID', $toUserID)->update(['isRead' => 1]);
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getChatMessageListByToUserID(int $pageSize = 20)
    {
        return $this->chatMessageModel->where('toUserID',app()->get("userProfile")->id)->order('createdAt','desc')->paginate($pageSize);
    }


}