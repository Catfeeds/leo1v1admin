<?php

class CommonTest extends TestCase
{

    public function test_utf8_err() {
        $str=hex2bin (substr( bin2hex("信息"), 2));
        //$str="信息";
        $e=$this->visit("/?xx=$str");
        //json 有误
        $this->seeJsonRet(-8002);
        
    }
}
