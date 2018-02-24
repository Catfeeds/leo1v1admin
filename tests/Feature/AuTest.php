<?php

use App\Models\t_login_log;
use App\Enums as E;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuTest extends TestCase
{
    public function login() {
        echo "test login\n";
        $response = $this->get("/login/get_verify_code");
        $response->assertSessionHas("verify");

        $code = session("verify");
        $response1 = $this->json("POST","/login/login",[
            "account" => \App\Helper\Config::get_test_username(),
            // "password" => md5("xxxdfa") ,
            "password" => md5( \App\Helper\Config::get_test_password() ),
            "seccode" => $code,
        ]);
        // var_dump($response1);

        $response1->assertJson( ["ret"=>0] );

        // var_dump($response1);

    }

    public function test_control() {
        echo __METHOD__. "\n";
        // $this->get('/test_control/test')->assertSee("succ");
    }
    public function  test_nologin() {

        echo __METHOD__. "\n";
        $response=$this->get('')->assertSee("html");
        $response->assertSee("登录");

        $response1=$this->get('/')->assertSee("html");
        $response1->assertSee("登录");

    }
    public function test_url_0 () {
        echo "check all funtion  ...\n ";
    }
    public function test_url_1 () {

        echo __METHOD__. "\n";
        /**  var   self */

        $this->login();



        /*
        $url_map=\App\Helper\Config::get_url_power_map();
        foreach ($url_map as $url => $v ) {
            if(\App\Http\NewRouteConfig::check_is_new_url($url) ) {
                $this->visit($url);
            }
        }
        */

        /*
        $e=$this->visit('/small_class/')->see("html");
        $e->see("开课时间");

        $e=$this->visit('user_manage/all_users');
        $e->see("家长姓名");
        $e=$this->visit('user_manage/index');
        $e->see("家长姓名");
        */

        /*
        $e=$this->visit('pic_manage/pic_info');
        $e->see("图片名称");
        */
        /*

        $e=$this->visit('user_manage/contract_list');
        $e->see("学员姓名");


        $e=$this->visit('/user_book/phone_user_list');
        $e->see("手机号");
        */


    }

    public function test_url () {
        echo __METHOD__. "\n";
        /**  var   self */
        $this->login();
        echo "断点\n";
        $response = $this->get('/main_page/assistant');
        $response->assertSee('排名');
        echo "断点2\n";
        $this->get('/user_manage_new/stu_all_info')->assertSee('老师');
        $this->get('/notice/sms_stu_register')->assertStatus(200);
        $this->get('/tea_manage/lesson_list')->assertSee('课程id');
        $this->get('/seller_student_new/seller_student_list_all')->assertSee('个人信息');
        $power_list= json_decode(session("power_list"),true);
        $ctl=new  \App\Http\Controllers\index();
        $this->assertSame(true,$ctl->check_power(E\Epower::V_LESSON_MONITOR),"power error");
        $this->get('/supervisor/monitor')->assertSee('服务器');
        $this->get('/seller_student_new/seller_student_list')->assertStatus(200);
        $this->get('/main_page/seller')->assertStatus(200);
        $this->get('/seller_student/student_list2')->assertSee('手机号');
        $this->get('/ss_deal/seller_noti_info')->assertStatus(200);
        $this->get('/seller_student/student_sub_list')->assertSee('手机号');
        $this->get('/seller_student/test_lesson_list')->assertSee('手机号');
        $this->get('/stu_manage?sid=50314')->assertStatus(200);
        $this->get('/tongji/user_count')->assertStatus(200);
        $this->get('/seller_student_new2/test_lesson_plan_list')->assertStatus(200);
        $this->get('/human_resource/index_new')->assertStatus(200);
    }

    public function test_common_new() {

        echo __METHOD__. "\n";
        $this->get('/common_new/get_env')->assertSee('testing');
    }
    public function test_book_free_lesson() {
        $this->get('/common_ex/book_free_lesson?phone=15601830297&grade=201')->assertStatus(200);
        $this->get('/common_ex/book_free_lesson?phone=12601830298&grade=201')->assertStatus(200);
        echo "test_book_free_lesson\n";
    }

    public function test_jinshuju()
    {
        echo "test_jinshuju\n";
        $c=new \App\Http\Controllers\jinshuju();
    }

}
