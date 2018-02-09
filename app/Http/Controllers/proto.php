<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

/*
3. 修改字段的注释，代码如下：

ALTER TABLE `student` MODIFY COLUMN `id` COMMENT '学号';

查看字段的信息，代码如下：

SHOW FULL COLUMNS  FROM `student`;
*/

class proto extends Controller
{
    var $check_login_flag = false;

    public function cmd_list() {
        $project=$this->get_in_str_val("project","yb_admin");
        $tag=$this->get_in_str_val("tag","");
        $query_str=trim($this->get_in_str_val("query_str"));
        //得到
        $project_dir=app_path("../public/proto");
        $cmd_file=$project_dir."/{$project}-cmd.json";
        $cmd_info=json_decode(file_get_contents($cmd_file),true);
        $cmd_list=[];
        foreach ($cmd_info["cmd_list"] as $item ) {
            $find_flag=true;
            if ( $find_flag && $tag) {
                $find_flag=in_array($tag, $item["TAGS"] );
            }
            if ($find_flag &&  $query_str) {
                $find_flag=
                    strpos($item["CMD"] , $query_str ) !== false
                    || strpos($item["NAME"] , $query_str ) !== false
                    || strpos($item["DESC"] , $query_str ) !== false;
            }

            if ($find_flag) {
                $cmd_list[]=[
                    "tags" => join(",", $item["TAGS"]),
                    "cmdid" => $item["CMD"] ,
                    "name" => $item["NAME"] ,
                    "desc" => $item["DESC"] ,
                ];
            }
        }

        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($cmd_list) ,
                               [
                                   "tag_list" => $cmd_info["tag_list"],
                               ]);
    }

    public function cmd_desc(){
        $project=$this->get_in_str_val("project","yb_db");
        $cmdid=$this->get_in_str_val("cmdid");
        $project_dir=app_path("../public/proto");
        $cmd_file=$project_dir."/{$project}-cmd.json";
        $cmd_info=json_decode(file_get_contents($cmd_file),true);
        $info_file=$project_dir."/{$project}-info.json";
        $info=json_decode(file_get_contents($info_file),true);

        $struct_map     = $info["struct_map"];
        $error_list     = $info["error_list"];
        $cmd_return_map = $info["cmd_return_map"];

        $cmd_list=[];
        foreach ($cmd_info["cmd_list"] as $item ) {
            if ( !$cmdid  || $item["CMD"]==  $cmdid) {
                $cmd_list[]=[
                    "tags" => join(",", $item["TAGS"]),
                    "cmdid" => $item["CMD"] ,
                    "name" => $item["NAME"] ,
                    "desc" => $item["DESC"] ,
                ];
            }
        }
        $cmd_desc_list = $cmd_list;
        if ($cmdid) {
            $cmd_list=[];
            $error_list=[];
        }
        unset($item);
        foreach ($cmd_desc_list as &$item )  {
            $item["in"]= [] ;
            $item["out"]= [] ;
            $struct_name=$item["name"];
            $cmd_error_list= @$cmd_return_map[ $struct_name ];
            if (!$cmd_error_list) {
                $cmd_error_list=[];
            }
            $item["cmd_error_list"]  = $cmd_error_list;
            $this->gen_struct_list( $item["in"],  $struct_map, $struct_name.".in"   );
            $this->gen_struct_list( $item["out"],  $struct_map, $struct_name.".out"   );
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($cmd_list) ,[
            "cmd_desc_list" =>  $cmd_desc_list,
            "tag_list"  =>   $cmd_info["tag_list"] ,
            "error_list" => $error_list,
            "project" => $project,
        ]);
    }

    public function gen_struct_list( &$field_list, $struct_map, $struct_name, $field_pre_fix="" ) {
        $sub_field_pre_fix= $field_pre_fix.">>>>";
        $list= @$struct_map["$struct_name"] ;
        if ($list) {
            foreach ( $list as  $item ) {
                $sub_struct_name= $item[1];
                $item[2]= $sub_field_pre_fix. $item[2] ;
                $item[3]= $sub_field_pre_fix. $item[3] ;
                $field_list[]= $item;
                if (!in_array($sub_struct_name,[ "uint32", "int32", "string","bool" ])){
                    $this->gen_struct_list( $field_list, $struct_map, $sub_struct_name,  $sub_field_pre_fix ) ;
                }
            }
        }
    }

}
