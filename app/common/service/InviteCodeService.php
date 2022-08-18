<?php

namespace app\common\service;

/**
 * \app\common\service\InviteCodeService
 */
class InviteCodeService implements InviteServiceInterface
{
    /**
     * @var string
     */
    protected $key, $num;


    public function __construct()
    {
        $this->key = 'abcdefghjkmnpqrstuvwxyz123456789';
        // 注意这个key里面不能出现数字0  否则当 求模=0 会重复的
        // 多少进制
        $this->num = strlen($this->key);
    }

    // 传递用户id生成唯一邀请码
    public function enCode(int $user_id): string
    {
        $code = ''; // 邀请码
        while ($user_id > 0) { // 转进制
            $mod = $user_id % $this->num; // 求模
            $user_id = ($user_id - $mod) / $this->num;
            $code = $this->key[$mod] . $code;
        }
        $code = str_pad($code, 4, 'L', STR_PAD_LEFT); // 不足用0补充
        return $code;
    }

    // 邀请码获取用户id  一般都不需要用到
    public function deCode($code): int
    {
        if (strrpos($code, 'L') !== false)
            $code = substr($code, strrpos($code, 'L') + 1);
        $len = strlen($code);
        $code = strrev($code);
        $user_id = 0;
        for ($i = 0; $i < $len; $i++)
            $user_id += strpos($this->key, $code[$i]) * pow($this->num, $i);
        return (int)$user_id;
    }

}