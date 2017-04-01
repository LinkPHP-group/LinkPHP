<?php
/**
 * 框架控制器基类
 */

namespace LinkPHP\lib;


class Action
{

    public $assign=array();
    public $tpl;
    public $request=array();
    public $actionPath=array();
    static public $static=array();
    static private $token_status;
    public $token_flag=true;
    public $tpl_cache=true;
    public $def_function=array();

    function __construct()
    {
        $this->request=route::$request;
        $func_arr=route::$func_arr;
        $this->actionPath=array(
            'm'=> $func_arr['m'],
            'a'=>$func_arr['a'],
            'f'=>$func_arr['f']
        );

        if(empty(self::$static)){
            self::$static=lp_c('static');
        }

        if(empty(self::$token_status)){
            self::$token_status=lp_c('token_status');
        }

        if(self::$token_status==true && $this->token_flag==true && lp_req_ty()=="post"){

            //开启表单令牌并且表单开启状态
            if(!$this->vfToken()){
                //未验证通过
                lp_return_notice('400','_token error!');
            }

        }

        $middle=route::$middle;
        if(!empty($middle)){

            $middle_arr=explode('/',$middle);
            $file_middle=LP_APP."/middles/".$middle_arr[0]."Middle.php";
            if(is_file($file_middle)){
                include $file_middle;
                $middleClass = "\\middles\\" . $middle_arr[0]."Middle";
                $middle_new = new $middleClass();
                $middle_new->$middle_arr[1]($this->request);
            }else{
                throw new \Exception("中间件文件‘".$file_middle."’不存在");
            }

        }

    }

    /*
    * 赋值模版变量
    * */
    function assign($key,$value){
        $this->assign[$key]=$value;
    }

    /**
     * 注册模版调用函数，函数只能有一个数组参数
     */
    function regFunc($func){

        if(!empty($func)){
            $this->def_function[]=$func;
        }

    }

    function setCache($bool=true){
        $this->tpl_cache=$bool;
    }

    /*
     * 指定显示模版
     * */
    function display($path=""){
        $actionPath=$this->actionPath;
        $tplPath=TPL_PATH.'/'.$actionPath['m'].'/';
        if($path==""){
            $tplPath.=$actionPath['a']."/".$actionPath['f'].".html";
        }else{
            $tplPath.=$path.".html";
        }

        if(is_file($tplPath)){
            
            $tpl_conf=lp_c('tpl');
            $tpl_conf['config_dir'];
            if($tpl_conf['is_open']){
                require_once (LP_LIB.'drives/tpl/Smarty.class.php');
                $smarty=new \Smarty();
                $smarty->template_dir=TPL_PATH."/".$actionPath['m'].'/'.$actionPath['a'];//指定模板文件的路径
                $smarty->compile_dir=LP_APP."/rundata/temp"."/".$actionPath['m'].'/'.$actionPath['a'];//指定编译的文件路径
                $smarty->cache_dir=LP_APP."/rundata/cache"."/".$actionPath['m'].'/'.$actionPath['a'];//指定缓存文件路径
                $smarty->config_dir=$tpl_conf['config_dir'];//指定smarty配置文件路径
                $smarty->configLoad('smarty.conf');
                $smarty->caching=$tpl_conf['caching'];     //设置缓存开启
                $smarty->cache_lifetime=$tpl_conf['cache_lifetime'];  //设置缓存的时间
                $smarty->left_delimiter=$tpl_conf['left_delimiter'];//指定左定界符，避免和JS冲突
                $smarty->right_delimiter=$tpl_conf['right_delimiter'];
                $smarty->registerPlugin("function","url", "lp_u_tpl");
                foreach($this->assign as $k=>$v){
                    $smarty->assign($k,$v);
                }
                foreach(self::$static as $k=>$v){
                    $smarty->assign($k,$v);
                }

                if(lp_c('token_status')){
                    //开启表单令牌
                    $smarty->assign('_token',lp_mk_token());
                }else{
                    $smarty->assign('_token',"");
                }

                if(!$this->tpl_cache){
                    //单独页面关闭缓存
                    $smarty->caching = false;
                }

                foreach($this->def_function as $v){
                    $smarty->registerPlugin("function",$v, $v);
                }

                $smarty->display($actionPath['f'].'.html');
            }else{

                foreach(self::$static as $k=>$v){
                    $this->assign($k,$v);
                }
				extract($this->assign);
                include $tplPath;
            }

        }else{
            throw new \Exception("模板不存在:".$tplPath);

        }
        exit;

    }

    //表单令牌校验
    private function vfToken(){

        if(!isset($this->request['_token']) && empty($this->request['_token'])){
            return false;
        }

        $server_token=lp_mk_token();
        if($server_token==$this->request['_token']){
            return true;
        }else{
            return false;
        }

    }




}