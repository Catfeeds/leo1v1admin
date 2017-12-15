<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class question_new extends Controller
{
    public function question_list(){
        $subject   = $this->get_in_int_val('id_subject',1);
        $open_flag   = $this->get_in_int_val('id_open_flag',1);
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
        return $this->pageView(__METHOD__,$ret_list, [ "_publish_version" => "201712121556"]);
    }

    public function question_edit(){
        $editType   = $this->get_in_int_val('editType',1); //1:添加 2:编辑
        $question_id   = $this->get_in_int_val('question_id');
        $subject   = $this->get_in_int_val('subject');

        $ret = [
            'describe' => '新增题目',
        ];

        //编辑题目
        $editData = [];

        //题目对应的知识点id
        $know_arr = [];

        if($question_id){
            //取出题目
            $editData = $this->t_question->get_by_id($question_id);
            $ret['describe'] = '编辑题目';

            //取出题目对应的知识点
            $q_k = $this->t_question_knowledge->question_know_list($question_id);
            if($q_k){
                $know_arr = array_column($q_k, 'knowledge_id'); 
            }
        }
        $editData = json_encode($editData);

        $where_arr = [
            ["subject=%d" , $subject,-1 ],
        ];

        $know_list = $this->t_knowledge_point->knowledge_list($where_arr,null);
        //所有知识点
        $knowledge = [];
        if($know_list['list']){
            if(empty($know_arr)){
                foreach( $know_list['list'] as &$item){
                    $arr['name'] = $item['title'];
                    $arr['id'] = $item['knowledge_id'];
                    $arr['pId'] = $item['father_id'];
                    $arr['editType'] = 2;
                    $knowledge[] = $arr;
                }

            }else{        
                foreach( $know_list['list'] as &$item){
                    $arr['name'] = $item['title'];
                    $arr['id'] = $item['knowledge_id'];
                    $arr['pId'] = $item['father_id'];
                    $arr['editType'] = 2;
                    if(in_array($item['knowledge_id'], $know_arr)){  
                        $know_arr[$item['knowledge_id']] = $item['title'];
                    }
                    $knowledge[] = $arr;
                }}
        }
        //dd($knowledge);
        $knowledge = json_encode($knowledge);
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712131617",
                                                  "ret"=>$ret,
                                                  'editData'=>$editData,
                                                  'knowledge'=>$knowledge,
                                                  'know_arr'=>$know_arr

        ]);
 
    }

    public function question_add(){
        $editType   = $this->get_in_int_val('editType');
        $question_id   = $this->get_in_int_val('question_id');
        $data = [];
        $data['subject']  = $this->get_in_int_val('subject',1);
        $data['score']    = $this->get_in_int_val('score',1);
        $data['title']    = $this->get_in_str_val('title','');
        $data['detail']   = $this->get_in_str_val('detail','');
        $data['open_flag']   = $this->get_in_str_val('open_flag',1);
        $data['difficult']   = $this->get_in_str_val('difficult',1);

        if( $editType == 1 ){
            $ret = $this->t_question->row_insert($data);
            if($ret){
                $result['status'] = 200;
                $result['msg'] = "添加成功";
            }else{
                $result['status'] = 500;
                $result['msg'] = "添加失败";
            }
            return $this->output_succ($result); 
        }

        if( $editType == 2 ){
            $ret = $this->t_question->field_update_list($question_id,$data);
            if($ret){
                $result['status'] = 200;
                $result['msg'] = "更新成功";
            }else{
                $result['status'] = 500;
                $result['msg'] = "更新失败";
            }
            return $this->output_succ($result); 

        }
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
        $subject   = $this->get_in_int_val('id_subject',1);
        $where_arr = [
            ["subject=%d" , $subject,-1 ],
        ];

        $ret_list = $this->t_knowledge_point->knowledge_list($where_arr,null);
        $ret = [];
        if($ret_list['list']){
        
            foreach( $ret_list['list'] as &$item){
                $arr['name'] = $item['title'];
                $arr['id'] = $item['knowledge_id'];
                $arr['pId'] = $item['father_id'];
                $arr['editType'] = 2;
                $ret[] = $arr;
            }
        }
        //dd($ret);
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712141356",'ret'=> json_encode($ret)]);
    }

    public function knowledge_get(){
        $knowledge_id = $this->get_in_int_val('knowledge_id','');
        $level = $this->get_in_int_val('level',0);
        $file_path = "/home/bacon/admin_yb1v1/flare.json";
        $jsonData = '';
        if(file_exists($file_path)){
            $jsonData = file_get_contents($file_path);
        }
        //dd($jsonData);
        return $jsonData;
    }

    public function knowledge_edit(){
        $editType   = $this->get_in_int_val('editType',1); //1:添加 2:编辑
        $knowledge_id = $this->get_in_int_val('knowledge_id','');
        $level = $this->get_in_int_val('level','');
        $father_id = $this->get_in_int_val('father_id',''); //添加在哪个父级下的子知识点
        $father_subject = $this->get_in_int_val('father_subject',1);

        $describe = '';

        if($editType == 1 && $level == 0){
            $describe = "添加根知识点";
        }
        if( $editType == 2 ){
            $describe = "编辑知识点";
        }
        $ret = [
            'editType' => $editType,
            'father_id' => $father_id,
            'father_subject' => $father_subject,
            'knowledge_id' => $knowledge_id,
            'level' => $level,
        ];
       
        if($father_id){
            $father = $this->t_knowledge_point->get_by_id($father_id);
            if($father){
                $describe = "为 ".$father['title']." 添加子知识点";
            }
        }

        $ret['describe'] = $describe;

        $editData = [];
        if($knowledge_id){
            $editData = $this->t_knowledge_point->get_by_id($knowledge_id);
            $ret['father_id'] = @$editData['father_id'];
            $ret['level'] = @$editData['level'];
        }
        $editData = json_encode($editData);
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712121456","ret"=>$ret,"editData"=>$editData]);
    }

    public function knowledge_add(){
        $editType   = $this->get_in_int_val('editType',1);
        $knowledge_id   = $this->get_in_int_val('knowledge_id',0);
        $data = [];
        $data['subject'] = $this->get_in_int_val('subject',1);
        $data['title']   = $this->get_in_str_val('title');
        $data['detail']   = $this->get_in_str_val('detail','');
        $data['open_flag']   = $this->get_in_int_val('open_flag',1);

        $levData = [];
        $levData['father_id']   = $this->get_in_int_val('father_id',0);

        if( $editType == 1 ){
            $ret = $this->t_knowledge_point->row_insert($data);
            if($ret){
                $levData['knowledge_id'] = $this->t_knowledge_point->get_last_insertid();
                $retinfo = $this->t_knowledge_level->row_insert($levData);
                if($retinfo){
                    $result['knowledge_id'] = $levData['knowledge_id'];
                    $result['status'] = 200;
                    $result['msg'] = "添加成功";

                }else{
                    $result['status'] = 500;
                    $result['msg'] = "知识点添加成功，父级添加失败";
                }         
            }else{
                $result['status'] = 500;
                $result['msg'] = "添加失败";
            }
            return $this->output_succ($result); 
        }

        if( $editType == 2 ){
            $ret = $this->t_knowledge_point->field_update_list($knowledge_id,$data);
            if($ret){
                $result['status'] = 201;
                $result['msg'] = "更新成功";
            }else{
                $result['status'] = 500;
                $result['msg'] = "更新失败";
            }
            return $this->output_succ($result); 

        }

    }

    public function knowledge_dele(){
        $knowledge_id = $this->get_in_int_val('knowledge_id');
        $deleNum = $this->t_knowledge_point->del_by_id($knowledge_id);
        if($deleNum){
            $result['status'] = 200;
            $result['msg'] = "删除成功";
        }else{
            $result['status'] = 500;
            $result['msg'] = "删除失败";
        }
        return $this->output_succ($result); 
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
    public function answer_edit(){
        $question_id   = $this->get_in_int_val('question_id');
        $where_arr = [
            ["question_id=%d" , $question_id ],
        ];

        $ret = $this->t_answer->answer_list($where_arr);
        $next_step = 10;
        $i = 1;
        $type = 1;
        if($ret){
            foreach( $ret as &$item ){
                $item['difficult_str'] = E\Equestion_difficult_new::get_desc($item['difficult']);
                if( $type == $item['answer_type']){
                    $item['step_str'] = E\Eanswer_type::get_desc($type).$i;
                    $i++;
                }else{
                    $type = $item['answer_type'];
                    $i = 1;
                    $item['step_str'] = E\Eanswer_type::get_desc($type).$i;
                }

            }
            $next_step = (int)end($ret)['step'] + 10;
        }

        //获取题目信息
        $question_info = $this->t_question->get_question_info($question_id);
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712131058",
                                                  'ret'=>$ret,
                                                  'next_step'=>$next_step,
                                                  'question'=>$question_info
        ]);
        
    }

    public function answer_add(){
        $editType   = $this->get_in_int_val('editType',0);
        $step = $this->get_in_int_val('step');
        $answer_id = $this->get_in_int_val('answer_id');
        $question_id = $this->get_in_int_val('question_id');
        $answer_type = $this->get_in_int_val('answer_type');
        $difficult   = $this->get_in_int_val('difficult',1);
        $score   = $this->get_in_int_val('score',0);
        $detail   = $this->get_in_str_val('detail','');

        
        $data = [
            "step"   => $step,
            "question_id"   => $question_id,
            "answer_type"   => $answer_type,
            "difficult"   => $difficult,
            "score"   => $score,
            "detail"   => $detail,
        ];
        //dd($data);
        // dd($editType);
        if( $editType == 1 ){
            $ret = $this->t_answer->row_insert($data);
            if($ret){
                $result['status'] = 200;
                $result['msg'] = "添加成功";
            }else{
                $result['status'] = 500;
                $result['msg'] = "添加失败";
            }
            return $this->output_succ($result); 
        }

        if( $editType == 2 ){
            $ret = $this->t_answer->field_update_list($answer_id,$data);
            if($ret){
                $result['status'] = 200;
                $result['msg'] = "更新成功";
            }else{
                $result['status'] = 500;
                $result['msg'] = "更新失败";
            }
            return $this->output_succ($result); 

        }

    }

    public function answer_dele(){
        $answer_id = $this->get_in_int_val('answer_id');
        $this->t_answer->del_by_id($answer_id);
        return $this->output_succ(); 
    }

}