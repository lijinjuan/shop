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
        "userEmail|用户邮箱" => "require|email",
        "password|用户密码" => "require|alphaDash|min:6|max:20"
    ];


}