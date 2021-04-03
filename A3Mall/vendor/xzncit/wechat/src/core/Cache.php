<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Cache {

    private static $instance;
    private $cache;

    private function __construct(){
        $log = Config::get("cache");
        $this->cache = new FilesystemAdapter('', 0, $log["path"]);
    }

    public static function create(){
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get($name){
        if($this->has($name)){
            $value = $this->cache->getItem($name);
            return $value->get();
        }

        return null;
    }

    public function set($name,$value,$expires=3600){
        $item = $this->cache->getItem($name);
        $item->set($value);
        $item->expiresAfter($expires);
        $this->cache->save($item);
    }

    public function has($name){
        return $this->cache->hasItem($name);
    }

    public function delete($name){
        $this->cache->deleteItem($name);
    }

    public function clear(){
        $this->cache->clear();
    }

}