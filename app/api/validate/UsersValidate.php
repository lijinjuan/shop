<?php

namespace app\api\validate;

/**
 * \app\api\validate\UsersValidate
 */
class UsersValidate extends BaseValidate
{

    /**
     * @var string[]
     */
    protected $rule = [
        "email|用户邮箱" => "require|email",
        "password|用户密码" => "require",
    ];

}