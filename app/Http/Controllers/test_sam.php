<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_sam  extends Controller
{
    use CacheNick;
    use TeaPower;

    
    
    

    


    function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        // 初始化curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $postUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // post提交方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        // 运行curl
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    
    

    public function test(){
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = $start_time + 86400;
        //$end_time   = time();
        $teacherid  = 392077;

        $total_count = $this->t_lesson_info_b2->get_teacher_lesson_total($teacherid,$start_time,$end_time);
        $consume_count = $this->t_teacher_spring->get_total($teacherid,$start_time,$end_time);

        $count = $total_count + 1 - $consume_count;
        if($count < 0 ){
            $count = 0;
        }

        dd($count);
    }


    public function test1(){
        $start_time = strtotime(date("Y-m-d",time()));
        //$end_time   = $start_time + 86400;
        $end_time   = time();
        $teacherid  = 392077;

        $total_count = $this->t_lesson_info_b2->get_teacher_lesson_total($teacherid,$start_time,$end_time);
        $consume_count = $this->t_teacher_spring->get_total($teacherid,$start_time,$end_time);

        $count = $total_count + 1 - $consume_count;
        if($count < 1 ){
            return $this->output_err("抽奖次数已用完!");
        }

        $rank = $this->t_teacher_spring->get_last_rank($start_time);
        if(!$rank){
            $rank = 0;
        }
        $result = 0;
        if($rank >= 0){
            $rank = $rank + 1;
            if($rank == 10 || $rank == 30 || $rank == 50
            || $rank == 70 || $rank == 90 || $rank == 110){
                $result = 1;
            }
        }
        
        $ret = $this->t_teacher_spring->row_insert([
            'teacherid' => $teacherid,
            'add_time'  => time(),
            'rank'      => $rank,
            'result'    => $result,
        ]);
        dd($result,$rank);
    }



    public function teacher_spring(){
        $start = "1517932800";
        $end   = "1519315200";

        
        for ($i=$start; $i <= $end ; ) { 
            $start_time = $i;
            $end_time   = $i + 86400;

            $day  = date("Y-m-d",$start_time);
            $ret = $this->t_teacher_spring->get_info($start_time,$end_time);
            $count = $this->t_teacher_spring->get_info_count($start_time,$end_time);
            echo "<div align='center'>";
            echo "<span >".$day."参与人次".$count."</span>";
            echo "<table align='center' border='1px solid red'>"; 
            echo "<th>获奖人姓名</th><th>手机号</th><th>时间</th><th>次数</th>";                    
            foreach ($ret as $key => $value) {
                $nick = $this->t_teacher_info->get_nick($value['teacherid']);
                echo "<td>";echo $nick;echo "</td>";
                $phone  = $this->t_teacher_info->get_phone($value['teacherid']);
                echo "<td>";echo $phone;echo "</td>";
                $time = date("Y-m-d H:i:s",$value['add_time']);
                echo "<td>";echo $time;echo "</td>";
                echo "<td>";echo $value['rank'];echo "</td>";
            }
            echo "</table>";
            echo "</div>";
            $i = $i + 86400;    
        }   

       
    }
    
}

