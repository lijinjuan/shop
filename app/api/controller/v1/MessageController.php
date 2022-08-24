<?php

namespace app\api\controller\v1;

use app\api\repositories\MessageRepositories;
use think\Request;

class MessageController
{

    /**
     * @var MessageRepositories
     */
    protected MessageRepositories $messageRepositories;

    /**
     * @param MessageRepositories $messageRepositories
     */
    public function __construct(MessageRepositories $messageRepositories)
    {
        $this->messageRepositories = $messageRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function messageList(Request $request)
    {
        $pageSize = $request->post('pageSize');
        return $this->messageRepositories->messageList($pageSize);

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
        return $this->messageRepositories->messageDetail($id);
    }


}