<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            /*
              $browser->visit('/')
                    ->pause(2000)
                    ->assertSee('后台登录');
            */

                            // 页面 url, 是否有All按钮, select 选择框
                // 模板 ["url" => "", "select" => ["name" => "", "value" => ""]]
                /*
                $pages = [
                    ["url" => "/user_manage/all_users", "all" => true, "select" => ["name" => "id_grade", "value" => 102], "click" => ".td-info"],
                    ["url" => "human_resource/index_new", "select" => ["name" => "id_teacher_money_type", "value" => 0], "click" => ".opt-freeze-list"],
                    ["url" => "/authority/manager_list", "select" =>["name" => "id_call_phone_type", "value" => "2"]]
                ];
                
                foreach($pages as $item) {
                    $browser->visit($item["url"])->pause(5000);
                    if (isset($item["all"]))
                        $browser->press("ALL");
                    //$browser->select($item["select"]['name'], $item["select"]["value"]);
                    if (isset($item["click"]) && $item["click"])
                        $browser->click($item["click"]);
                    //$browser->pause(5000);
                    }*/

                    /*       $browser->visit("/user_manage/all_users")
                     ->press("ALL")
                     ->select("id_grade", 101)
                     ->select("id_grade", 102)
                     ->click(".td-info")
                     ->pause(500);
                /*

                //$browser->click(".bootstrap-dialog-body .opt-user");

                $browser->click(".bootstrap-dialog-header .close"); // 关闭模态框

                $browser->visit("/tea_manage/lesson_list")
                     ->press("ALL")
                     ->pause(2000);
                */

        });
    }
}
