<?php

namespace LinkPHP\lib\drives\cache;


class File
{

    public static $cache_path;

    public function __construct($cache_status)
    {

        if ((!isset(self::$cache_path) || empty(self::$cache_path)) && $cache_status == true) {
            self::$cache_path = LP_APP . '/rundata/data';
        }

    }

    function getData($key, $cache_fix)
    {

        $file_path = self::$cache_path . '/' . $cache_fix . $key . '.php';
        if (!is_file($file_path)) {
            return false;
        }

        $cache_data = include $file_path;
        if ($cache_data['end_time'] != 0 && $cache_data['end_time'] <= time()) {
            //缓存数据已过期
            return false;
        }

        return json_decode(base64_decode($cache_data['body']), true);
    }

    function setData($key, $cache_data, $cache_fix, $time_out)
    {
        $dir_path = self::$cache_path . '/';
        echo $file_path = $dir_path . $cache_fix . $key . '.php';
        if (!is_dir($dir_path)) {
            mkdir($dir_path, '0755', true);
        }

        $end_time = $time_out == 0 ? 0 : time() + $time_out;
        $content = "<?php";
        $content .= PHP_EOL;
        $content .= "return array('end_time'=>" . $end_time . ',"body"=>"' . base64_encode(json_encode($cache_data)) . '");';

        file_put_contents($file_path, $content);

    }

    function delData($key, $cache_fix)
    {
        $dir_path = self::$cache_path . '/';
        $file_path = $dir_path . $cache_fix . $key . '.php';
        if (!is_file($file_path)) {
            return false;
        }

        unlink($file_path);
        return true;

    }

    function delAll()
    {
        $dir_path = self::$cache_path . '/';
        $this->rmdirs($dir_path);
        return true;
    }

    //php删除指定目录下的的文件-用PHP怎么删除某目录下指定的一个文件？

    private function rmdirs($dir)
    {
        //error_reporting(0);    函数会返回一个状态,我用error_reporting(0)屏蔽掉输出

        //rmdir函数会返回一个状态,我用@屏蔽掉输出

        $dir_arr = scandir($dir);

        foreach ($dir_arr as $key => $val) {
            if ($val == '.' || $val == '..') {
            } else {

                if (is_dir($dir . '/' . $val)) {

                    if (@rmdir($dir . '/' . $val) == 'true') {
                    }    //去掉@您看看

                    else

                        $this->rmdirs($dir . '/' . $val);

                } else

                    unlink($dir . '/' . $val);

            }

        }

    }

}