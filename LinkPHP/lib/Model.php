<?php
/**
 * 框架数据库基类
 */

namespace LinkPHP\lib;
use \LinkPHP\lib\drives\db\Medoo;

class Model extends Medoo
{

    /**
     * Model constructor.
     * @param $db_conf,数据库配置
     */
    public function __construct($db_conf=null)
    {

        if(empty($db_conf)){
            parent::__construct(lp_c('db_default'));

        }else{
            parent::__construct(lp_c($db_conf));
        }

    }


}