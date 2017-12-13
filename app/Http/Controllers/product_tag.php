<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Enums as E;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class product_tag extends Controller
{
    //@desc:展示所有的产品标签库
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

    public function get_all_tag(){
        $ret = $this->t_tag_library->get_all_tag_list();
        return $this->output_succ(["data" => $ret]);
    }
    //@desn:通过xls添加标签
    public function add_tag_by_xls(){
       if($this->get_account() != 'abner')
           return false;
       $file = Input::file('file');
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $flag = $this->upload_ass_stu_from_xls( $realPath);
            if($flag == 1)
                return outputjson_success();
            else
                return outputjson_ret(false);

        } else {
            return outputjson_ret(false);
        }
    }

    public function upload_ass_stu_from_xls($realPath){
        $adminid = $this->get_account_id();
        $file = Input::file('file');
        \App\Helper\Utils::logger("yayayyal 1111");
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();

            foreach($arr as $k=>&$val){
                if($k == 0)
                    continue;
                if(empty($val[0]) || $k==0 ){
                    unset($arr[$k]);
                    \App\Helper\Utils::logger("22222");
                }
                $tag_name = $val[0];
                $tag_l1_sort = $val[1];
                $tag_l2_sort = $val[2];
                $tag_l3_sort = $val[3];
                $tag_weight = $val[4];
                $tag_object = $val[5];
                $tag_desc = $val[6];
                if(!empty($tag_name) && !empty($tag_l1_sort) && !empty($tag_l2_sort)){
                    $this->t_tag_library->row_insert([
                        'tag_name' => $tag_name,
                        'tag_l1_sort' => $tag_l1_sort,
                        'tag_l2_sort' => $tag_l2_sort,
                        'tag_l3_sort' => $tag_l3_sort,
                        'tag_weight' => $tag_weight,
                        'tag_object' => $tag_object,
                        'tag_desc' => $tag_desc,
                        'create_time' => time(NULL),
                        'manager_id' => $adminid
                    ]);
                }else{
                    \App\Helper\Utils::logger("11111");
                    \App\Helper\Utils::logger("tag_name $tag_name");
                    \App\Helper\Utils::logger("tag_l1_sort $tag_l1_sort");
                    \App\Helper\Utils::logger("tag_l2_sort $tag_l2_sort");
                }
            }


            return 1;
        }else{
            \App\Helper\Utils::logger("33333");
            return 0;
        }
    }

}
