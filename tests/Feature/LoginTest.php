<?php

use App\Models\t_login_log;
use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{

    public function test_index()
    {
        echo "login test_index\n";
        $response = $this->get('/');
        $response->assertStatus(200)
             ->assertSee('后台登录');
    }

    public function test_login()
    {
        echo "login test_login\n";
        //检测验证码
        $response1 = $this->get('/login/get_verify_code');
        $response1->assertSessionHas("verify");

        //模拟错误登录
        $code = session("verify");
        $response2 = $this->json("POST","/login/login",[
            "account" => \App\Helper\Config::get_test_username(),
            "password" => md5("xxxdfa") ,
            "seccode" => $code,
        ]);


        $response2->assertJson( ["ret"=>7002] );

        //检测后台登录界面
        $response3 = $this->get('/');
        $response3->assertStatus(200)
             ->assertSee('后台登录');


        /*//模拟成功登录
        $response4 = $this->json("POST","/login/login",[
            "account" => \App\Helper\Config::get_test_username(),
            "password" => md5( \App\Helper\Config::get_test_password() ),
            "seccode" => $code,
        ]);

        $response4->assertJson( ["ret"=>0] );
        $response4->assertSessionHas("acc","jim");
        $response4->assertGlobalSessionHas("acc","jim");*/

        // $response5 = $this->visit('/');
        // $response5->assertSee("/supervisor/monitor");

    }
    public function test_check_power() {
        echo "login test_check_power\n";
        $response = $this->withSession(["acc"=>"jim","power_list"=>"{}"])
                  ->get('/supervisor/monitor');

        $response->assertSee('没有权限');

    }

}
