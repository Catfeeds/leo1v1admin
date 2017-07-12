<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class lesson_info extends Controller
{
    public function update_lesson_info(){
        $lessonid = $this->get_in_int_val("lessonid");
        $grade    = $this->get_in_int_val("grade");

        $ret = $this->t_lesson_info->field_update_list($lessonid,[
            "grade" => $grade
        ]);

        if(!$ret){
            return $this->output_err("更新失败！请重试！");
        }
        return $this->output_succ();
    }


}