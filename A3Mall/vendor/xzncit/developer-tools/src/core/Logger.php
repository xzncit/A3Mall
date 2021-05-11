<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core;

use Monolog\Logger as Log;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\JsonFormatter;

class Logger {

    private static $instance;
    private $log;

    private function __construct(){
        $log = Config::get("log");
        $logger = new Log($log["name"]);

        $streamHandler = new StreamHandler($log["path"], Log::DEBUG);
        $streamHandler->setFormatter(( $log["type"] == "line" ? new LineFormatter() : new JsonFormatter() ));
        $logger->pushHandler($streamHandler);
        $this->log = $logger;
    }

    public static function getInstance(){
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getMonolog(){
        return $this->log;
    }

    /**
     * @param string  $message
     * @param mixed[] $context
     */
    public function debug($message,$content=[]){
        $this->log->debug($message,$content);
    }

    /**
     * @param string  $message
     * @param mixed[] $context
     */
    public function emergency($message,$content=[]){
        $this->log->emergency($message,$content);
    }

    /**
     * @param string  $message
     * @param mixed[] $context
     */
    public function alert($message,$content=[]){
        $this->log->alert($message,$content);
    }

    /**
     * @param string  $message
     * @param mixed[] $context
     */
    public function critical($message,$content=[]){
        $this->log->critical($message,$content);
    }

    /**
     * @param string  $message
     * @param mixed[] $context
     */
    public function error($message,$content=[]){
        $this->log->error($message,$content);
    }

    /**
     * @param string  $message
     * @param mixed[] $context
     */
    public function warning($message,$content=[]){
        $this->log->warning($message,$content);
    }

    /**
     * @param string  $message
     * @param mixed[] $context
     */
    public function notice($message,$content=[]){
        $this->log->notice($message,$content);
    }

    /**
     * @param string  $message
     * @param mixed[] $context
     */
    public function info($message,$content=[]){
        $this->log->info($message,$content);
    }

    public function write($message,$content=[],$method=""){

        $this->log->$method($message,$content);
    }
}