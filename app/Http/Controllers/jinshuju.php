<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;


class jinshuju extends Controller
{

    var     $grade_map=[
        '200'=>201,
        '初二'=>202,
        '初三'=>203,
        '初一'=>201,
        '二年级'=>102,
        '高二'=>302,
        '高三'=>303,
        '高一'=>301,
        '八年级'=>202,
        '九年级'=>203,
        '六年级'=>201,
        '七年级'=>202,
        '预备八年级'=>202,
        '预备九年级'=>203,
        '预备六年级'=>201,
        '预备七年级'=>202,

        '三年级'=>103,
        '四年级'=>104,
        '未填写'=>100,
        '五年级'=>105,

        '小二'=>102,
        '小六'=>106,
        '小三'=>103,
        '小四'=>104,
        '小五'=>106,
        '小学'=>100,
        '小一'=>101,
        '学龄前'=>101,
        '一年级'=>101,
    ];

    var $subject_map= array(
        "语文"=> 1,
        "数学"=> 2,
        "英语"=> 3,
        "化学"=> 4,
        "物理"=> 5,
        "生物"=> 6,
        "政治"=> 7,
        "历史"=> 8,
        "地理"=> 9,
    );


    var $check_login_flag =false;
    public function post() {
        global $g_request;
        $input     = $g_request->input();
        $form      = $input["form"];
        $form_name = $input["form_name"];

        $entry     = $input["entry"];

        $phone=$entry["field_1"];
        $grade=$entry["field_2"];
        $subject=$entry["field_5"];
        //$field_4=$entry["field_4"];
        $has_pad=$entry["field_4"];
        $origin=$entry["field_6"];
        //$nick=$entry["creator_name"];
        $nick="";
        $add_time=$entry["created_at"];

        $this->add( $phone,$nick, $origin, $grade   , $subject, $has_pad, $add_time);

    }

    public function add( $phone,$nick, $origin, $grade   , $subject, $has_pad,$add_time  ) {

        \App\Helper\Utils::logger("  LOG:: $phone,$nick, $origin, $grade   , $subject, $has_pad,$add_time ");
        $add_time=strtotime( $add_time);
        if (strpos( $has_pad , "iPad" )!== false ) {
            $has_pad=1;
        }else if (strpos( $has_pad , "安卓" ) !== false ) {
            $has_pad=2;
        }else{
            $has_pad=0;
        }

        if (isset($this->grade_map[$grade] ))  {
            $grade = $this->grade_map[$grade] ;
        }

        if (isset($this->subject_map [$subject] ))  {
            $subject = $this->subject_map[$subject] ;
        }


        /*
        $this->t_seller_student_info->add_or_add_to_sub(
            $nick, $phone, $grade, $origin, $subject, $has_pad, "", "" ,"",
            $add_time,true
        );
        */
        $this->t_seller_student_new->book_free_lesson_new($nick,$phone,$grade,$origin,$subject,$has_pad);
        return $this->output_succ();
    }


}