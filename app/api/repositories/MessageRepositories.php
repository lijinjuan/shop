<?php

namespace app\api\repositories;

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
        return renderResponse($this->servletFactory->messageServ()->getMessageByID($id));
    }
}