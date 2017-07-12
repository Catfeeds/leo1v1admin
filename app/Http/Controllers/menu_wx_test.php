<?php
namespace App\Http\Controllers;
include(app_path("Libs/LaneWeChat/lanewechat.php"));

class  menu_wx_test extends Controller
{
    public function index() {
        $menuList = array(

                array('id'=>'1', 'pid'=>'0', 'name'=>'关于LEO', 'type'=>'', 'code'=>''),

                array('id'=>'2', 'pid'=>'0', 'name'=>'0元试听', 'type'=>'', 'code'=>'http://www.leo1v1.com/market/index.html'),

                array('id'=>'3', 'pid'=>'0', 'name'=>'精品讲座', 'type'=>'', 'code'=>'https://m.qlchat.com/topic/270000274258224.htm?preview=Y&intoPreview=Y'),

                array('id'=>'4', 'pid'=>'1', 'name'=>'活动与反馈', 'type'=>'click', 'code'=>'activity'),

                array('id'=>'5', 'pid'=>'1', 'name'=>'学生端使用手册', 'type'=>'click', 'code'=>'student'),
                array('id'=>'6', 'pid'=>'1', 'name'=>'家长端下载手册', 'type'=>'click', 'code'=>'parent')

            );

        $result = \LaneWeChat\Core\Menu::setMenu($menuList);
        dd($result);

    }
}