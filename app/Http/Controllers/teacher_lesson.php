<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_lesson extends Controller
{
    use CacheNick;

    public function lesson_count_list(){
        list($start_time,$end_time) = $this->get_in_date_range(-1,0,0,null,3);
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",-1);
        $start_time = strtotime("2017-8-1");
        $end_time= strtotime("2017-9-1");
        $this->switch_tongji_database();
        $ret_list = $this->t_lesson_info_b3->get_tea_lesson_count_list($start_time,$end_time,$teacher_money_type);

        echo "姓名|工资类型|等级|所带年级|所带科目|累计课时|学生数|课程数";
        echo "<br>";
        foreach($ret_list['list'] as $item){
            E\Elevel::set_item_value_str($item);
            E\Eteacher_money_type::set_item_value_str($item);
            $grade_str = E\Egrade::idlist2namelist($item['grade']);
            $subject_str = E\Esubject::idlist2namelist($item['subject']);
            $lesson_total=$item['lesson_count']/100;

            echo $item['realname']."|".$item['teacher_money_type_str']."|".$item['level_str']."|".$grade_str."|".$subject_str
                                  ."|".$lesson_total."|".$item['stu_num']."|".$item['count'];
            echo "<br>";
        }


        // return $this->Pageview(__METHOD__,$ret_list );
    }

}