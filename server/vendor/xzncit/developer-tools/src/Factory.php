<?php
declare(strict_types=1);

namespace xzncit;

use xzncit\core\Config;
use xzncit\core\exception\ClassNotFoundException;
use xzncit\core\exception\ConfigNotFoundException;

/**
 * Class Factory
 * @package xzncit
 * @method static \xzncit\wechat\Wechat Wechat(array $config)
 * @method static \xzncit\mini\MiniProgram MiniProgram(array $config)
 * @method static \xzncit\microapp\MicroApp MicroApp(array $config)
 * @method static \xzncit\payment\Payment Payment(array $config)
 * @method static \xzncit\sms\Sms Sms(array $config)
 * @method static \xzncit\delivery\Delivery Delivery(array $config)
 */
class Factory {

    /**
     * Current version of program
     * @var string
     */
    public static $version = "0.3.2";

    /**
     * @param $name
     * @param array $options
     * @return object
     * @throws ClassNotFoundException
     * @throws ConfigNotFoundException
     */
    public static function create($name,array $options){
        $obj = "\\xzncit\\" . (substr($name,0,4)=="Mini" ? "mini" : strtolower($name)) . "\\" . $name;
        if(!class_exists($obj)){
            throw new ClassNotFoundException("class [$name] does not exist",0);
        }

        if(empty($options)){
            throw new ConfigNotFoundException("config does not exist",0);
        }

        Config::init();
        return new $obj($options);
    }

    /**
     * @return string
     */
    public static function getRootPath(){
        $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
        $filePath = $reflection->getFileName();
        return str_replace(substr($filePath,stripos($filePath,"vendor")),'',$filePath);
    }

    /**
     * @param $name
     * @param $arguments
     * @return object
     * @throws ClassNotFoundException
     * @throws ConfigNotFoundException
     */
    public static function __callStatic($name, $arguments){
        return self::create($name, ...$arguments);
    }

}
