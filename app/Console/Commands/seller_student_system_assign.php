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
        //等级对应配额[抢新]
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
        //私海配额
        $hold_config=\App\Helper\Config::get_seller_hold_user_count();

        $admin_list=[];
        foreach ($tmp_admin_list as $key=> $item){ //
            $adminid=$item["uid"];
            $seller_level=$item["seller_level"];
            $def_new_count=@$config[$seller_level]; //每日抢新配额
            // $no_return_call_num = $item['no_return_call_num'];//试听成功未回访数量
            $no_return_call_list = $this->task->t_lesson_info_b2->get_call_end_time_num_by_adminid($adminid);
            $no_return_call_num = count($no_return_call_list);
            $no_return_call_arr = array_column($no_return_call_list, 'phone');
            $no_return_call_str = join(',', $no_return_call_arr);
            if (!$def_new_count){
                $def_new_count=0;
            }
            $assigned_new_count = @$admin_assign_map[$adminid]["new_count"];//已获取配额
            if (!$assigned_new_count){
                $assigned_new_count=0;
            }
            $assigned_no_connected_count = @$admin_assign_map[$adminid]["no_connected_count"];//已获取未拨打数量
            if (! $assigned_no_connected_count ){
                $assigned_no_connected_count=0;
            }

            //新例子
            $item["def_new_count"] = $def_new_count;//新例子配额
            if ( $def_new_count>$seller_max_new_count ) {
                $seller_max_new_count = $def_new_count;//最大新例子配额[所有cc]
            }
            $item["assigned_new_count"] = $assigned_new_count;//已获取新例子

            $need_new_count_all+=$def_new_count; //新例子配额之和
            $assigned_new_count_all+= min( [$assigned_new_count, $def_new_count]);//已获取例子之和


            //未拨通例子重新分配的个数
            $def_no_connected_count= 5;
            if($item['is_top']){
                $item["def_no_connected_count"] = $def_no_connected_count ;//配置未拨通数量

                $need_no_connected_count_all+=$def_no_connected_count;//配置未拨通数量之和
            }
            $item["assigned_no_connected_count"] = $assigned_no_connected_count ;//已获取未拨通数量
            //已获取未拨通数量之和
            $assigned_no_connected_count_all+= min([ $assigned_no_connected_count, $def_no_connected_count  ]);
            //得到每个人上限
            $item["hold_count"]=$this->task->t_seller_student_new_b2->admin_hold_count($adminid);//私海数量
            $item["max_hold_count"] = @$hold_config[$seller_level];
            \App\Helper\Utils::logger("$adminid:". $item["hold_count"]."." .$item["max_hold_count"]  );
            // $admin_list['work_start_time'] = $work_start_time_map[$adminid]['work_start_time'];
            //$need_work_flag
            $add_flag= true;
            if ($need_work_flag) {
                $add_flag = isset($work_start_time_map[$adminid]);
            }
            if ($add_flag) {
                //不超上限
                $add_flag=($item["max_hold_count"] >$item["hold_count"]);
            }
            if ($add_flag && $no_return_call_num <=0 )  {
                $admin_list[]=$item;
            }


            //记录试听未回访信息
            $is_set = $this->task->t_cc_no_return_call->field_get_value($adminid, 'uid');
            if(!$is_set){
                $this->task->t_cc_no_return_call->row_insert([
                    'uid' => $adminid,
                    'no_return_call_num' => $no_return_call_num,
                    'no_call_str' => $no_return_call_str,
                    'add_time' => strtotime(date('Y-m-d'))
                ]);
            }else{
                $this->task->t_cc_no_return_call->field_update_list($adminid, [
                    'no_return_call_num' => $no_return_call_num,
                    'no_call_str' => $no_return_call_str,
                    'add_time' => strtotime(date('Y-m-d'))
                ]);
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
        $left_no_connected_count_all=$ret_info["left_no_connected_count_all"];//剩余未拨通数量之和
        $left_new_count_all=$ret_info["left_new_count_all"];//还需总配额
        $admin_list=$ret_info["admin_list"];//销售信息

        $seller_max_new_count = $ret_info["seller_max_new_count"];//最大新例子配额

        $new_ret_info= $this->assign_new( $left_new_count_all,$admin_list ,$seller_max_new_count );
        $no_connnected_ret_info=$this->assign_no_connected_new( $left_no_connected_count_all,$admin_list  );
        // $no_connnected_ret_info=$this->assign_no_connected( $left_no_connected_count_all,$admin_list  );
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

    //@desn:分配奖励例子
    public function assign_no_connected_new($left_no_connected_count_all,$admin_list){
        $new_deal_list = [];
        //30天内未拨通电话
        $need_deal_list=$this->task->t_seller_student_new_b2->get_need_new_assign_list(
            E\Etq_called_flag::V_1
        );
        $need_deal_list_count = count( $need_deal_list);
        $new_deal_list = $this->task->t_seller_student_new_b2->get_need_new_assign_list(
            E\Etq_called_flag::V_0
        );
        shuffle ($need_deal_list);
        if($new_deal_list)
            shuffle($new_deal_list);
        $need_deal_list = array_merge($need_deal_list,$new_deal_list);
        $need_deal_count= count( $need_deal_list);
        $old_need_deal_count=$need_deal_count;

        $assigned_count=0;
        if( $left_no_connected_count_all)  {
            $start_deal_index=0;//random_int(0, $need_deal_count*2/3 );
            for ($i=0;$i< 5;$i++ ) { //第几轮
                \App\Helper\Utils::logger(" DO count_reward :$i");
                //遍历获奖用户
                foreach( $admin_list as $item ) {
                    if($item['is_top']){//获奖
                        $assigned_no_connected_count=$item["assigned_no_connected_count"];//已获取奖励数量
                        $def_no_connected_count=$item["def_no_connected_count"];//分配奖励数量
                        $opt_adminid= $item["uid"];
                        \App\Helper\Utils::logger(" --> adminid: $opt_adminid, $i,assigned_no_connected_count:$assigned_no_connected_count");
                        if ($assigned_no_connected_count <=$i ){//这一轮可以分配
                            for($j=$start_deal_index; $j< $need_deal_count ;  $j++ ) {  //为了避免因已分配过少分
                                $find_userid= @$need_deal_list[$j]["userid"];
                                //判断之前没有分配给此用户过
                                if ( $find_userid && !$this->task->t_seller_student_system_assign_log->check_userid_adminid_existed( $find_userid, $opt_adminid  ) ) {

                                    $assigned_count++;
                                    $userid_list=[$find_userid];
                                    $opt_type ="" ;
                                    $opt_type=0;
                                    $account="系统分配-未拨通例子";
                                    $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $opt_adminid, $opt_type,$account);
                                    $check_hold_flag = false;
                                    $this->task->t_seller_student_system_assign_log->add(
                                        E\Eseller_student_assign_from_type::V_1, $find_userid, $opt_adminid,$check_hold_flag
                                    );
                                    unset($need_deal_list[$j]);
                                    $start_deal_index=$j+1;
                                    break;
                                }
                            }
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


    public function   assign_no_connected ( $left_no_connected_count_all, $admin_list  ) {
        //30天内未拨通电话
        $need_deal_list=$this->task->t_seller_student_new_b2->get_need_new_assign_list(
            E\Etq_called_flag::V_1
        );
        $need_deal_count= count( $need_deal_list);
        $old_need_deal_count=$need_deal_count;
        $assigned_count=0;
        if( $left_no_connected_count_all)  {

            shuffle ($need_deal_list);
            $start_deal_index=0;//random_int(0, $need_deal_count*2/3 );
            foreach( $admin_list as &$item ) {
                //销售在奖励名单内
                if($item['is_top']){
                    $assigned_no_connected_count=$item["assigned_no_connected_count"];//已获取奖励数量
                    $def_no_connected_count=$item["def_no_connected_count"];//分配奖励数量
                    $opt_adminid= $item["uid"];
                    for($i=$assigned_no_connected_count;$i<$def_no_connected_count;$i++ ) {  //差几次分几个
                        for($j=$start_deal_index; $j< $need_deal_count ;  $j++ ) {
                            $find_userid= @$need_deal_list[$j]["userid"];
                            //判断之前没有分配给此用户过
                            if ( $find_userid && !$this->task->t_seller_student_system_assign_log->check_userid_adminid_existed( $find_userid, $opt_adminid  ) ) {

                                $assigned_count++;
                                $userid_list=[$find_userid];
                                $opt_type ="" ;
                                $opt_type=0;
                                $account="系统分配-未拨通例子";
                                $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $opt_adminid, $opt_type,$account);
                                $check_hold_flag = false;
                                $this->task->t_seller_student_system_assign_log->add(
                                    E\Eseller_student_assign_from_type::V_1, $find_userid, $opt_adminid,$check_hold_flag
                                );
                                unset($need_deal_list[$j]);
                                $start_deal_index=$j+1;
                                break;
                            }
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
    //@param:$left_new_count_all 还需新例子数
    //@param:$admin_list  所有销售列表
    //@param:$seller_max_new_count 最大配额
    public function assign_new( $left_new_count_all, $admin_list,$seller_max_new_count  )  {
        \App\Helper\Utils::logger("还需新例子数-left_new_count_all:$left_new_count_all");
        \App\Helper\Utils::logger("分配销售列表-admin_list:".json_encode($admin_list));
        \App\Helper\Utils::logger("最大配额-seller_max_new_count:$seller_max_new_count");
        //待分配系统分配例子
        $need_deal_list=$this->task->t_seller_student_new_b2->get_need_new_assign_list(
            E\Etq_called_flag::V_0
        );
        $seller_student_level_map=[];
        $need_deal_count=count($need_deal_list);
        $assigned_count=0;
        if ($left_new_count_all) {
            foreach ($need_deal_list as $user_info)  {
                $userid=$user_info["userid"];
                $origin_level=$user_info["origin_level"];
                //y类渠道[优学优享]按s类分配
                if($origin_level == 99)
                    $origin_level = 1;
                if (!isset($seller_student_level_map[$origin_level]) ) {
                    $seller_student_level_map[$origin_level]=[];
                }
                $seller_student_level_map[$origin_level][]= $userid;
            }

            $check_end_flag=false;
            for ($i=0;$i< $seller_max_new_count;$i++ ) { //第几轮
                \App\Helper\Utils::logger(" DO count :$i");

                $round_seller_level_map=[ ];
                foreach( $admin_list as $item ) {
                    $assigned_new_count=$item["assigned_new_count"];//已获取新例子
                    $seller_level=$item["seller_level"];
                    $def_new_count=$item["def_new_count"];//新例子配额
                    $opt_adminid= $item["uid"];
                    $seller_level_flag= floor( $seller_level/100);
                    if (!isset($round_seller_level_map[$seller_level_flag] )) {
                        $round_seller_level_map[$seller_level_flag]=[];
                    }

                    \App\Helper\Utils::logger(" --> adminid: $opt_adminid, $i, def_new_count:$def_new_count , assigned_new_count:$assigned_new_count   ");
                    if ($i<$def_new_count // 在配额内
                        && $assigned_new_count <=$i //这一轮可以分配
                    ){
                        $round_seller_level_map[$seller_level_flag][$opt_adminid]= $opt_adminid;
                    }
                }
                \App\Helper\Utils::logger("check_error1:$i-round_seller_level_map".json_encode($round_seller_level_map));
                \App\Helper\Utils::logger("check_error1:$i-seller_student_level_map".json_encode($seller_student_level_map));

                if (count($round_seller_level_map) >0 ) {
                    $this->round_set_adminid( $round_seller_level_map, $seller_student_level_map);
                }
            }
        }

        return [
            "need_deal_count" =>$need_deal_count,
            "assigned_count" =>$assigned_count,
        ];

    }
    //@desn:同等级分配
    //@param:$account 分配注释
    //@param:$userid 找到的用户id
    //@param:$account 找到的销售id
    public function do_assign($account ,$userid, $adminid ) {
        \App\Helper\Utils::logger("第一轮分配：userid:$userid adminid:$adminid");
        $userid_list=[$userid];
        $opt_type ="" ;
        $opt_type=0;
        $this->task->t_seller_student_new->set_admin_id_ex( $userid_list, $adminid, $opt_type,$account);
        $check_hold_flag = false;
        $this->task->t_seller_student_system_assign_log->add(
            E\Eseller_student_assign_from_type::V_0, $userid, $adminid,$check_hold_flag
        );


    }

    //每轮 第一次  通过cc 等级 找对应 例子
    public function  round_one_student_to_admin(  &$round_seller_level_map, &$seller_student_level_map) {
        //E\Eseller_level
        $seller_level_flag_to_origin_map=[
            1=> E\Eorigin_level::V_1,
            2=> E\Eorigin_level::V_2,
            3=> E\Eorigin_level::V_3,
            4=> E\Eorigin_level::V_4,
            5=> E\Eorigin_level::V_4,
            6=> E\Eorigin_level::V_4,
            7=> E\Eorigin_level::V_4,
        ];
        //$seller_level_flag cc等级
        //$seller_level_admin_map 每个等级的销售arr
        $y = 1;
        foreach ( $round_seller_level_map as $seller_level_flag =>  &$seller_level_admin_map ) {
            $find_origin_level= @$seller_level_flag_to_origin_map[$seller_level_flag ];
            if (!$find_origin_level) {
                $find_origin_level =  E\Eorigin_level::V_4;
            }

            $account="系统分配-新例子-每轮首次按用户等级分 ";
            foreach( $seller_level_admin_map as $adminid=>$v ) {
                if(@$seller_student_level_map[ $find_origin_level]){
                    $find_userid= @array_shift($seller_student_level_map[ $find_origin_level] );
                    if ($find_userid) {
                        \App\Helper\Utils::logger("check-for-1".$y++.'-userid:'.$find_userid);
                        $this->do_assign($account, $find_userid, $adminid);
                        unset ( $seller_level_admin_map[$adminid]  );
                    }
                }
            }
        }
        \App\Helper\Utils::logger("check_error2:-seller_level_admin_map".json_encode($seller_level_admin_map));
        \App\Helper\Utils::logger("check_error2:-seller_student_level_map".json_encode($seller_student_level_map));
    }
    //@desn:选择对应的cc
    //@param:$userid 用户id
    //@param:$check_seller_level_list 分配规则数组
    //@param:$round_seller_level_map  剩下的销售
    public  function assign_adminid( $userid, $check_seller_level_list , &$round_seller_level_map ) {

        $account="系统分配-新例子-每轮二次 按例子等级分";

        foreach( $check_seller_level_list as  $check_seller_level ) {
            $find_adminid= @array_shift( $round_seller_level_map[ $check_seller_level] );
            if ($find_adminid) {
                $this->do_assign($account, $userid, $find_adminid);
                return $find_adminid;
            }
        }
        return 0;
    }

    //@desn:每一轮分配例子
    //@param:$round_seller_level_map 待分配销售列表
    //@param:$seller_student_level_map 待分配学员列表
    public function  round_set_adminid(  $round_seller_level_map, &$seller_student_level_map) {
        \App\Helper\Utils::logger("1round销售列表-round_seller_level_map: " . json_encode( $round_seller_level_map ) );
        \App\Helper\Utils::logger("1round例子列表-seller_student_level_map: " . json_encode( $seller_student_level_map) );


        //每轮 第一次  通过cc 等级 找对应 例子
        $this->round_one_student_to_admin($round_seller_level_map, $seller_student_level_map);


        // 剩下的 例子 等级 找cc
        //E\Eorigin_level::V_1
        $origin_level_to_seller_level_flag_map=[
            1 => [ 2, 3,  4, 5 ,6,7 ] , // S =>   A B C D E F
            2 => [ 1, 3, 4, 5, 6, 7] ,  // A =>   S B C D E F
            3 => [2 , 1, 4, 5, 6, 7 ] , // B =>   A S C D E F
            4 => [5, 6, 7, 3,2, 1 ] ,   // C =>   D E F B A S
        ];

        \App\Helper\Utils::logger("22round_set_adminid: round_seller_level_map: " . json_encode( $round_seller_level_map ) );
        \App\Helper\Utils::logger("22round_set_adminid:  seller_student_level_map: " . json_encode( $seller_student_level_map) );


        $find_end_flag=false;
        //$origin_level 渠道等级
        //$userid_list 学员信息
        $x = 1;
        foreach ( $seller_student_level_map as  $origin_level => &$userid_list ) {
            \App\Helper\Utils::logger("check_sort".json_encode($userid_list));
            //渠道等级
            $check_origin_level= $origin_level;
            if (!in_array( $check_origin_level, [1,2,3,4]) ) {
                $check_origin_level= 4;
            }
            //渠道对应cc顺序数组
            $check_seller_level_list= $origin_level_to_seller_level_flag_map[ $check_origin_level ];
            //防止有其它等级的用户,没有处理, 把所有的等级加入检查列表
            $check_seller_level_list = array_merge( $check_seller_level_list, array_keys($round_seller_level_map ));
            \App\Helper\Utils::logger("33round_set_adminid  userid_list: " . json_encode( $userid_list) );

            while ( ($userid =@array_shift( $userid_list )) >0 ){ //抛出处理
                \App\Helper\Utils::logger("check-for-2".$x++.'-userid:'.$userid);

                $find_adminid=$this->assign_adminid($userid, $check_seller_level_list, $round_seller_level_map);
                if (!$find_adminid ) {
                    array_unshift($userid_list, $userid ) ;
                    $find_end_flag=true;
                    break;
                }
            }
            if  ($find_end_flag ) {
                break;
            }
        }

    }



    //@param:$level_map 用户渠道等级arr
    //@param:$seller_level cc等级
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

        //E\Eorigin_level

        switch ( $seller_level_flag ) {
        case 1 :  //S级:所有 S A B C
            $origin_level_list=[1, 2, 3, 4] ; break;
        case 2 :  //A级:A S B C
            $origin_level_list=[2,1,3,4 ]; break;
        case 3 : //B级:B A C S
            $origin_level_list=[3,2,4,1 ]; break;
        default: //其它:C B A S
            $origin_level_list=[4,3,2,1 ]; break;
        }

        $find_level_map_item=null;
        foreach( $origin_level_list as $origin_level ) {
            if ( isset($level_map[$origin_level]) && count( $level_map[$origin_level])>0 ) {
                $find_level_map_item=  &$level_map[$origin_level];//每个等级的用户
                break;
            }
        }

        if (!$find_level_map_item){ // 检查其他
            foreach ( $level_map as  &$level_item  )  {
                if (count($level_item)>0 ) {
                    $find_level_map_item =&$level_item;//检查？？
                    break;
                }
            }
        }

        $find_userid=0;
        if ($find_level_map_item)  {
            foreach($find_level_map_item as  $userid => $value) {
                $find_userid= $userid; //用户id
                break;
            }
            unset($find_level_map_item[$find_userid])  ;
        }
        \App\Helper\Utils::logger("find_userid: $find_userid ");
        return $find_userid;
    }

}
