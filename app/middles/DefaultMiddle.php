<?php

/**
 * 默认中间件实现类
 * Class DefaultMiddle
 *
 */
namespace middles;
use LinkPHP\lib\Middle;

class DefaultMiddle extends Middle
{

    public function __construct()
    {
        parent::__construct();
    }

    function test($data){

        //入参合法性检测
        $vf = array(
            "uid" => array("is_yes" => "y", "is_empty" => "n", "v" => "num", 'ch' => '用户编号'),
            "phone" => array("is_yes" => "y", "is_empty" => "n", "v" => "/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/", 'ch' => '手机号码')
        );

        //中间件验证
        $this->vfMiddle($vf,$data);

    }

}