<?php
/**
 * 框架缓存基类
 */

namespace LinkPHP\lib;

use LinkPHP\lib\drives\cache\File;
use LinkPHP\lib\drives\cache\RedisLib;


class Cache
{

    private static $cache_conf;
    public function __construct()
    {

        if((!isset(self::$cache_conf) || empty(self::$cache_conf))){
            self::$cache_conf=lp_c('cache');
        }

    }

    //设置缓存数据
    /**
     * @param $key：缓存数据键名
     * @param $data：缓存数据，数组
     * @param string $time_out：缓存失效时间，当设置为0时为永久缓存
     * @return bool：返回的结果为false,true
     */
    public function setData($key,$data,$time_out='-1'){
        if(self::$cache_conf['cache_status']==false){
            return false;
        }

        $time_out_flag=$time_out==0?0:self::$cache_conf['cache_time_out'];
        if(self::$cache_conf['cache_type']=='file'){
            //文件缓存

            $cache_file=new File(self::$cache_conf['cache_status']);
            $cache_file->setData($key,$data,self::$cache_conf['cache_fix'],$time_out_flag);
            return true;

        }else if(self::$cache_conf['cache_type']=='redis'){
            //redis缓存数据库
            $redis=new RedisLib(self::$cache_conf['cache_host']);

            $key_flag=self::$cache_conf['cache_fix'].$key;
            if($time_out_flag>0){
                $redis->setex($key_flag,$time_out_flag,base64_encode(json_encode($data)));
            }else{
                $redis->set($key_flag,base64_encode(json_encode($data)));
            }


            return true;
        }else{
            return false;
        }
    }

    //获取缓存数据
    public function getData($key=""){

        if(self::$cache_conf['cache_status']==false){
            return false;
        }

        if(empty($key)){
            return false;
        }

        if(self::$cache_conf['cache_type']=='file'){
            //文件方式缓存
            $cache_file=new File(self::$cache_conf['cache_status']);
            return $cache_file->getData($key,self::$cache_conf['cache_fix']);

        }else if(self::$cache_conf['cache_type']=='redis'){
            //redis缓存数据库
            $redis=new RedisLib(self::$cache_conf['cache_host']);

            $key_flag=self::$cache_conf['cache_fix'].$key;
            return json_decode(base64_decode($redis->get($key_flag),true));

        }else{
            return false;
        }

    }

    //删除缓存
    function clearData($key="",$is_all=0){

        if(self::$cache_conf['cache_status']==false){
            return false;
        }

        if(self::$cache_conf['cache_type']=='file'){

            //文件方式缓存
            $cache_file=new File(self::$cache_conf['cache_status']);
            if($is_all==1){
                //清除所有文件
                return $cache_file->delAll();
            }else{
                //指定缓存删除
                return $cache_file->delData($key,self::$cache_conf['cache_fix']);
            }

        }else if(self::$cache_conf['cache_type']=='redis'){
            //redis缓存数据库
            $redis=new RedisLib(self::$cache_conf['cache_host']);

            $key_flag=self::$cache_conf['cache_fix'].$key;

            if($is_all==1){
                //清除所有文件
                $redis->flushDB();
            }else{
                //指定缓存删除
                $redis->del($key_flag);
            }
            return true;

        }else{
            return false;
        }

    }


}