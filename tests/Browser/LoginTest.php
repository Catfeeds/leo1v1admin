<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('后台登录')
                    ->value("#id_account", "jim")
                    ->value("#id_password", "142857")
                    ->press("登录")
                    ->visit("/");


                $browser->visit("/user_manage/all_users")
                     ->press("ALL")
                     ->pause("4000")
                     ->select("id_grade", 101)
                     ->select("id_grade", 102)
                     ->click(".td-info")
                     ->pause(500);

                $browser->click(".bootstrap-dialog-body .opt-user");

                $browser->click(".bootstrap-dialog-header .close"); // 关闭模态框

                $browser->visit("/tea_manage/lesson_list")
                     ->pause(400)
                     ->press("ALL")
                     -pause(5000);

        });


    }
}
