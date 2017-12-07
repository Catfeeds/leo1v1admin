<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class question extends Controller
{
    public function question_list(){
        $subject   = $this->get_in_int_val('id_subject',-1);
        $open_flag   = $this->get_in_int_val('id_open_flag',-1);
        $where_arr = [
            ["subject=%d" , $subject,-1 ],
            ["open_flag=%d" , $open_flag,-1 ],
        ];
        $page_num        = $this->get_in_page_num();
        $ret_list = $this->t_question->question_list($where_arr,$page_num);
        if($ret_list){
            foreach( $ret_list['list'] as &$item ){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']);
                $item['open_str'] = E\Eboolean::get_desc($item['open_flag']);
                $knowledge_detail = $this->question_know_list($item['question_id']);
                $item['knowledge_detail'] = json_encode($knowledge_detail);
            }
        }
        return $this->pageView(__METHOD__,$ret_list, [ "_publish_version" => "201712061556"]);
    }

    public function question_add(){
        $subject   = $this->get_in_int_val('subject',-1);
        $score   = $this->get_in_int_val('score',0);
        $title   = $this->get_in_str_val('title','');
        $detail   = $this->get_in_str_val('detail','');

        $ret = $this->t_question->row_insert([
            "title"   => $title,
            "subject"   => $subject,
            "score"   => $score,
            "detail"   => $detail,
        ]);

        if($ret){
            $result['status'] = 200;
            $result['msg'] = "插入成功";
            return $this->output_succ($result); 
        }else{
            $result['status'] = 500;
            $result['msg'] = "插入失败";
            return $this->output_succ($result); 
        }
    }

    public function question_edit(){
        $question_id   = $this->get_in_int_val('question_id');
        $subject   = $this->get_in_int_val('subject',-1);
        $score   = $this->get_in_int_val('score',0);
        $title   = $this->get_in_str_val('title','');
        $detail   = $this->get_in_str_val('detail','');

        $updateArr = [
            'title' => $title,
            'subject' => $subject,
            "score"   => $score,
            'detail' => $detail,
        ];

        if($this->t_question->field_update_list($question_id,$updateArr)){
            $result['msg'] = '更新成功';
            $result['status'] = 200;
            return $this->output_succ($result);
        }else{
            $result['msg'] = '更新失败';
            $result['status'] = 500;
            return $this->output_succ($result);
        };

    }

    public function question_flag(){
        $question_id = $this->get_in_int_val('question_id');
        $open_flag = $this->get_in_int_val('open_flag');
        $updateArr = [
            'open_flag' => $open_flag,
        ];
        $this->t_question->field_update_list($question_id,$updateArr);
        return $this->output_succ();
    }

    public function question_dele(){
        $question_id = $this->get_in_int_val('question_id');
        //删除该提对应的知识点
        $this->t_question_knowledge->del_by_question_id($question_id);

        //删除该提对应的答案
        $this->t_answer->del_by_question_id($question_id);

        //删除该题
        $this->t_question->del_by_id($question_id);
        
        return $this->output_succ(); 


    }

    public function knowledge_list(){
        $subject   = $this->get_in_int_val('id_subject',-1);
        $where_arr = [
            ["subject=%d" , $subject,-1 ],
        ];
        $page_num        = $this->get_in_page_num();
        $ret_list = $this->t_knowledge_point->knowledge_list($where_arr,$page_num);
        if($ret_list){
            foreach( $ret_list['list'] as &$item ){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']);
            }
        }
        return $this->pageView(__METHOD__,$ret_list, [ "_publish_version" => "201712061856"]);

    }

    public function knowledge_add(){
        $subject   = $this->get_in_int_val('subject',-1);
        $title   = $this->get_in_str_val('title','');
        $detail   = $this->get_in_str_val('detail','');

        $ret = $this->t_knowledge_point->row_insert([
            "title"   => $title,
            "subject"   => $subject,
            "detail"   => $detail,
        ]);

        if($ret){
            $result['status'] = 200;
            $result['msg'] = "插入成功";
            return $this->output_succ($result); 
        }else{
            $result['status'] = 500;
            $result['msg'] = "插入失败";
            return $this->output_succ($result); 
        }
    }

    public function knowledge_edit(){
        $knowledge_id   = $this->get_in_int_val('knowledge_id');
        $subject   = $this->get_in_int_val('subject',-1);
        $title   = $this->get_in_str_val('title','');
        $detail   = $this->get_in_str_val('detail','');

        $updateArr = [
            'title' => $title,
            'subject' => $subject,
            'detail' => $detail,
        ];

        if($this->t_knowledge_point->field_update_list($knowledge_id,$updateArr)){
            $result['msg'] = '更新成功';
            $result['status'] = 200;
            return $this->output_succ($result);
        }else{
            $result['msg'] = '更新失败';
            $result['status'] = 500;
            return $this->output_succ($result);
        };

    }

    public function knowledge_dele(){
        $knowledge_id = $this->get_in_int_val('knowledge_id');
        $this->t_knowledge_point->del_by_id($knowledge_id);
        return $this->output_succ(); 
    }

    public function question_know_get(){
        $subject   = $this->get_in_int_val('subject',-1);
        $title   = $this->get_in_str_val('title',-1);
        $where_arr = [
            ["subject=%d" , $subject,-1 ],
        ];
        if ($title) {
            $title = "%".$title."%";
            $where_arr[]= [ "title like '".$title."'",null ];
        }
        $page_num        = $this->get_in_page_num();
        
        $ret  = \App\Helper\Utils::list_to_page_info([]);
        $ret =  $this->t_knowledge_point->knowledge_get($where_arr,$page_num);
        
        if($ret){
            foreach($ret['list'] as &$item){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']);
            }
        }
        
        return $this->output_ajax_table($ret, [ "lru_list" => [] ]);
    }

    public function question_know_list($question_id){
        
        $q_k = $this->t_question_knowledge->question_know_list($question_id);
        if($q_k){
            foreach($q_k as &$item){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']);
                $item['difficult_str'] = E\Equestion_difficulty::get_desc($item['difficult']);
                if( !$item['title']){
                    $item['title'] = '该知识点已经被删除';
                }
            }
        }
        return $q_k;
    }

    //添加题目对应的知识点
    public function question_know_add(){
        $question_id = $this->get_in_int_val('question_id');
        $idstr = $this->get_in_str_val('strArr');
        if(!$idstr){
            $result['msg'] = '请添加知识点';
            $result['status'] = 500;
            return $this->output_succ($result);
        }
      
        $success_item = 0 ;
        $fail_item = 0;
        $idArr = $idstr;
        foreach( $idArr as $knowledge_id => $difficult){
            //查找该知识点id是否删除
            $where_arr = [
                ["knowledge_id=%d" , $knowledge_id ],
            ];
            $is_knowledge_exit = $this->t_knowledge_point->knowledge_get($where_arr,1);
            if(!$is_knowledge_exit){
                continue;
            }

            //查找该条目录是否已经存在 不存在才能添加
            $is_question_know_exit = $this->t_question_knowledge->is_question_know_exit($question_id,$knowledge_id,$difficult);
            if($is_question_know_exit){
                $fail_item++;
                continue;
            }

            $ret = $this->t_question_knowledge->row_insert([
                'question_id' => $question_id,
                'knowledge_id' => $knowledge_id,
                'difficult' => $difficult,
            ]);
            if($ret){
                $success_item++;
            }else{
                $fail_item++;
            }
        }
        $result['msg'] = '成功添加知识点条数：'.$success_item.'添加失败条数：'.$fail_item;
        $result['status'] = 200;
        return $this->output_succ($result);
    }

    public function question_know_dele(){
        $id = $this->get_in_int_val('id');
        $this->t_question_knowledge->del_by_id($id);
        return $this->output_succ(); 

    }
    public function answer_list(){
        $question_id   = $this->get_in_int_val('question_id');
        $where_arr = [
            ["question_id=%d" , $question_id ],
        ];
        $ret_list = $this->t_answer->answer_list($where_arr);
        $next_step = 1;
        if($ret_list['list']){
            foreach( $ret_list['list'] as &$item ){
                $item['difficult_str'] = E\Equestion_difficulty::get_desc($item['difficult']);
                $item['step_str'] = '第'.$item['step'].'步';
                if(!$item['title']){
                    $item['title'] = '该知识点未添加活被删除';
                }
            }
            $next_step = (int)end($ret_list['list'])['step'] + 1;
        }

        //获取题目信息
        $question_info = $this->t_question->get_question_info($question_id);
        return $this->pageView(__METHOD__,$ret_list, [ "_publish_version" => "201712061556",'next_step'=>$next_step,'question'=>$question_info]);
        
    }

    public function answer_add(){
        $step = $this->get_in_int_val('step');
        $question_id = $this->get_in_int_val('question_id');
        $knowledge_id = $this->get_in_int_val('knowledge_id');
        $difficult   = $this->get_in_int_val('difficult',1);
        $score   = $this->get_in_int_val('score',0);
        $detail   = $this->get_in_str_val('detail','');

        //查看该答题步骤是否存在
        $is_exit = $this->t_answer->is_exit_step($question_id,$step);
        if($is_exit){
            $result['status'] = 500;
            $result['msg'] = "该答题步骤数已经存在，请重新输入步骤数";
            return $this->output_succ($result); 

        }
        $ret = $this->t_answer->row_insert([
            "step"   => $step,
            "question_id"   => $question_id,
            "knowledge_id"   => $knowledge_id,
            "difficult"   => $difficult,
            "score"   => $score,
            "detail"   => $detail,
        ]);

        if($ret){
            $result['status'] = 200;
            $result['msg'] = "插入成功";
            return $this->output_succ($result); 
        }else{
            $result['status'] = 500;
            $result['msg'] = "插入失败";
            return $this->output_succ($result); 
        }

    }

    public function answer_edit(){
        $step = $this->get_in_int_val('step');
        $answer_id = $this->get_in_int_val('answer_id');
        $question_id = $this->get_in_int_val('question_id');
        $knowledge_id = $this->get_in_int_val('knowledge_id');
        $difficult   = $this->get_in_int_val('difficult',1);
        $score   = $this->get_in_int_val('score',0);
        $detail   = $this->get_in_str_val('detail','');

        //查看该答题步骤是否存在
        $is_exit = $this->t_answer->is_exit_edit_step($answer_id,$question_id,$step);
        if($is_exit){
            $result['status'] = 500;
            $result['msg'] = "该答题步骤数已经存在，请重新输入步骤数";
            return $this->output_succ($result); 

        }

        $updateArr = [
            "step"   => $step,
            "question_id"   => $question_id,
            "knowledge_id"   => $knowledge_id,
            "difficult"   => $difficult,
            "score"   => $score,
            "detail"   => $detail,
        ];

        if($this->t_answer->field_update_list($answer_id,$updateArr)){
            $result['status'] = 200;
            $result['msg'] = "更新成功";
            return $this->output_succ($result); 
        }else{
            $result['status'] = 500;
            $result['msg'] = "更新失败";
            return $this->output_succ($result); 
        }

    }

    public function answer_dele(){
        $answer_id = $this->get_in_int_val('answer_id');
        $this->t_answer->del_by_id($answer_id);
        return $this->output_succ(); 
    }

}