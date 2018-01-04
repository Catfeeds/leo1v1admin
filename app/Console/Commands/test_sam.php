<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_sam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_sam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test_sam';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $ret_info = $task->t_order_refund->get_2017_11_refund_info();
        
        $ret_file_name = \App\Helper\Utils::download_txt("sam_2017_11_refund_info",
        $ret_info,
        ["银行账号","卡号","卡的类型","省份","城市","支行"],
        ['bank_account','bankcard','bank_type','bank_province','bank_city','bank_address']);

        dd($ret_file_name);
        $path = '/home/ybai/test_sam.txt';
        //$path = '/home/sam/admin_yb1v1/a.txt';
        $fp = fopen($path,"a+");
        //dd($fp);
        foreach ($ret_info as $key => $value) {
            if($value['bankcard'] == ' '){

            }else{
                fwrite($fp, @$value['bank_account']);//1
                fwrite($fp, '   ');
                fwrite($fp, @$value['bankcard']);//1
                fwrite($fp, '   ');
                fwrite($fp, @$value['bank_type']);//2
                fwrite($fp, '   ');
                fwrite($fp, @$value['bank_province']);//2
                fwrite($fp, '   ');
                fwrite($fp, @$value['bank_city']);//2
                fwrite($fp, '   ');
                fwrite($fp, @$value['bank_address']);//2
                fwrite($fp, "\n");
            }
            
        }
        fclose($fp);

        /*
        $ret_info = $task->t_student_info->get_all_student_id();  
        foreach ($ret_info as $key => $value) {
            # code...
            $userid = $value['userid'];
            $phone  = $value['phone'];
            $lesson_time = $task->t_lesson_info->get_first_lesson($userid);
            if(!$lesson_time){
                $lesson_time = 0;
            }
            $test_subject = $task->t_test_lesson_subject->get_subject_only_once($userid);

            if(strpos($value['phone_location'], "黑龙江") !== false || strpos($value['phone_location'], "内蒙古") !== false){

                $location = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                $cor = substr($value['phone_location'],9,strlen($value['phone_location']));
            }else if($value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '北京U友'  || $value['phone_location'] == '辽宁U友'){
                $location = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                $cor = "其它";

            }else if( strpos($value['phone_location'], "普泰") !== false ||strpos($value['phone_location'], "京东") !== false 
                    || $value['phone_location'] == "鹏博士" || $value['phone_location'] == '' 
                   || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' 
                   || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' 
                   || $value['phone_location'] == '阿里通信'  || $value['phone_location'] == '小米移动' ){
                $location = "其它";
                $cor = "其它";
            }else{
                $location = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                $cor = substr($value['phone_location'],6,strlen($value['phone_location']));
            }

            $origin_info = $task->t_seller_student_origin->get_origin_by_userid($userid);
            if($origin_info){
                if(strpos($origin_info,'-') !== false){
                    $ret = explode("-",$origin_info);
                    $three_origin  = @$ret[1];
                    $two_origin    = @$ret[0];
                }else{
                    $ret = explode("—",$origin_info);
                    $three_origin  = @$ret[1];
                    $two_origin    = @$ret[0];
                }
                
            }else{
                $three_origin = '';
                $two_origin = '';
            }
            $origin_count = $task->t_seller_student_origin->get_count_origin($userid);
            $cc_called_count = $task->t_tq_call_info->get_cc_called_count_total($phone);
            $return_publish_count = $task->t_test_subject_free_list->get_return_publish_count($userid);
            $data = [
                "userid"        => $userid,
                "add_time"      => $value['reg_time'],
                "lesson_time"   => $lesson_time,
                "grade"         => $value['grade'],
                "subject"       => $test_subject['subject'],
                "pad"           => $value['has_pad'],
                "location"      => $location,
                "cor"           => $cor,
                'three_origin'  => $three_origin,
                'two_origin'    => $two_origin,
                'origin_count'  => $origin_count,
                'cc_called_count' => $cc_called_count,
                'return_publish_count' => $return_publish_count,
            ];
            $ret_s = $task->t_student_call_data->check($userid);
            if($ret_s){
                echo "update".$userid;
                echo "\n";
                $ret = $task->t_student_call_data->field_update_list($userid,$data);
            }else{
                echo "insert".$userid;
                echo "\n";
                $ret = $task->t_student_call_data->row_insert($data);
            }
        }
        

        

        $ret_info = $task->t_student_call_data->get_all_list();
        $path = '/home/ybai/test_sam.txt';
        //$path = '/home/sam/test_sam.txt';
        $fp = fopen($path,"a+");
        //$location = '/home/sam/location.txt';
        //$lo = fopen($location,"a+");
        //dd($fp);

        $arr_location = [];
        $l = 0;
        $arr_cor = [];
        $c = 0;
        $arr_three = [];
        $t = 0;
        $arr_two   = [];
        $w = 0;
        foreach ($ret_info as $key => $value) {
                fwrite($fp, @$value['userid']);//1
                fwrite($fp, ',');
                fwrite($fp, @$value['add_time']);//1
                fwrite($fp, ',');
                fwrite($fp, @$value['lesson_time']);//2
                fwrite($fp, ',');
                fwrite($fp, @$value['grade']);//2
                fwrite($fp, ',');
                fwrite($fp, @$value['subject']);//2
                fwrite($fp, ',');
                fwrite($fp, @$value['pad']);//2
                fwrite($fp, ',');
                if(isset($arr_location[$value['location']])){
                    fwrite($fp, @$arr_location[$value['location']]);//2
                }else{
                    $arr_location[$value['location']] = $l;
                    fwrite($fp, $l);//2
                    ++$l;
                }
                fwrite($fp, ',');

                if(isset($arr_cor[$value['cor']])){
                    fwrite($fp, @$arr_cor[$value['cor']]);//2
                }else{
                    $arr_cor[$value['cor']] = $c;
                    fwrite($fp, $c);//2
                    ++$c;
                }
                fwrite($fp, ',');

                if(isset($arr_three[$value['three_origin']])){
                    fwrite($fp, @$arr_three[$value['three_origin']]);//2
                }else{
                    $arr_three[$value['three_origin']] = $t;
                    fwrite($fp, $t);//2
                    ++$t;
                }
                
                fwrite($fp, ',');
                if(isset($arr_two[$value['two_origin']])){
                    fwrite($fp, @$arr_two[$value['two_origin']]);//2
                }else{
                    $arr_two[$value['two_origin']] = $w;
                    fwrite($fp, $w);//2
                    ++$w;
                }
                fwrite($fp, ',');
                fwrite($fp, @$value['origin_count']);//2
                fwrite($fp, ',');
                fwrite($fp, @$value['cc_called_count']);//2
                fwrite($fp, ',');
                fwrite($fp, @$value['return_publish_count']);//2
                fwrite($fp, "\n");
        }
        fclose($fp);
        $path_location = '/home/ybai/test_sam_location.txt';
        //$path_location = '/home/sam/test_sam_location.txt';
        $fl = fopen($path_location,"a+");
        foreach ($arr_location as $key => $value) {
            fwrite($fl, $key);
            fwrite($fl, ',');
            fwrite($fl, $value);
            fwrite($fl, "\n");
        }
        fclose($fl);
        //ybai
        $path_cor = '/home/ybai/test_sam_cor.txt';
        //$path_cor = '/home/sam/test_sam_cor.txt';
        $fc = fopen($path_cor,"a+");
        foreach ($arr_cor as $key => $value) {
            fwrite($fc, $key);
            fwrite($fc, ',');
            fwrite($fc, $value);
            fwrite($fc, "\n");
        }
        fclose($fc);
        $path_three = '/home/ybai/test_sam_three.txt';
        //$path_three = '/home/sam/test_sam_three.txt';
        $ft = fopen($path_three,"a+");
        foreach ($arr_three as $key => $value) {
            fwrite($ft, $key);
            fwrite($ft, ',');
            fwrite($ft, $value);
            fwrite($ft, "\n");
        }
        fclose($ft);

        $path_two = '/home/ybai/test_sam_two.txt';
        //$path_two = '/home/sam/test_sam_two.txt';
        $fw = fopen($path_two,"a+");
        foreach ($arr_two as $key => $value) {
            fwrite($fw, $key);
            fwrite($fw, ',');
            fwrite($fw, $value);
            fwrite($fw, "\n");
        }
        fclose($fw);

        */
        




        //$ret_info = $task->t_tq_call_info->get_all_info_by_cc();
        //$ret = $task->t_tq_call_info->get_all_info_by_cc_new();
        //$ret_test = $task->t_tq_call_info->get_all_info_by_cc_test();
        //$ret_info = $task->t_teacher_info->get_teacher_bank_info_new();
        
        
        
        //dd($ret_info);
        /*
        $ret_info = $task->t_tq_call_info->get_all_info_group_by_phone();
        foreach ($ret_info as $key => $value) {
            # code...
            $phone = $value['phone'];
            $total = $value['total'];
            $data = [
                'cc_no_called_count_new' => $total,
            ];
            $task->t_seller_student_new->update_cc_no_called_count_new($phone,$total);
        }
        */
        /*
        $is_full_time = 2;
        //$teacher_money_type = $task->get_in_int_val('teacher_money_type',-1);
        $teacher_money_type=-1;

        //$page_num = $task->get_in_page_num();
        //$task->switch_tongji_database();
        // $is_full_time = 1;  // 显示兼职老师
        // $task->switch_tongji_database();
        //$assistantid= $task->get_in_int_val("assistantid",-1);
        $assistantid = -1;

        //list($start_time,$end_time) = $task->get_in_date_range(0,0,0,[],3);
        $start_time = 1509465600;
        $end_time   = 1512057600;
        //权限写死,Erick要求
        //$adminid = $task->get_account_id();
        $adminid = 72;
        if(in_array($adminid,[72,967])){
            $show_all_flag=1;
        }else{
            $teacher_money_type=6;
            $show_all_flag=0;
        }
        $show_all_flag=1;
        
        $ret_info = $task->t_lesson_info_b2->get_lesson_info_teacher_tongji_jy($start_time,$end_time,$is_full_time,$teacher_money_type,$show_all_flag );
        $stu_num_all = $task->t_lesson_info_b2->get_lesson_info_teacher_tongji_jy_stu_num($start_time,$end_time,$is_full_time,$teacher_money_type);
        dd($start_time,$end_time);
        foreach($ret_info as &$item_list){
            $item_list['teacher_nick'] = $task->cache_get_teacher_nick($item_list['teacherid']);

            if($item_list['train_through_new_time'] !=0){
                $item_list["work_time"] = ceil((time()-$item_list["train_through_new_time"])/86400);
            }else{
                $item_list["work_time"] = 0;
            }

            if($item_list['valid_count']>0){
                $item_list['lesson_leavel_rate'] = number_format(($item_list['teacher_leave_lesson']/$item_list['valid_count'])*100,2);
                $item_list['lesson_come_late_rate'] = number_format(($item_list['teacher_come_late_count']/$item_list['valid_count'])*100,2);
                $item_list['lesson_cut_class_rate'] = number_format(($item_list['teacher_cut_class_count']/$item_list['valid_count'])*100,2);
                $item_list['lesson_change_rate'] = number_format(($item_list['teacher_change_lesson']/$item_list['valid_count'])*100,2);
            }else{
                $item_list['lesson_leavel_rate'] = 0;
                $item_list['lesson_come_late_rate'] = 0;
                $item_list['lesson_cut_class_rate'] = 0;
                $item_list['lesson_change_rate'] = 0;
            }

            E\Eteacher_money_type::set_item_value_str($item_list);
        }

        $all_item=["teacher_nick" => "全部" ];
        foreach ($ret_info as &$item) {
            foreach ($item as $key => $value) {
                if ((!is_int($key)) && (($key == "stu_num") || ($key =="valid_count") || ($key == "teacher_come_late_count") || ($key == "teacher_cut_class_count") || ($key =="teacher_change_lesson")||($key == 'teacher_leave_lesson') || ($key == "work_time") )) {
                    $all_item[$key]=(@$all_item[$key])+$value;
                }
            }
        }


        if($is_full_time == 1){
            $all_item['teacher_money_type_str'] = "兼职老师";
        }elseif($is_full_time == 2){
            $all_item['teacher_money_type_str'] = "全职老师";
        }


        $teacher_num = count($ret_info);
        if($teacher_num>0){
            $all_item['work_time'] = number_format($all_item['work_time']/$teacher_num,2);
            $all_item['lesson_leavel_rate'] = number_format($all_item['teacher_leave_lesson']/$all_item['valid_count']*100,2);
            $all_item['lesson_come_late_rate'] = number_format($all_item['teacher_come_late_count']/$all_item['valid_count']*100,2);
            $all_item['lesson_cut_class_rate'] = number_format($all_item['teacher_cut_class_count']/$all_item['valid_count']*100,2);
            $all_item['lesson_change_rate'] = number_format($all_item['teacher_change_lesson']/$all_item['valid_count']*100,2);
        }else{
            $all_item['work_time'] = 0;
            $all_item['lesson_leavel_rate'] = 0;
            $all_item['lesson_come_late_rate'] = 0;
            $all_item['lesson_cut_class_rate'] = 0;
            $all_item['lesson_change_rate'] = 0;
        }


        if($show_all_flag==1){
            array_unshift($ret_info, $all_item); 
        }
        $index_num=0;
        foreach($ret_info as &$p_item){
            $p_item["index_num"] = $index_num;
            $index_num++;

            if($p_item["teacher_nick"]=="全部"){
                $p_item["stu_num"]=$stu_num_all;
                $p_item["index_num"]=0;
            }
        }
        $path = '/var/www/admin.yb1v1.com/a.txt';
        //$path = '/home/sam/admin_yb1v1/a.txt';
        $fp = fopen($path,"a+");
        //dd($fp);
        foreach ($ret_info as $key => $value) {
            fwrite($fp, @$value['index_num']);//1
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_nick']);//2
            fwrite($fp, '   ');
            fwrite($fp, @$value['stu_num']);//3
            fwrite($fp, '   ');
            fwrite($fp, @$value['valid_count']);//4
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_come_late_count']);//5
            fwrite($fp, '   ');
            fwrite($fp, @$value['lesson_come_late_rate']);//6
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_cut_class_count']);//7
            fwrite($fp, '   ');
            fwrite($fp, @$value['lesson_cut_class_rate']);//8
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_change_lesson']);//9
            fwrite($fp, '   ');
            fwrite($fp, @$value['lesson_change_rate']);//10
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_leave_lesson']);//11
            fwrite($fp, '   ');
            fwrite($fp, @$value['lesson_leavel_rate']."%");//12
            fwrite($fp, '   ');
            fwrite($fp, @$value['teacher_money_type_str']);//13
            fwrite($fp, '   ');
            fwrite($fp, @$value['work_time'].'天');//14
            fwrite($fp, "\n");
        }
        fclose($fp);
        */
    }
}
