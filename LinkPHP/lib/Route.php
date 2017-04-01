<?php
/**
 * 框架路由类
 */

namespace LinkPHP\lib;

class Route
{

    public $f='';//执行方法
    public $r='0';//执行方法，0：正常，-1：路由不存在，-2：非法请求,-3：方法路径错误

    public static $rote_arr=array();
    public static $func_arr=array();
    public static $path_info='';

    public static $request=array();
    public static $response=array();
    public static $middle='';

    public function routeAct()
    {

        if($_GET){
            self::$request=$_GET;
        }

        if($_POST){
            self::$request=$_POST;
        }

        $_REQUEST=[];
        $_GET=[];
        $_POST=[];

        if(isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!="/"){
            $method=strtolower($_SERVER['REQUEST_METHOD']);
            $path=$_SERVER['PATH_INFO'];

            if($method=='get'){
                $get_str=strstr($path,config::get("DEF_GET_FIX","route"));
                if(!empty($get_str)){
                    $path=str_replace('/'.$get_str,'',$path);
                    $get_str=str_replace(config::get("DEF_GET_FIX","route"),'',$get_str);
                    $get_str_arr=explode('/',trim($get_str,'/'));
                    $get_arr_flag=[];
                    foreach($get_str_arr as $k=>$v){
                        if(($k%2)==0 && isset($get_str_arr[$k+1])){
                            $get_arr_flag[$get_str_arr[$k]]=$get_str_arr[$k+1];
                        }
                    }
                    if($get_arr_flag){
                        self::$request=array_merge(self::$request,$get_arr_flag);
                    }
                }

            }

            if(!isset(self::$rote_arr[$path])){
                //请求路由不存在
                $this->r='-1';
            }else{

                $req_method=self::$rote_arr[$path]['method'];
                if($req_method=='any' || $req_method==$method){
                    //请求方式正确并且请路由存在
                    $this->r='0';
                    $this->f=self::$rote_arr[$path]['func'];
                    self::$path_info=$_SERVER['PATH_INFO'];
                    self::$middle=self::$rote_arr[$path]['middle'];//设置中间件
                }else{
                    $this->r='-2';
                }

            }

        }else{
            $this->r='0';
            $this->f=config::get("DEF_FUNC","route");
        }

        $func_arr=array();
        if(!empty($this->f)){
            $func_arr_flag=explode('/',trim($this->f,'/'));
            if(sizeof($func_arr_flag)!=3){
                $this->r='-3';
            }else{
                $func_arr=array(
                    'm'=>$func_arr_flag[0],
                    'a'=>$func_arr_flag[1],
                    'f'=>$func_arr_flag[2],
                );
            }

        }

        self::$func_arr=$func_arr;
        $res_req['r']=$this->r;
        $res_req['f']=$func_arr;

        return $res_req;

    }

    public static function req($method,$path,$func,$middle=""){

        if(!isset(self::$rote_arr[$path])){
            self::$rote_arr[$path]=array(
                'method'=>strtolower($method),
                'path'=>$path,
                'func'=>$func,
                'middle'=>$middle
            );
        }

    }




}