<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core;
use Pimple\Container;


class Service extends Container {

    /**
     * @var array
     */
    private $bind = [];

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var array
     */
    public $config;

    /**
     * Service constructor.
     * @param array $config
     */
    public function __construct(array $config){
        $this->config = $config;
    }

    /**
     * @param $name
     * @return object
     */
    public function __get($name){
        if(!in_array($this->providers[$name],$this->bind)){
            $this->bind[] = $this->providers[$name];
            parent::register(new $this->providers[$name]());
        }

        return $this->offsetGet($name);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value){
        $this->offsetSet($name, $value);
    }

}