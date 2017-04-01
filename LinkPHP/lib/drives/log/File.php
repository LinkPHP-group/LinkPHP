<?php
/**
 * 日志文件驱动类
 */

namespace LinkPHP\lib\drives\log;
use LinkPHP\lib\Config;
use LinkPHP\lib\Route;

class File
{

    public $config;

    public function __construct()
    {
        $this->config=Config::all('log');
        //p($this->config);
    }

    public function log($msg,$file_flag="",$file_fix=''){

        $func_arr=Route::$func_arr;
        if($func_arr){
            $path=$this->config['LOG_EXT']['PATH'].$func_arr['m'].'/'.date("Y-m-d").'/';
        }else{
            $path=$this->config['LOG_EXT']['PATH_DEF'].date("Y-m-d").'/';
        }


        if(!is_dir($path)){
            mkdir($path,0755,true);
        }

        if($file_flag==""){
            $this->config['LOG_FLAG'][0];
        }

        $file_fix=empty($file_fix)?"":$file_fix."_";
        $file_name=$file_fix.date("Y-m-d").".log";

        //检测日志文件大小，超过配置大小则备份日志文件重新生成
        if(is_file($path.$file_name) && floor($this->config['LOG_EXT']['MAX_SIZE']) <= filesize($path.$file_name) ){
            rename($path.$file_name,dirname($path.$file_name).'/bak_'.time().'_'.basename($path.$file_name));
        }

        $content="[".date("Y-m-d H:i:s")."] ".$_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].PHP_EOL;
        $content.="[ip] ".$_SERVER['REMOTE_ADDR'];
        $content.=PHP_EOL;
        if(is_array($msg)){
            foreach($msg as $v){
                if(is_array($v)){
                    $content.="[".$file_flag."] ".json_encode($v,JSON_UNESCAPED_UNICODE);
                }else{
                    $content.="[".$file_flag."] ".$v;
                }

                $content.=PHP_EOL;
            }
        }else{
            $content.="[".$file_flag."] ".json_encode($msg,JSON_UNESCAPED_UNICODE);
        }

        $content.=PHP_EOL.PHP_EOL;

        file_put_contents($path.$file_name,$content,FILE_APPEND);

    }

}