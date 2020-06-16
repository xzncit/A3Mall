<?php
namespace mall\response;

class Response {

    public static function returnArray($msg="",$code=1,$data=[],$count=0){
        return ["code"=>$code, "msg"=>$msg,"count"=>$count,"data"=>$data];
    }

}