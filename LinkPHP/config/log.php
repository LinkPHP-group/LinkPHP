<?php
/**
 * 框架日志配置文件
 */

return array(

    'LOG_TYPE'=>'File',//文件-File,数据库-Db
    'LOG_EXT'=>array(
        'PATH'=>LP_APP.'/rundata/log/',//日志生成目录
        'PATH_DEF'=>LP_APP.'/rundata/log/Other/',//日志生成目录
        'MAX_SIZE'=>1024*1024,//日志大小限制，单位：kb
    ),
    'LOG_FLAG'=>array(

        0=>'info',
        1=>'notice',
        2=>'error'

    )

);