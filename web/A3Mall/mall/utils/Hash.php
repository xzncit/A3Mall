<?php

namespace mall\utils;

class Hash {

    public static function encrypt($name, $value, $salt = '!kQm*fF3pXe1Kbm%9') {
        if(empty($value)){
            return false;
        }
        
        $md5 = md5($salt.$name.$value);
        $string = implode("|", [$md5,$name,$value]);
        return str_replace("=", "", base64_encode($string));
    }

    public static function decrypt($data, $salt = '!kQm*fF3pXe1Kbm%9') {
        $string = base64_decode($data);
        $arr = explode('|', $string);
        if (count($arr) != 3) {
            return false;
        }
        
        list($md5,$name,$value) = $arr;
        $new_md5 = md5($salt.$name.$value);
        if($new_md5 == $md5){
            return ["name"=>$name,"value"=>$value];
        }
        
        return false;
    }

}
