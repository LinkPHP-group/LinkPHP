<?php
/**
 *
 */
namespace Home\Action;
use app\common\Action\BasicAction;

class IndexAction extends BasicAction{

    function __construct()
    {
        parent::__construct();
    }

    function index(){


        echo "<body style='background: #E9E9E9;'><table style='height: 100%;width: 100%;'><tr><td style='width: 33%'></td><td style='width: 33%;border: solid 0px red;font-size: 90px;font-family:微软雅黑;color: #3E863D;'><p style='color:gray;font-size: 20px;padding-left: 7px;'>欢迎使用 Linkphp ".LP_VERSION."</p><p style='margin-top:5px;'>LinkPHP</p></td><td style='width: 33%'></td></tr></table></body>";

    }

    


}