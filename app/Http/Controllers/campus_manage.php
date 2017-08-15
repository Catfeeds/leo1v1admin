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
        $num=1;
        foreach($ret_info as $s)   {
            $n = $num;
            $campus_id = $s["campus_id"];
            $campus_name = $s["campus_name"];
            $list[] = ["campus_id"=>$campus_id,"campus_name"=>$campus_name,"up_group_name"=>"","group_name"=>"","account"=>"","campus_id_class"=>"campus_id-".$n,"up_group_name_class"=>"","group_name_class"=>"","account_class"=>"","level"=>"l-1"];
            $up_group_list = $this->t_admin_main_group_name->get_group_list_by_campus_id($campus_id);

            foreach($up_group_list as $item){
                $list[] = ["campus_id"=>$campus_id,"campus_name"=>$campus_name,"up_group_name"=>$item["group_name"],"group_name"=>"","account"=>"","campus_id_class"=>"campus_id-".$n,"up_group_name_class"=>"up_group_name-".++$num,"group_name_class"=>"","account_class"=>"","level"=>"l-2","up_master_adminid"=>$item["master_adminid"],"up_groupid"=>$item["groupid"],"main_type"=>$item["main_type"]];
                $group_list = $this->t_admin_group_name->get_group_name_list($i,$item["groupid"]);

                $m = $num;
                foreach($group_list as $val){
                    $list[] = ["campus_id"=>$campus_id,"campus_name"=>$campus_name,"up_group_name"=>$item["group_name"],"group_name"=>$val["group_name"],"account"=>"","campus_id_class"=>"campus_id-".$n,"up_group_name_class"=>"up_group_name-".$m,"group_name_class"=>"group_name-".++$num,"account_class"=>"","groupid"=>$val["groupid"],"level"=>"l-3","master_adminid"=>$val["master_adminid"],"main_type"=>$item["main_type"]];
                    $admin_list = $this->t_admin_group_user->get_user_list_new($val["groupid"]);

                    $c = $num;
                    foreach($admin_list as $v){
                        $list[] = ["campus_id"=>$campus_id,"campus_name"=>$campus_name,"up_group_name"=>$item["group_name"],"group_name"=>$val["group_name"],"account"=>$v["account"],"campus_id_class"=>"campus_id-".$n,"up_group_name_class"=>"up_group_name-".$m,"group_name_class"=>"group_name-".$c,"account_class"=>"account-".++$num,"adminid"=>$v["adminid"],"groupid"=>$val["groupid"],"level"=>"l-4","main_type"=>$item["main_type"]];

                    }
                }
            }
            $num++;
        }
 
        foreach($list as &$pig){
            if($pig["level"] != "l-1"){
                $pig["campus_name"]  =  $pig["campus_name"]."-".E\Emain_type::get_desc($pig["main_type"]);
            }
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list));

        dd($list);
    }

    public function add_admin_campus(){
        $campus_name =trim($this->get_in_str_val("campus_name"));
        $this->t_admin_campus_list->row_insert([
            "campus_name" =>$campus_name
        ]);
        return $this->output_succ();
    }

    public function set_campus_id(){
        $campus_id = $this->get_in_int_val("campus_id");
        $groupid = $this->get_in_int_val("up_groupid");
        $this->t_admin_main_group_name->field_update_list($groupid,[
           "campus_id"  =>$campus_id 
        ]);
        return $this->output_succ();
    }

}