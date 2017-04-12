<?php
/**
 * 框架函数库
 */
use LinkPHP\lib\Config;
function p($var){

    if(is_bool($var)){
        var_dump($var);
    }else if(is_null($var)){
        var_dump(null);
    }else{
        echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:solid 1px #aaa;font-size:14px;line-height:18px;opacity:0.9;' >".print_r($var,true)."</pre>";
    }

}

//转化为json
function to_json($obj) {
    //url编码,避免json_encode将中文转为unicode
    $obj = urlencode($obj);
    $str_json = json_encode($obj);
    //url解码,转完json后将各属性返回,确保对象属性不变
    return urldecode($str_json);
}

//获取应用配置数据
function lp_c($key){
   return Config::get($key,'config');
}

//生成url
function lp_u($route="",$prams=array()){
    $prams_str="";
    if($prams){
        $prams_str="/".$log_flag=Config::get("DEF_GET_FIX",'route');
        foreach($prams as $k=>$v){
            $prams_str.='/'.$k."/".$v;
        }
    }
    return _ROOT_.$route.$prams_str;
}

//smarty模板中调用生成url
function lp_u_tpl($format){
    $format['p']=explode(',',$format['p']);
    $params=array();
    if($format['p']){
        foreach( $format['p'] as $v){
            $flag=array();
            $flag=explode('=',$v);
            if(sizeof($flag)>=2){
                $params[$flag[0]]=urlencode($flag[1]);
            }

        }
    }


    $prams_str="";
    if($params){
        $prams_str="/".$log_flag=Config::get("DEF_GET_FIX",'route');
        foreach($params as $k=>$v){
            $prams_str.='/'.$k."/".$v;
        }
    }
    return _ROOT_.$format['r'].$prams_str;
}


//返回数据
function lp_send($data,$type='json')
{
    switch (strtoupper($type)) {
        case 'JSON' :
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            $data = json_encode($data);
            break;
        case 'JSONP':
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            $handler = isset($_GET[lp_c('VAR_JSONP_HANDLER')]) ? $_GET[lp_c('VAR_JSONP_HANDLER')] : lp_c('DEFAULT_JSONP_HANDLER');
            $data = $handler . '(' . json_encode($data) . ');';
            break;
        case 'EVAL' :
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            break;
    }
    exit($data);
}

//获取，设置缓存
function lp_cache($key,$data='',$time_out='-1'){

    if(empty($key)){
        return false;
    }
    $cache=new \LinkPHP\lib\Cache();
    if($data=='' && $time_out=='-1'){
        //获取缓存
        return $cache->getData($key);
    }else{
        //设置缓存
        return $cache->setData($key,$data,$time_out);
    }

}

//清除缓存
function lp_cache_clear($key='',$is_all=0){

    if($is_all=='0' && empty($key)){
        return false;
    }

    $cache=new \LinkPHP\lib\Cache();
    return $cache->clearData($key,$is_all);

}

//获取客户端ip
function lp_get_ip(){
    return $_SERVER['REMOTE_ADDR'];
}

//生成表单令牌_token
function lp_mk_token(){
    $token_key=lp_c("token_key");
    return md5(md5($_SERVER['HTTP_USER_AGENT']).$_SERVER['REMOTE_ADDR'].$token_key);
}

//获取请求方式
function lp_req_ty(){
    return strtolower($_SERVER['REQUEST_METHOD']);
}

//统一格式返回json数据
function lp_ret($code="-1",$msg="数据拉取失败",$data=array(),$ext=array('flag'=>'')){

    $res=array();
    $res["code"]=$code;
    $res["msg"]=$msg;
    $res["ext"]=$ext;
    $res['data']=$data;

    return $res;

}

/*
    入参字段验证规则
       $vf = array(
            "uid" => array("is_yes" => "y", "is_empty" => "n", "v" => "num", 'ch' => '用户编号'),
            "phone" => array("is_yes" => "y", "is_empty" => "n", "v" => "/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/", 'ch' => '手机号码')
        );
    is_yes：是否为必传参数（y-》必传，n-》非必传）
    is_empty:是否允许为空（y-》允许为空，n-》不允许为空）
    v：传值验证（num-》必须为数字，str-》可以是任意字符，/^.+/-》数据验证正则表达式，eq-》相等规则,compare->和指定值比较）
    value：当v值为“eq”时，必传值；compare时必传，并且必须在所传入的字段列表中
    ch:返回中文提示信息

    $vf:验证规则数组
    $data：提交过来的数组

 */
function lp_check_files($vf,$data){

    $key_vf=array_keys($vf);
    $key_data=array_keys($data);
    $res=array();
    $res["code"]=0;
    $res["msg"]="";
    $res["data"]="";

    for($i=0;$i<sizeof($key_vf);$i++){
        if(in_array($key_vf[$i],$key_data)){

            if($vf[$key_vf[$i]]["is_empty"]=="n" && $data[$key_vf[$i]]==""){
                $res["code"]="-1";
                //$res["msg"]=$key_vf[$i]."是必传参数并且不能为空";
                $res["msg"]=$vf[$key_vf[$i]]['ch']."是必传参数并且不能为空";

            }else{
                if(!($vf[$key_vf[$i]]["is_empty"]=="y" && $data[$key_vf[$i]]=="")){
                    if($vf[$key_vf[$i]]["v"]=="num"){
                        if(!is_numeric($data[$key_vf[$i]])){
                            $res["code"]="-1";
                            //$res["msg"]=$key_vf[$i]."必须是数字";
                            $res["msg"]=$vf[$key_vf[$i]]['ch']."必须是数字";

                        }
                    }else if($vf[$key_vf[$i]]["v"]=="compare"){
                        if($data[$key_vf[$i]] != $data[$key_vf[$i]['value']]){
                            $res["code"]="-1";
                            $res["msg"]=$key_vf[$i]."和".$key_vf[$i]['value']."值不相等";

                        }
                    }else if($vf[$key_vf[$i]]["v"]=="eq"){
                        if($data[$key_vf[$i]] != $vf[$key_vf[$i]]['value']){
                            $res["code"]="-1";
                            $res["msg"]=$vf[$key_vf[$i]]['ch']."传值错误";

                        }
                    }else if($vf[$key_vf[$i]]["v"]!="str"){
                        if(!preg_match($vf[$key_vf[$i]]["v"],$data[$key_vf[$i]])){
                            $res["code"]="-1";
                            $res["msg"]=$vf[$key_vf[$i]]['ch']."字段值不合法";

                        }
                    }
                }else{
                    $res["code"]=0;
                    $res["msg"]="";
                }
            }

        }else{

            //不存在
            if($vf[$key_vf[$i]]["is_yes"]=="y"){

                $res["code"]="-1";
                $res["msg"]=$vf[$key_vf[$i]]['ch']."是必传参数";
                break;

            }
        }
    }

    return $res;
}

//判断请求是否是ajax请求
function lp_is_ajax(){
    if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
        // ajax 请求的处理方式
        return true;
    }else{
        // 正常请求的处理方式
        return false;
    }
}

//操作失败提示页面
function lp_error($msg="操作失败",$s=3){
    if(lp_c('tpl_def_status')){
        $def=lp_c('tpl_def');
        include $def['error'];
    }else{
        include LP_TPL."error.html";
    }

    exit;
}

//操作成功提示页面
function lp_success($target_url,$msg="操作成功",$s=3){
    if(lp_c('tpl_def_status')){
        $def=lp_c('tpl_def');
        include $def['success'];
    }else {
        include LP_TPL . "success.html";
    }
    exit;
}

//404提示页面
function lp_404(){
    http_response_code(404);
    if(lp_c('tpl_def_status')){
        $def=lp_c('tpl_def');
        include $def['404'];
    }else {
        include LP_TPL . "404.html";
    }
    exit;
}

//直接跳转
function lp_redirect($target_url){
    Header("Location: $target_url");
    exit;
}

//web与api返回方式
function lp_return_notice($code='-1',$msg="",$is_404=0){
    if($code!='0'){
        if($is_404==0){
            if(lp_is_ajax() || APP_TYPE=='api'){
                lp_send(lp_ret($code, $msg));
            }else{
                lp_error($msg);
            }
        }else{
            if(lp_is_ajax() || APP_TYPE=='api'){
                http_response_code(404);
                lp_send(lp_ret('404', 'system error!'));
            }else{
                lp_404();
            }

        }

    }
}

//记录日志
function lp_log($title,$msg,$log_flag=0,$log_fix=""){

    $log=new \LinkPHP\lib\Log();
    $log_conf=Config::get("LOG_FLAG",'log');
    $log::log(array($title,$msg),$log_conf[$log_flag],$log_fix);

}

//get方式请求接口
function lp_get($url,$data=array()){

    $res_url=$url;
    $flag=0;
    foreach($data as $key=>$v){
        $flag+=1;
        if($flag==1){
            $res_url.="?";
        }else{
            $res_url.="&";
        }
        $res_url.=$key."=".$v;
    }
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $res_url);
    //设置头文件的信息作为数据流输出
    //curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $res_data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    return json_decode($res_data,true);

}

//post方式请求接口
function lp_post($url, $data=array()){
    $ch = curl_init();
    $timeout = 300;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $handles = curl_exec($ch);
    curl_close($ch);
    return json_decode($handles,true);
}

//utf-8下截取中文字符串
/**
 * @param $need_str,需要截取的目标字符串
 * @param $start,截取起始位置
 * @param $end，截取结束位置或截取字符长度
 * @return string
 */
function lp_substr_cn($need_str,$start,$end){
    return mb_substr($need_str,$start,$end,'utf-8');
}





