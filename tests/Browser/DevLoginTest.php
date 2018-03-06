<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DevLoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            // 可以通过调用driver(Facebook\WebDriver\Remote\RemoteWebDriver)来实现更复杂的逻辑
            //$browser->driver->manage()->window()->maximize();
            //$browser->driver->get("https://www.baidu.com");
            $browser->maximize(); // 浏览器窗口最大化 解决元素不可见问题(element not visible)

                $url = "http://dev.admin.leo1v1.com";
                $browser->driver->get($url);
                $browser->value("#id_account", "jim")
                     ->value("#id_password", "142857")
                     ->press("登录")
                     ->visit($url)->pause(1000);

                // 页面url click页面元素的单击
                // 模块 ["url" => "", "click" => ""],
                $pages = [
                    // cc相关页面
                    //["url" => "/main_page/seller", "click" => ".opt-no-order"], // 排行榜
                    ["url" => "/seller_student_new/seller_student_list_all", "click" => ".opt-edit-new_new_two"], // 所有用户
                    ["url" => "/seller_student_new/assign_member_list", "click" => ".opt-return-back-list"], // 分配例子-主管
                    ["url" => "/seller_student_new/ass_master_seller_student_list", "click" => ".opt-edit-new_new_two"], // 转介绍例子

                    // cr相关页面
                    ["url" => "/user_manage/ass_random_revisit", "click" => ".opt-return-back-lesson"], // 助长满意度回访
                    ["url" => "/user_manage/ass_archive", "click" => ".opt-edit-new_new_two"], // 学员档案
                    ["url" => "/user_manage/ass_count", "click" => ".opt-return-back-list"], // 回访统计
                    ["url" => "/user_manage/student_school_score_stat", "click" => ".td-info"], // 学生在校成绩统计
                    ["url" => "/user_manage/stu_all_teacher_all", "click" => ".td-info"], // 在读学员老师列表``
                    ["url" => "/human_resource/index_ass", "click" => ".opt-account-number "], // 老师档案
                    ["url" => "/human_resource/specialty", "click" => ".opt-edit-info"], // 老师特长

                    // 师资部
                    ["url" => "/human_resource/reaearch_teacher_lesson_list", "click" => ".td-info"],
                    ["url" => "/channel_manage/admin_channel_manage", "click" => ".opt-assign-channel"],

                    // 教研部
                    ["url" => "/textbook_manage/get_subject_grade_textbook_info", "click" => ".opt-edit"], // 教材版本管理
                    ["url" => "/teacher_level/get_teacher_level_quarter_info_new", "click" => ".opt-advance-require"], // 老师晋升申请
                    ["url" => "/human_resource/reaearch_teacher_lesson_list_research", "click" => ".opt-set-grade-range"], // 教研老师信息

                    // 服务管理
                    ["url" => "/user_manage_new/account_list", "click" => ".opt-set-userid"], // 账号登录管理
                    ["url" => "/user_manage/parent_archive", "click" => ".opt-edit"], // 家长档案
                    ["url" => "/user_manage/pc_relationship", "click" => ".opt-set-parentid"], // 家长 <> 学生
                    // ["url" => "", "click" => ""],
                    // ["url" => "", "click" => ""],
                    // ["url" => "", "click" => ""],
                    // ["url" => "", "click" => ""],
                    // ["url" => "", "click" => ""],
                    ["url" => "/user_manage/all_users", "click" => ".td-info"],
                    ["url" => "/human_resource/index_new", "click" => ".opt-freeze-list"],
                    ["url" => "/authority/manager_list", "click" => ".opt-ower-permission"] // 用户管理
                ];
                
                foreach($pages as $item) {
                    echo PHP_EOL."当前页面:".$item["url"];
                    $browser->visit($url.$item["url"])->pause(3000)->click($item["click"])->pause(300);
                    $text = $browser->text(".modal-header");
                    //$text = $browser->text(".bootstrap-dialog-title");
                }
                $browser->quit();
        });


    }
}
