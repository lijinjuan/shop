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
            throw  new ParameterException(['errMessage' => '聊天信息添加失败...']);
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
        return $this->chatMessageModel->where('id',$id)->find();
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
        return $this->chatMessageModel->where('toUserID',$userID)->select();
    }


}