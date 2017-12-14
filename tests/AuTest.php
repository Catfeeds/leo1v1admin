<?php

use App\Models\t_login_log;
use App\Enums as E;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuTest extends TestCase
{
    public function login() {
        echo "test login\n";
        $this->visit("/login/get_verify_code");
        $this->assertSessionHas("verify");

        $code = session("verify");
        $this->json("POST","/login/login",[
            "account" => \App\Helper\Config::get_test_username(),
            "password" => md5( \App\Helper\Config::get_test_password() ),
            "seccode" => $code,
        ]);


    }

    public function test_control() {
        echo __METHOD__. "\n";
        $e=$this->visit('/test_control/test')->see("succ");
    }
    public function  test_nologin() {

        echo __METHOD__. "\n";
        $e=$this->visit('')->see("html");
        $e->see("登录");

        $e=$this->visit('/')->see("html");
        $e->see("登录");

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
        //$this->withSession(["acc"=>"jim","power_list"=>"{\"301\":true}"]);
        $e=$this->visit('/main_page/assistant');
        $e->see("排名");
        $e=$this->visit('/user_manage_new/stu_all_info');
        $e->see("老师");



        $e=$this->visit('/notice/sms_stu_register');

        $this->seeJsonSuccess();

        $e=$this->visit('/tea_manage/lesson_list');
        $e->see("课程id");

        $e=$this->visit('/seller_student_new/seller_student_list_all');
        $e->see("个人信息");
        $e=$this->visit('/user_manage_new/money_contract_list');
        $e->see("上课时间");




        $power_list= json_decode(session("power_list"),true);


        $ctl=new  \App\Http\Controllers\index();
        $this->assertSame(true,
                          $ctl->check_power(E\Epower::V_LESSON_MONITOR),
                          "power error");

        $e=$this->visit('/supervisor/monitor');
        $e->see("服务器");


        $e=$this->visit('/seller_student_new/seller_student_list');

        $e=$this->visit('/main_page/seller');


        $e=$this->visit('/seller_student/student_list2');
        $e->see("手机号");

        $e=$this->visit('/ss_deal/seller_noti_info');
        $e->seeJsonSuccess();

        $e=$this->visit('/seller_student/student_sub_list');
        $e->see("手机号");
        $e=$this->visit('/seller_student/test_lesson_list');
        $e->see("手机号");

        $e=$this->visit('/stu_manage?sid=50314');
        $e=$this->visit('/tongji/user_count');
        $e=$this->visit('/seller_student_new2/test_lesson_plan_list');
        $e=$this->visit('/human_resource/index_new');
    }

    public function test_common_new() {

        echo __METHOD__. "\n";
        $e=$this->visit('/common_new/get_env');
        $e->see("testing");


    }
    public function test_book_free_lesson() {
        $e=$this->visit('/common_ex/book_free_lesson?phone=15601830297&grade=201');
        $e=$this->visit('/common_ex/book_free_lesson?phone=12601830298&grade=201');
        echo "test_book_free_lesson\n";

    }

    public function test_jinshuju()
    {
        echo "test_jinshuju\n";
        $c=new \App\Http\Controllers\jinshuju();
    }

}
