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
        $this->get("/login/get_verify_code");
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
        $e=$this->get('/test_control/test')->see("succ");
    }
    public function  test_nologin() {

        echo __METHOD__. "\n";
        $e=$this->get('')->see("html");
        $e->see("登录");

        $e=$this->get('/')->see("html");
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
                $this->get($url);
            }
        }
        */

        /*
        $e=$this->get('/small_class/')->see("html");
        $e->see("开课时间");

        $e=$this->get('user_manage/all_users');
        $e->see("家长姓名");
        $e=$this->get('user_manage/index');
        $e->see("家长姓名");
        */

        /*
        $e=$this->get('pic_manage/pic_info');
        $e->see("图片名称");
        */
        /*

        $e=$this->get('user_manage/contract_list');
        $e->see("学员姓名");


        $e=$this->get('/user_book/phone_user_list');
        $e->see("手机号");
        */


    }

    public function test_url () {

        echo __METHOD__. "\n";
        /**  var   self */

        $this->login();
        //$this->withSession(["acc"=>"jim","power_list"=>"{\"301\":true}"]);


        $e=$this->get('/main_page/assistant');
        $e->see("排名");
        $e=$this->get('/user_manage_new/stu_all_info');
        $e->see("老师");



        $e=$this->get('/notice/sms_stu_register');

        $this->seeJsonSuccess();

        $e=$this->get('/tea_manage/lesson_list');
        $e->see("课程id");

        $e=$this->get('/seller_student_new/seller_student_list_all');
        $e->see("个人信息");
        $e=$this->get('/user_manage_new/money_contract_list');
        $e->see("上课时间");




        $power_list= json_decode(session("power_list"),true);


        $ctl=new  \App\Http\Controllers\index();
        $this->assertSame(true,
                          $ctl->check_power(E\Epower::V_LESSON_MONITOR),
                          "power error");

        $e=$this->get('/supervisor/monitor');
        $e->see("服务器");


        $e=$this->get('/seller_student_new/seller_student_list');

        $e=$this->get('/main_page/seller');


        $e=$this->get('/seller_student/student_list2');
        $e->see("手机号");

        $e=$this->get('/ss_deal/seller_noti_info');
        $e->seeJsonSuccess();

        $e=$this->get('/seller_student/student_sub_list');
        $e->see("手机号");
        $e=$this->get('/seller_student/test_lesson_list');
        $e->see("手机号");

        $e=$this->get('/stu_manage?sid=50314');
        $e=$this->get('/tongji/user_count');
        $e=$this->get('/seller_student_new2/test_lesson_plan_list');
        $e=$this->get('/human_resource/index_new');


    }
    public function test_common_new() {

        echo __METHOD__. "\n";
        $e=$this->get('/common_new/get_env');
        $e->see("testing");


    }
    public function test_book_free_lesson() {
        $e=$this->get('/common_ex/book_free_lesson?phone=15601830297&grade=201');
        $e=$this->get('/common_ex/book_free_lesson?phone=12601830298&grade=201');
        echo "test_book_free_lesson\n";

    }

    public function test_jinshuju()
    {
        echo "test_jinshuju\n";
        $c=new \App\Http\Controllers\jinshuju();
    }

}
