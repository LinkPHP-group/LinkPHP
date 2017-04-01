<?php
/**
 * 框架中间件基类
 */

namespace LinkPHP\lib;


class Middle
{

    public function __construct()
    {

    }

    public function vfMiddle($vf,$data){
        $res_check=lp_check_files($vf,$data);
        if ($res_check['code'] != 0) {

            if(lp_is_ajax() || APP_TYPE=='api'){
                lp_send(lp_ret($res_check['code'], $res_check['msg']));
            }else{
                lp_error($res_check['msg']);
            }

            exit;
        }
    }


}