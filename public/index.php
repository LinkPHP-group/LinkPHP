<?php
/**
 * 项目入口
 */

define('DEFAULT_PATH','../');//框架和应用相对于入口文件的位置,必须填写

define('EXT_PATH',DEFAULT_PATH.'ext');//扩展类存放目录
define('LP_APP',DEFAULT_PATH.'app');
define('TPL_PATH',LP_APP.'/tpl');

define('DEBUG',true);
define('APP_TYPE','web');//应用类型，web，api

include DEFAULT_PATH.'LinkPHP/LinkPHP.php';







