<?php
/**
 * 框架核心类
 */

namespace LinkPHP\lib;

class Lp
{

    //避免类重复引用
    public static $classMap=array();

    //框架启动
    static public function run(){

        Log::init();
        include LP_APP."/routes.php";
        $rote=new \LinkPHP\lib\Route();
        $routeAct=$rote->routeAct();

        //加载应用自定义配置
        Config::all('config','app');

        if($routeAct['r']=='0'){
            $file_action=LP_APP."/modules/".$routeAct['f']['m']."/Action/".$routeAct['f']['a']."Action.php";

            if(is_file($file_action)) {
                include $file_action;
                $actionClass = "\\" . $routeAct['f']['m'] . '\\Action\\' . $routeAct['f']['a'] . "Action";
                $action = new $actionClass();
                if(in_array($routeAct['f']['f'],get_class_methods($action))){
                    $action->$routeAct['f']['f']();
                }else{
                    $log_flag=Config::get("LOG_FLAG",'log');
                    Log::log("找不到执行方法:".$routeAct['f']['f'],$log_flag[2],"");
                    lp_return_notice('404','',1);
                }

            }else{

                $log_flag=Config::get("LOG_FLAG",'log');
                Log::log("找不到控制器:".$file_action,$log_flag[2],"");
                lp_return_notice('404','',1);

            }
        }else{
            $log_flag=Config::get("LOG_FLAG",'log');
            switch($routeAct['r']){
                case '-1':
                    Log::log("路由不存在:",$log_flag[2],"");
                    //p("路由不存在");
                    lp_return_notice('404','',1);

                    break;

                case "-2":
                    Log::log("请求方式错误",$log_flag[2],"");
                    //p("请求方式错误");
                    lp_return_notice('404','',1);
                    break;

                case "-3":
                    Log::log("方法地址错误",$log_flag[2],"");
                    lp_return_notice('404','',1);

                    break;

            }

        }


    }

    /*
     * 自动加载类库
     * */
    static public function load($class){

        if(isset(self::$classMap[$class])){
            return true;
        }else{
            $class=str_replace("\\","/",$class);
            $file=DEFAULT_PATH.$class.'.php';
            if(is_file($file)){
                include $file;
                self::$classMap[$class]=$class;
            }else{
                return false;
            }
        }

    }

}