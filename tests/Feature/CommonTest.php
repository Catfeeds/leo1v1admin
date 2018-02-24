<?php
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommonTest extends TestCase
{

    public function test_utf8_err() {
        echo __METHOD__. "\n";
        $str=hex2bin (substr( bin2hex("信息"), 2));
        $response = $this->get("/?xx=$str")->assertJson( ["ret"=>-8002] );
    }
}
