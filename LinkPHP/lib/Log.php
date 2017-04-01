<?php
/**
 * 框架日志类
 */

namespace LinkPHP\lib;

class Log
{
    static public $class;

    static public function init(){

        //存储方式
        $drive=Config::get("LOG_TYPE",'log');
        $class="\\LinkPHP\\lib\\drives\\log\\".$drive;
        self::$class=new $class;

    }

    static public function log($msg,$log_flag="",$fix=''){
        self::$class->log($msg,$log_flag,$fix);
    }


}