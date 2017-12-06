<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Enums as E;
use App\Http\Requests;

class product_tag extends Controller
{
    //
    //@desn:展示所有的产品标签库
    public function tag_list(){
        $tag_l1_sort = $this->get_in_str_val('tag_l1_sort');
        $tag_l2_sort = $this->get_in_str_val('tag_l2_sort');
        $tag_l3_sort = $this->get_in_str_val('tag_l3_sort');
        $tag_name = $this->get_in_str_val('tag_name');
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_tag_library->get_tag_list($tag_l1_sort,$tag_l2_sort,$tag_l3_sort,$tag_name,$page_info);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            E\Etag_object::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
    //@desn:添加产品标签库
    public function tag_add(){
        $tag_l1_sort = $this->get_in_str_val('tag_l1_sort');
        $tag_l2_sort = $this->get_in_str_val('tag_l2_sort');
        $tag_l3_sort = $this->get_in_str_val('tag_l3_sort');
        if($tag_l3_sort == '标签三级分类')
            $tag_l3_sort = '';
        $tag_name = $this->get_in_str_val('tag_name');
        $tag_desc = $this->get_in_str_val('tag_desc');
        $tag_object = $this->get_in_int_val('tag_object');
        $tag_weight = $this->get_in_int_val('tag_weight');
        $adminid = $this->get_account_id();
        $this->t_tag_library->row_insert([
            'tag_name' => $tag_name,
            'tag_l1_sort' => $tag_l1_sort,
            'tag_l2_sort' => $tag_l2_sort,
            'tag_l3_sort' => $tag_l3_sort,
            'tag_weight' => $tag_weight,
            'tag_object' => $tag_object,
            'tag_desc' => $tag_desc,
            'create_time' => time(NULL),
            'manager_id' => $adminid,
        ]);
        return $this->output_succ();
    }
    //@desn:修改产品标签库
    public function tag_update(){
        $tag_id = $this->get_in_id();
        $tag_l1_sort = $this->get_in_str_val('tag_l1_sort');
        $tag_l2_sort = $this->get_in_str_val('tag_l2_sort');
        $tag_l3_sort = $this->get_in_str_val('tag_l3_sort');
        if($tag_l3_sort == '标签三级分类')
            $tag_l3_sort = '';
        $tag_name = $this->get_in_str_val('tag_name');
        $tag_desc = $this->get_in_str_val('tag_desc');
        $tag_object = $this->get_in_int_val('tag_object');
        $tag_weight = $this->get_in_int_val('tag_weight');
        $adminid = $this->get_account_id();
        $this->t_tag_library->field_update_list($tag_id,[
            'tag_name' => $tag_name,
            'tag_l1_sort' => $tag_l1_sort,
            'tag_l2_sort' => $tag_l2_sort,
            'tag_l3_sort' => $tag_l3_sort,
            'tag_weight' => $tag_weight,
            'tag_object' => $tag_object,
            'tag_desc' => $tag_desc,
            'create_time' => time(NULL),
            'manager_id' => $adminid,
        ]);
        return $this->output_succ();

    }
    //@desn:删除标签库
    public function tag_del(){
        $id = $this->get_in_int_val("tag_id");
        $this->t_tag_library->row_delete($id);
        return $this->output_succ();
    }

}
