<?php
/**
 *LinkPHP 框架启动入口文件
 */
header("content-type:text/html;charset=utf-8");

//设置中国时间区
date_default_timezone_set("asia/shanghai");

//开启session
session_start();

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.4.0','<'))  die('require PHP > 5.4.0 !');

defined('LP_PATH')   or define('LP_PATH',     __DIR__.'/');
defined('LP_TPL')   or define('LP_TPL',     LP_PATH.'tpl/');
defined('LP_COMMON')   or define('LP_COMMON',     LP_PATH.'common/');
defined('LP_LIB')   or define('LP_LIB',     LP_PATH.'lib/');
defined('LP_CONFIG')   or define('LP_CONFIG',     LP_PATH.'config/');
defined('APP_TYPE')   or define('APP_TYPE','web');
defined('_ROOT_')   or define('_ROOT_',$_SERVER['SCRIPT_NAME']);

//入口文件所属站点根目录
define('IS_CGI',(0 === strpos(PHP_SAPI,'cgi') || false !== strpos(PHP_SAPI,'fcgi')) ? 1 : 0 );
define('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
define('IS_CLI',PHP_SAPI=='cli'? 1   :   0);

if(!IS_CLI) {
    // 当前文件名
    if(!defined('_PHP_FILE_')) {
        if(IS_CGI) {
            //CGI/FASTCGI模式下
            $_temp  = explode('.php',$_SERVER['PHP_SELF']);
            define('_PHP_FILE_',    rtrim(str_replace($_SERVER['HTTP_HOST'],'',$_temp[0].'.php'),'/'));
        }else {
            define('_PHP_FILE_',    rtrim($_SERVER['SCRIPT_NAME'],'/'));
        }
    }
    if(!defined('ROOT_PATH')) {
        $_root  =   rtrim(dirname(_PHP_FILE_),'/');
        define('ROOT_PATH',  (($_root=='/' || $_root=='\\')?'':$_root));
    }
}

const LP_VERSION="V1.0.3";

if(DEBUG==true){
    error_reporting(E_ALL);
    include LP_PATH."vendor/autoload.php";
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
    ini_set('display_error','On');
}else{
    error_reporting(0);
    ini_set('display_error','Off');
}

include LP_APP.'/common/function.php';
include LP_COMMON.'function.php';
include LP_LIB . 'Lp.php';
//自动加载类
spl_autoload_register('\LinkPHP\lib\Lp::load');
//启动框架
LinkPHP\lib\Lp::run();


