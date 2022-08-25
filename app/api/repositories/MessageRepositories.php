<?php

namespace app\api\repositories;

use app\lib\exception\ParameterException;

class MessageRepositories extends AbstractRepositories
{
    /**
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function messageList(int $pageSize)
    {
        return renderPaginateResponse($this->servletFactory->messageServ()->messageList($pageSize));
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function messageDetail(int $id)
    {
        $message = $this->servletFactory->messageServ()->getMessageByID($id);
        if (!$message) {
            throw new ParameterException(['errMessage' => '站内信不存在...']);
        }
        $message::update(['isRead' => 1], ['id' => $id]);
        return renderResponse($message);
    }

    /**
     * @param int $isRead
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function messageCount(int $isRead = 0)
    {
        return renderResponse($this->servletFactory->messageServ()->noReadMessageCount($isRead));
    }
}