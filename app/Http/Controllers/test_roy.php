<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class test_roy extends Controller
{
    use CacheNick;

    public function test_list() {
		dd('11');
        /*
        $row= $this->t_student_info->field_get_list($xx,"*");
        $this->t_student_info->field_update_list($userid,$set_field_arr);
        $this->t_student_info->row_insert($arr);
        $this->t_student_info->row_delete($userid);
        $sql="";
        $this->t_student_info->main_get_value($sql);
        $this->t_student_info->main_get_row($sql);
        $this->t_student_info->main_get_list($sql);
        $this->t_student_info->main_get_list_by_page($sql,$page_info);
        */
        $userid=$this->get_in_userid(-1);
        $page_info= $this->get_in_page_info();
        $ret_info=$this->t_student_info->get_list_test($page_info,$userid);
        foreach ($ret_info["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
        }


        return $this->pageView(__METHOD__,$ret_info);
    }

}
