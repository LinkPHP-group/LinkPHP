<?php
/**
 *
 * LinkPHP框架配置类
 *
 */

namespace LinkPHP\lib;


class Config
{

    static public $conf=array();

    static public function get($key,$file){

        $file_path=LP_CONFIG.$file.".php";
        if(isset(self::$conf[$file])){
            if(isset(self::$conf[$file][$key])){
                return self::$conf[$file][$key];
            }else{
                return false;
            }
        }else{
            if(is_file($file_path)){
                $conf=include $file_path;
                if(isset($conf[$key])){
                    self::$conf[$file]=$conf;
                    return $conf[$key];
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

    }

    static public function all($file,$ty='sys'){
        if($ty=='sys'){
            $file_path=LP_CONFIG.$file.".php";
        }else if($ty=='app'){
            $file_path=LP_APP.'/config/'.$file.".php";
        }else{
            $file_path=LP_CONFIG.$file.".php";
        }

        if(isset(self::$conf[$file])){
            return self::$conf[$file];
        }else{
            if(is_file($file_path)){
                $conf=include $file_path;
                self::$conf[$file]=$conf;
                return self::$conf[$file];
            }else{
                return false;
            }
        }
    }

}