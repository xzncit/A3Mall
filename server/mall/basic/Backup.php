<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\basic;

use mall\utils\Tool;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Db;

class Backup {

    public function getDir(){
        return Tool::getRootPath() . "runtime/backup";
    }

    public function getDirFile(){
        $path = $this->getDir();
        $fileArray = glob($path . '/*.sql');
        return is_array($fileArray) ? $fileArray : [];
    }

    public function deleteFile($data=null){
        $path = $this->getDir();
        if(is_array($data)){
            foreach($data as $file){
                $filePath = $path . '/' . $file;
                file_exists($filePath) && unlink($filePath);
            }

            return true;
        }else if(is_string($data)){
            $file = $path . '/' . $data;
            return file_exists($file) && unlink($file);
        }

        return false;
    }

    /**
     * 导入备份
     */
    public function import($file="",$setp=null){
        $path = $this->getDir() . '/' . $file;
        if(!file_exists($path)){
            throw new \Exception("导入失败，文件不存在");
        }

        $data = Cache::remember('backup', function () use ($path) {
            $sql = file_get_contents($path);
            $sql = str_replace("\r", "\n", $sql);
            $sql = explode(";\n", $sql);
            Cache::tag('backup_data', 'backup');
            return $sql;
        });

        if(is_null($setp)){
            foreach($data as $val){
                !empty(trim($val)) && substr(ltrim($val),0,3) != '-- ' && Db::query($val);
            }
            Cache::delete('backup');
        }else if(array_key_exists($setp, $data)){
            $sql = trim($data[$setp]);
            Db::query($sql);
        }

        return count($data);
    }

    public function export(){
        $tables = Db::query("SHOW TABLES");
        $sql = "";
        foreach($tables as $value){
            $sql .= $this->exportTable(current(array_values($value)));
        }

        $fileInfo  = "-- A3Mall SQL Dump\n";
        $fileInfo .= "-- VERSION - " . Config::get("version.version") . "\n";
        $fileInfo .= "-- date：" . date("Y-m-d H:i:s") . ";\n\n";
        //$fileInfo .= 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";' . "\n";
        //$fileInfo .= "SET AUTOCOMMIT = 0;\n";
        //$fileInfo .= "START TRANSACTION;\n";
        //$fileInfo .= 'SET time_zone = "+00:00";' . "\n\n";
        $fileInfo .= $sql;
        //$fileInfo .= "COMMIT;\n";
        $fileInfo .= "-- the end of backup";
        $path = $this->getDir();
        return file_put_contents($path . '/' . date("YmdHis").".sql",$fileInfo);
    }

    private function exportTable($table=""){
        $sql = "DROP TABLE IF EXISTS `$table`;\n";
        $result = Db::query("SHOW CREATE TABLE $table");
        $sql .= $result[0]['Create Table'] . ";\n\n";

        $data = Db::table($table)->select()->toArray();

        if(empty($data[0])){
            return $sql;
        }

        $fields = '`'.implode("`,`",array_keys($data[0])).'`';
        $sql .= 'INSERT INTO `'.$table.'`('.$fields.') VALUES ';
        foreach($data as $value){
            $sql .= "(";
            $sql .= "'" . implode("','",array_map('addslashes',array_values($value))) . "'";
            $sql .= "),";
        }

        $sql = rtrim($sql,',') . ";\n\n\n\n";

        return $sql;
    }
}