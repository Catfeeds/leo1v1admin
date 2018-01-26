<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class seller_student_system_assign extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:seller_student_system_assign";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "系统分配例子";

    public function get_admin_info() {
        list($start_time, $end_time)=$this->task->get_in_date_range_day(0);
        \App\Helper\Utils::logger("deal :$start_time ,$end_time");

        $config=\App\Helper\Config::get_seller_new_user_day_count();

        $work_start_time_map=$this->task->t_admin_work_start_time-> get_today_work_start_time_map();
        $check_work_time= strtotime(date("Y-m-d 14:00:00"));
        $need_work_flag=  (time(NULL) > $check_work_time);

        //得到要处理的的人
        $tmp_admin_list=$this->task->t_manager_info->get_seller_list(E\Eseller_student_assign_type::V_SYSTEM_ASSIGN );
        //得到已经分配的数据
        $admin_assign_map= $this->task->t_seller_student_system_assign_log->get_admin_assign_count_info($start_time, $end_time);

        $left_new_count_all=0;
        $left_no_connected_count_all =0;
        $seller_max_new_count=0;

        $need_new_count_all=0;
        $assigned_new_count_all=0;

        $need_no_connected_count_all=0;
        $assigned_no_connected_count_all=0;

        $hold_config=\App\Helper\Config::get_seller_hold_user_count();

        $admin_list=[];
        foreach ($tmp_admin_list as $key=> $item){ //
            $adminid=$item["uid"];
            $seller_level=$item["seller_level"];
            $def_new_count=@$config[$seller_level];
            if (!$def_new_count){
                $def_new_count=0;
            }
            $assigned_new_count = @$admin_assign_map[$adminid]["new_count"];
            if (!$assigned_new_count){
                $assigned_new_count=0;
            }
            $assigned_no_connected_count = @$admin_assign_map[$adminid]["no_connected_count"];
            if (! $assigned_no_connected_count ){
                $assigned_no_connected_count=0;
            }

            //新例子
            $item["def_new_count"] = $def_new_count;
            if ( $def_new_count>$seller_max_new_count ) {
                $seller_max_new_count = $def_new_count;
            }
            $item["assigned_new_count"] = $assigned_new_count;

            $need_new_count_all+=$def_new_count;
            $assigned_new_count_all+= min( [$assigned_new_count, $def_new_count]);


            //未拨通例子重新分配的个数
            $def_no_connected_count= 5;
            $item["def_no_connected_count"] = $def_no_connected_count ;
            $item["assigned_no_connected_count"] = $assigned_no_connected_count ;

            $need_no_connected_count_all+=$def_no_connected_count;
            $assigned_no_connected_count_all+= min([ $assigned_no_connected_count, $def_no_connected_count  ]);
            //得到每个人上限
            $item["hold_count"]=$this->task->t_seller_student_new_b2->admin_hold_count($adminid);
            $item["max_hold_count"] = @$hold_config[$seller_level];
            \App\Helper\Utils::logger("$adminid:". $item["hold_count"]."." .$item["max_hold_count"]  );
            //$need_work_flag
            $add_flag= true;
            if ($need_work_flag) {
                $add_flag = isset($work_start_time_map[$adminid]);
            }
            if ($add_flag) {
                //不超上限
                $add_flag=($item["max_hold_count"] >$item["hold_count"]);
            }

            if ($add_flag)  {
                $admin_list[]=$item;
            }
        }

        return [
            "admin_list"                      => $admin_list,
            "left_no_connected_count_all"     => $need_no_connected_count_all-$assigned_no_connected_count_all ,
            "left_new_count_all"              => $need_new_count_all-$assigned_new_count_all,
            "need_no_connected_count_all"     => $need_no_connected_count_all,
            "need_new_count_all"              => $need_new_count_all,
            "assigned_no_connected_count_all" => $assigned_no_connected_count_all,
            "assigned_new_count_all"          => $assigned_new_count_all,
            "seller_max_new_count"            => $seller_max_new_count,
        ];
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function do_handle()
    {
        //$def_count=@$config[$seller_level];
        $ret_info=$this->get_admin_info();
        $left_no_connected_count_all=$ret_info["left_no_connected_count_all"];
        $left_new_count_all=$ret_info["left_new_count_all"];
        $admin_list=$ret_info["admin_list"];
        $seller_max_new_count = $ret_info["seller_max_new_count"];
        $new_ret_info= $this->assign_new( $left_new_count_all,$admin_list ,$seller_max_new_count );
        $no_connnected_ret_info=$this->assign_no_connected( $left_no_connected_count_all,$admin_list  );

        $this->task->t_seller_student_system_assign_count_log->row_insert([
            "logtime" => time(),
            "new_count"=> $new_ret_info["need_deal_count"],
            "need_new_count" => $ret_info["need_new_count_all"],
            "new_count_assigned" =>  $ret_info["assigned_new_count_all"] + $new_ret_info["assigned_count"],

            "no_connected_count"  =>$no_connnected_ret_info["need_deal_count"],
            "need_no_connected_count" =>  $ret_info["need_no_connected_count_all"],

            "no_connected_count_assigned" => $ret_info["assigned_no_connected_count_all"] + $no_connnected_ret_info["assigned_count"],

        ]);


    }
    public function   assign_no_connected ( $left_no_connected_count_all, $admin_list  ) {
        $need_deal_list=$this->task->t_seller_student_new_b2->get_need_new_assign_list(
            E\Etq_called_flag::V_1
        );
        $need_deal_count=count($need_deal_list);
        $old_need_deal_count=$need_deal_count;
        $assigned_count=0;
        if( $left_no_connected_count_all)  {

            shuffle ($need_deal_list);
            $start_deal_index=0;//random_int(0, $need_deal_count*2/3 );
            foreach( $admin_list as &$item ) {
                $assigned_no_connected_count=$item["assigned_no_connected_count"];
                $def_no_connected_count=$item["def_no_connected_count"];
                $opt_adminid= $item["uid"];
                for($i=$assigned_no_connected_count;$i<$def_no_connected_count;$i++ ) {
                    for($j=$start_deal_index; $j< $need_deal_count ;  $j++ ) {
                        $find_userid= @$need_deal_list[$j]["userid"];
                        if ( $find_userid && !$this->task->t_seller_student_system_assign_log->check_userid_adminid_existed( $find_userid, $opt_adminid  ) ) {

                            $assigned_count++;
                            $userid_list=[$find_userid];
                            $opt_type ="" ;
                            $opt_type=0;
                            $account="系统分配-未拨通例子";
                            $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $opt_adminid, $opt_type,$account);
                            $this->task->t_seller_student_system_assign_log->add(
                                E\Eseller_student_assign_from_type::V_1, $find_userid, $opt_adminid
                            );
                            unset($need_deal_list[$j]);
                            $start_deal_index=$j+1;
                            break;
                        }
                    }
                }
            }
        }

        return [
            "need_deal_count" =>$old_need_deal_count,
            "assigned_count" =>$assigned_count,
        ];
    }

    public function get_no_connected_item( ) {

    }

    public function assign_new( $left_new_count_all, $admin_list,$seller_max_new_count  )  {
        $need_deal_list=$this->task->t_seller_student_new_b2->get_need_new_assign_list(
            E\Etq_called_flag::V_0
        );
        $level_map=[];
        $need_deal_count=count($need_deal_list);
        $assigned_count=0;
        if ($left_new_count_all) {
            foreach ($need_deal_list as $user_info)  {
                $userid=$user_info["userid"];
                $origin_level=$user_info["origin_level"];
                if (isset($level_map[$origin_level]) ) {
                    $level_map[$origin_level]=[];
                }
                $level_map[$origin_level][$userid]=true;
            }

            $check_end_flag=false;
            for ($i=0;$i< $seller_max_new_count;$i++ ) { //第几轮

                foreach( $admin_list as &$item ) {
                    $assigned_new_count=$item["assigned_new_count"];
                    $seller_level=$item["seller_level"];
                    $def_new_count=$item["def_new_count"];
                    if ($i<$def_new_count // 在配额内
                        && $assigned_new_count <=$i //这一轮可以分配
                    ){
                        $find_userid=$this->get_assign_userid( $level_map ,$seller_level );
                        if($find_userid) {
                            $assigned_count++;
                            $userid_list=[$find_userid];
                            $opt_type ="" ;
                            $opt_adminid= $item["uid"];
                            $opt_type=0;
                            $account="系统分配-新例子";
                            $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $opt_adminid, $opt_type,$account);
                            $this->task->t_seller_student_system_assign_log->add(
                                E\Eseller_student_assign_from_type::V_0, $find_userid, $opt_adminid
                            );
                        }else{ //没有可分配的
                            $check_end_flag=true;
                            break;
                        }
                    }
                }

                if( $check_end_flag   )  {
                    break;
                }
            }
        }

        return [
            "need_deal_count" =>$need_deal_count,
            "assigned_count" =>$assigned_count,
        ];
    }

    public function get_assign_userid( &$level_map, $seller_level ){
        $seller_level_flag = floor( $seller_level/100);

        // 渠道S, A , B, C
        /*
        E\Eorigin_level
            0 => "未设置",
            1 => "S类",
            2 => "A类",
            3 => "B类",
            4 => "C类",
            90 => "T类",
            99 => "Y类",
            100 => "Z类",
        */

        switch ( $seller_level_flag ) {
        case 1 :  //S级:所有
            $origin_level_list=[1, 2, 3, 4] ; break;
        case 2 :  //A级
            $origin_level_list=[2,1,3,4 ]; break;
        case 3 : //B级:B,
            $origin_level_list=[3,2,4,1 ]; break;
        default: //其它:c
            $origin_level_list=[4,3,2,1 ]; break;
        }

        $find_level_map_item=null;
        foreach( $origin_level_list as $origin_level ) {
            if ( isset($level_map[$origin_level]) && count( $level_map[$origin_level])>0 ) {
                $find_level_map_item=  &$level_map[$origin_level];
            }
        }

        if (!$find_level_map_item){ // 检查其他
            foreach ( $level_map as  &$level_item  )  {
                if (count($level_item)>0 ) {
                    $find_level_map_item =&$level_item;
                }
            }
        }
        $find_userid=0;
        if ($find_level_map_item)  {
            foreach($find_level_map_item as  $userid => $value) {
                $find_userid= $userid;
                break;
            }
            unset($find_level_map_item[$find_userid])  ;
        }
        return $find_userid;
    }

}
