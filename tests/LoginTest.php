<?php

use App\Models\t_login_log;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{

    public function test_index()
    {
        echo "login test_index\n";
        $this->visit('/');
        $this->see("后台登录");
    }

    public function test_login()
    {
        echo "login test_login\n";
        $this->visit("/login/get_verify_code");
        $this->assertSessionHas("verify");


        /*
        $this->json("POST","/login/login",[
            "account" => \App\Helper\Config::get_test_username(),
            "password" => md5( \App\Helper\Config::get_test_password() ),
            "seccode" => "xx",
        ]);
        //$this->seeJson( ["ret"=>7001] );

        $this->visit('/');
        $this->see("后台登录");
        */


        $code = session("verify");
        $this->json("POST","/login/login",[
            "account" => \App\Helper\Config::get_test_username(),
            "password" => md5("xxxdfa") ,
            "seccode" => $code,
        ]);


        $this->seeJson( ["ret"=>7002] );
        $this->visit('/');
        $this->see("后台登录");


        $this->json("POST","/login/login",[
            "account" => \App\Helper\Config::get_test_username(),
            "password" => md5( \App\Helper\Config::get_test_password() ),
            "seccode" => $code,
        ]);

        $this->seeJson( ["ret"=>0] );
        $this->assertSessionHas("acc","jim");
        $this->assertGlobalSessionHas("acc","jim");

        $this->visit('/');
        $this->see("/supervisor/monitor");

        $this->json("POST","/login/logout",[]);

        //$this->assertSessionHasErrors("acc");

    }
    public function test_check_power() {
        echo "login test_check_power\n";
        $this->withSession(["acc"=>"jim","power_list"=>"{}"]);
        $this->visit("/supervisor/monitor");

        $this->see("没有权限");


    }

}
