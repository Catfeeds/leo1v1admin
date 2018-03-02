<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class seller_new_count_day_gen extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:seller_new_count_day_gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天为每个人生成可抢例子数据';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function do_handle()
    {
        $config=\App\Helper\Config::get_seller_new_user_day_count();
        $admin_list=$this->task->t_manager_info->get_seller_list();
        $unallot_info=$this->task->t_test_lesson_subject->get_unallot_info();
        $all_uncall_count=$unallot_info["all_uncall_count" ] ;
        $add_count_ex=0;
        if ( $all_uncall_count>1000)  {
            $add_count_ex=4;
        }else if ( $all_uncall_count>500)  {
            $add_count_ex=2;
        }else if ( $all_uncall_count>200)  {
            $add_count_ex=1;
        }

        $seller_new_count_type=E\Eseller_new_count_type::V_DAY ;
        $value_ex=0;
        $check_base_time= strtotime( date("Y-m-d"));
        $check_start_time=  $check_base_time -8*86400;
        $check_end_time =  $check_start_time + 7*86400 +1;

        $start_time=strtotime(date("Y-m-d"), time(NULL));
        $end_time=$start_time+86400-1;

        $count=0;
        foreach($admin_list as $item ) {
            $adminid=$item["uid"];
            $seller_level=$item["seller_level"];
            $def_count=@$config[$seller_level];
            if ($def_count ) {
                $count = $def_count+ $add_count_ex;
            }

            $last_day_list=$this->task->t_seller_new_count-> get_list_for_check_work($adminid,$seller_new_count_type,
                                                         $check_start_time,$check_end_time);
            /*
            $modify_count=$this->check_last_day_list($last_day_list,$check_base_time);
            $count+=  $modify_count;
            */
            echo "adminid $adminid, $count \n";
            if ($count<0) {
                $count=0;
            }

            $existed_flag=$this->task->t_seller_new_count->check_adminid_seller_new_count_type_start_time (  $adminid, $seller_new_count_type, $start_time  );
            if (!$existed_flag) {
                $this->task->t_seller_new_count->add(
                    $start_time,$end_time, $seller_new_count_type,$count  ,$adminid,$value_ex);
            }
        }
    }

    public function check_last_day_list( $last_day_list, $base_time )  {
        foreach ( $last_day_list as &$item ) {
            $item["left_count"] = $item["count"] -$item["get_count"];
        }
        $check_count_fun= function (  $day) use( $base_time, $last_day_list  )  {
            $time= $base_time + $day *86400;
            if (  !isset ($last_day_list [$time]) ) {
                return true;
            }
            if ( $last_day_list [$time]["left_count"]>0 ) {
                return false;
            }else{
                return true;
            }
        };
        $f1=$check_count_fun(-1 );
        $f2=$check_count_fun(-2 );
        $f3=$check_count_fun(-3 );
        $f4=$check_count_fun(-4 );
        $f5=$check_count_fun(-5 );
        $f6=$check_count_fun(-6 );
        $f7=$check_count_fun(-7 );
        if ($f1) { //
            if ($f2) {
                if ($f3) {
                    if ($f4) {
                        if ($f5) {
                            if ($f6) {
                                if ($f7) {
                                    return 12;
                                }else{return 10;}
                            }else{return 8;}
                        }else{return 6;}
                    }else{return 4;}
                } else {return 2;}
            } else {return 0;}
        }else { //
            if ($f2) {
                return -2;
            }else{
                return -4;
            }
        }

    }
}
