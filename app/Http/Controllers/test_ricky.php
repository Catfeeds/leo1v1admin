<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

require_once  app_path("Libs/Pingpp/init.php");

class test_ricky extends Controller
{
    var $check_login_flag =true;

    public function get_user_list(){
        #分页信息
        $page_info= $this->get_in_page_info();
        #排序信息
        list($order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"userid desc");

        #输入参数
        list($start_time, $end_time)=$this->get_in_date_range_day(0);
        $userid=$this->get_in_userid(-1);
        $grade=$this->get_in_el_grade();
        $gender=$this->get_in_el_gender();
        $query_text=$this->get_in_query_text();

        $ret_info=$this->t_student_info->get_test_list($page_info, $order_by_str,  $grade );

        foreach($ret_info["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
            $item["testv"]="xxx";
        }
        //sleep(3);
        //dd($this->html_power_list);

        return $this->pageView(__METHOD__, $ret_info,[
            "message" =>  "cur usrid:".$userid,
        ]);
    }

    public function get_count() {
        $count = file_get_contents("http://p.admin.leo1v1.com/test_ricky/get_test_lesson_count");
        dd($count);
    }

    public function get_test_lesson_count() {
        $start_time = strtotime("2018-1-1");
        $end_time = strtotime('2018-1-9');
        $count = $this->t_lesson_info_b3->get_test_lesson_count($start_time, $end_time);
        $info = [];
        $i = 0;
        foreach($count as $item) {
            $order = $this->t_order_info->get_not_order($item['userid']);
            if (!$order) {
                //echo $item['userid']." ".$item['nick']."<br/>";
                $info[$i]['userid'] = $item['userid'];
                $info[$i]['nick'] = $item['nick'];
            }

        }
        return $this->output_succ(['data' => $info]);
    }

}