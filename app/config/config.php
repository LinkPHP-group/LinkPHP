<?php
/**
 *
 * 应用配置文件
 *
 */

return array(

    //模板相关配置
    'tpl'=>array(
        'is_open'=>true,//是否开启smarty模板引擎，未开启的话直接在模板中写php代码
        'config_dir'=>LP_APP."/config/",//指定smarty配置文件路径
        'caching'=>false,//设置缓存开启
        'cache_lifetime'=>600,//设置缓存的时间
        'left_delimiter'=>'<{',//指定左定界符，避免和JS冲突
        'right_delimiter'=>'}>',
    ),

    //自定义提示模版，404，error，success
    'tpl_def_status'=>false,//是否使用自定显示模版，true->自定义模版，false->框架默认
    'tpl_def'=>array(
        '404'=>TPL_PATH.'/default/404.html',
        'error'=>TPL_PATH.'/default/error.html',
        'success'=>TPL_PATH.'/default/success.html',
    ),

    //网页静态资源存放配置，图片、样式，js等
    'static'=>array(
        '_js'=>ROOT_PATH.'/static/js',
        '_css'=>ROOT_PATH.'/static/css',
        '_img'=>ROOT_PATH.'/static/img'
    ),

    //表单令牌，防跨站攻击，令牌只对post请求有效
    'token_status'=>false,//令牌开关，true->开，false->关
    'token_key'=>'lp_form_token',//令牌key,上线建议修改

    //缓存相关配置,弱缓存方式为非文件缓存还需针对性的进行配置
    'cache'=>array(
        'cache_status'=>true,//true->开启缓存，false->关闭缓存
        'cache_type'=>'redis',//缓存方式，file->文件缓存，redis->redis内存缓存数据库
        'cache_time_out'=>20,//缓存失效时间，单位为“秒”
        'cache_fix'=>'lp_',//缓存前缀
        'cache_host'=>array(//redis缓存时配置
            'host'=>'127.0.0.1',
            'port'=>'6379',
            'auth'=>'123'
        ),
    ),

    //数据库默认配置
    'db_default'=>array(
        // 必须配置项
        'database_type' => 'mysql',
        'database_name' => 'lp_test',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',

        // 可选参数
        'port' => 3306,

        // 可选，定义表的前缀
        'prefix' => 'lp_',

        // 连接参数扩展, 更多参考 http://www.php.net/manual/en/pdo.setattribute.php
        'option' => [
            PDO::ATTR_CASE => PDO::CASE_NATURAL
        ]
    ),

    //----------------------------------以下是自定义配置区域------------------------------------------------


);