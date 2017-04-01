<?php
/**
 * 示例模型类
 */

namespace app\modules\Home\Model;
use \LinkPHP\lib\Model;

class TestModel extends Model
{

    public $tb='test';

    function __construct() {
        parent::__construct();
    }


}