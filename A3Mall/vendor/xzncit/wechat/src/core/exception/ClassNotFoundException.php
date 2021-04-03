<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\exception;

use Throwable;

class ClassNotFoundException extends \Exception {

    private $className;

    public function __construct($message = "", $class = "", Throwable $previous = null){
        $this->className = $class;
        parent::__construct($message, 0, $previous);
    }

    public function getClassName(){
        return $this->className;
    }

}