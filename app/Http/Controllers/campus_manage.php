<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class campus_manage extends Controller
{
    use CacheNick;
    use TeaPower;

   
    public function admin_campus_manage(){
        $ret_info = $this->t_admin_campus_list->get_admin_campus_info(); 
        $list=[];
        foreach($ret_info as $val)   {
            $n = $num;
            $list[] = ["campus_id"=>$val["campus_id"],"campus_name"=>$val["campus_name"],"up_group_name"=>"","group_name"=>"","account"=>"","campus_id_class"=>"campus_id-".$n,"up_group_name_class"=>"","group_name_class"=>"","account_class"=>"","level"=>"l-1"];
            $up_group_list = $this->t_admin_main_group_name->get_group_list($i);

            foreach($up_group_list as $item){
                $list[] = ["main_type"=>$i,"up_group_name"=>$item["group_name"],"group_name"=>"","account"=>"","main_type_class"=>"main_type-".$n,"up_group_name_class"=>"up_group_name-".++$num,"group_name_class"=>"","account_class"=>"","level"=>"l-2","up_master_adminid"=>$item["master_adminid"],"up_groupid"=>$item["groupid"]];
                if($monthtime_flag==1){
                    $group_list = $task->t_admin_group_name->get_group_name_list($i,$item["groupid"]);
                }else{
                    $group_list = $task->t_group_name_month->get_group_name_list($i,$item["groupid"],$month);
                }

                $m = $num;
                foreach($group_list as $val){
                    $list[] = ["main_type"=>$i,"up_group_name"=>$item["group_name"],"group_name"=>$val["group_name"],"account"=>"","main_type_class"=>"main_type-".$n,"up_group_name_class"=>"up_group_name-".$m,"group_name_class"=>"group_name-".++$num,"account_class"=>"","groupid"=>$val["groupid"],"level"=>"l-3","master_adminid"=>$val["master_adminid"]];
                    if($monthtime_flag==1){
                        $admin_list = $task->t_admin_group_user->get_user_list_new($val["groupid"]);
                    }else{
                        $admin_list = $task->t_group_user_month->get_user_list_new($val["groupid"],$month);
                    }

                    $c = $num;
                    foreach($admin_list as $v){
                        $list[] = ["main_type"=>$i,"up_group_name"=>$item["group_name"],"group_name"=>$val["group_name"],"account"=>$v["account"],"main_type_class"=>"main_type-".$n,"up_group_name_class"=>"up_group_name-".$m,"group_name_class"=>"group_name-".$c,"account_class"=>"account-".++$num,"adminid"=>$v["adminid"],"groupid"=>$val["groupid"],"level"=>"l-4"];

                    }
                }
            }
            $num++;
        }
 
        return $this->pageView(__METHOD__,$ret_info);

        dd($ret_info);
    }

    public function add_admin_campus(){
        $campus_name =trim($this->get_in_str_val("campus_name"));
        $this->t_admin_campus_list->row_insert([
            "campus_name" =>$campus_name
        ]);
        return $this->output_succ();
    }


}