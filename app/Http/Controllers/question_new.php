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
            ["qu.subject=%d" , $subject,-1 ],
            ["qu.open_flag=%d" , $open_flag,-1 ],
        ];
        $page_num        = $this->get_in_page_num();
        $ret_list = $this->t_question->question_list($where_arr,$page_num);
        if($ret_list){
            foreach( $ret_list['list'] as &$item ){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']);
                $item['open_str'] = E\Eboolean::get_desc($item['open_flag']);
                $knowledge_detail =$this->t_question_knowledge->question_know_get($item['question_id']);
                $item['knowledge_detail'] = json_encode($knowledge_detail);
            }
        }
        return $this->pageView(__METHOD__,$ret_list, [ "_publish_version" => "201712221556"]);
    }

    public function question_edit(){
        $editType   = $this->get_in_int_val('editType',1); //1:添加 2:编辑
        $question_id   = $this->get_in_int_val('question_id');
        $subject   = $this->get_in_int_val('subject');

        $ret = [
            'describe' => '新增题目',
        ];

        //取出当前科目对应的题型
        $question_type = $this->t_question_type->question_type_list($subject,1);

        //编辑题目
        $editData = [];
      
        //题目对应的知识点id
        $know_arr = [];

        if($question_id){
            //取出题目
            $editData = $this->t_question->get_by_id($question_id);
            $ret['describe'] = '编辑题目';

            //取出题目对应的知识点
            $know_arr = $this->t_question_knowledge->question_know_get($question_id);
       
        }
        $editData = json_encode($editData);

        $where_arr = [
            ["subject=%d" , $subject,-1 ],
        ];

        $know_list = $this->t_knowledge_point->knowledge_list($where_arr,null);
        //所有知识点
        $knowledge = [];
        if($know_list['list']){              
            foreach( $know_list['list'] as &$item){
                $arr['name'] = $item['title'];
                $arr['id'] = $item['knowledge_id'];
                $arr['pId'] = $item['father_id'];
                $arr['editType'] = 2;
                $knowledge[] = $arr;
            }
        }
        //dd($know_arr);

        $knowledge = json_encode($knowledge);
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712181447",
                                                  "ret"=>$ret,
                                                  'editData'=>$editData,
                                                  'knowledge'=>$knowledge,
                                                  'know_arr'=>$know_arr,
                                                  'question_id' =>$question_id,
                                                  'question_type' => $question_type

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
        $data['question_type']   = $this->get_in_int_val('question_type',1);
        $data['question_resource_name']   = $this->get_in_str_val('question_resource_name','');
        $data['question_resource_type']   = $this->get_in_int_val('question_resource_type',1);

        $knowledge_old   = $this->get_in_str_val('knowledge_old','');
        $knowledge_new   = $this->get_in_str_val('knowledge_new','');

        $knowledge_old = !empty($knowledge_old) ? array_column( json_decode($knowledge_old,true),'knowledge_id' ):[];
        $knowledge_new = !empty($knowledge_new) ? explode(',',$knowledge_new):[];
       
        if( $editType == 1 ){
            $ret = $this->t_question->row_insert($data);
            if($ret){
                $question_id = $this->t_question->get_last_insertid();
                $this->question_or_answer_know_add($question_id,$knowledge_old,$knowledge_new,1);
                $result['status'] = 200;
                $result['question_id'] = $question_id;
                $result['msg'] = "添加成功";
            }else{
                $result['status'] = 500;
                $result['msg'] = "添加失败";
            }
            return $this->output_succ($result); 
        }

        if( $editType == 2 ){
            $this->question_or_answer_know_add($question_id,$knowledge_old,$knowledge_new,1);
            $ret = $this->t_question->field_update_list($question_id,$data);
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

    //录入题目相似度检测
    public function question_similar_check($subject,$question_type){
        
    }

    //添加题目对应的知识点
    public function question_or_answer_know_add($id,$old,$new,$type){
        $delArr = array_diff($old,$new);
        $addArr = array_diff($new,$old);
        //删除
        if(!empty($delArr)){
            $deleNum = $this->t_question_knowledge->dele_by_id_arr($id,$delArr,$type); 
        }
        //添加
        if(!empty($addArr)){
            $addNum = $this->t_question_knowledge->add_id_arr($id,$addArr,$type);
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
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712161356",'ret'=> json_encode($ret)]);
    }

    public function knowledge_get(){

        $type = $this->get_in_int_val('type');
        $subject = $this->get_in_int_val('subject',5);
        if( $type == 1){
            $where_arr = [
                ["subject=%d" , $subject,-1 ],
            ];

            $ret_list = $this->t_knowledge_point->knowledge_list($where_arr,null);
            $ret = [];
            $jsonData = '';
            if($ret_list['list']){       
                foreach( $ret_list['list'] as &$item){
                    $arr['name'] = $item['title'];
                    $arr['id'] = $item['knowledge_id'];
                    $arr['pid'] = $item['father_id'];
                    $ret[] = $arr;
                }
            }else{
                // $file_path = "/home/bacon/下载/flare.json";
                // $jsonData = '';
                // if(file_exists($file_path)){
                //     $jsonData = file_get_contents($file_path);
                // }
                dd('暂无知识点,请添加');
                return $jsonData;
            }
            $ret = $this->get_tree($ret,0);
            $arr = [ 'name'=>E\Esubject::get_desc($subject)."知识点", 'children' => $ret ];
            $jsonData = json_encode($arr);
            //dd($arr);
            //dd($jsonData);
            return $jsonData;
        }
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712141356",'subject' => $subject ]);
        
    }

    private function get_tree($a,$pid){  
        $tree = array();                                //每次都声明一个新数组用来放子元素  
        foreach($a as $v){  
            if($v['pid'] == $pid){                      //匹配子记录  
                $v['children'] = $this->get_tree($a,$v['id']); //递归获取子记录  
                if($v['children'] == null){  
                    unset($v['children']);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）  
                }
                // $arr['name'] = $v['name'];
                // $arr['children'] = @$v['children'];
                $tree[] = $v;                           //将记录存入新数组  
            }  
        }  
        return $tree;                                  //返回新数组  
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
        $knowledge_id = $this->get_in_int_val('knowledge_id'); //删除单个
        $idstr = $this->get_in_str_val('idstr');               //删除多个

        if($knowledge_id && empty($idstr) ){
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

        if( $idstr && empty( $knowledge_id ) ){
            $know_arr = explode(',', $idstr);
            $deleNum = 0;
            if($know_arr){
                foreach( $know_arr as $know ){
                    $deleNum += $this->t_knowledge_point->del_by_id($know);                   
                }
            }
            if($deleNum > 0){
                $result['status'] = 200;
                $result['msg'] = "删除成功";
            }else{
                $result['status'] = 500;
                $result['msg'] = "删除失败";
            }
            return $this->output_succ($result); 
        }
    
    }

    public function answer_edit(){
        $question_id   = $this->get_in_int_val('question_id');
        $answer_no = $this->get_in_int_val('answer_no',0);
        $where_arr = [
            ["question_id=%d" , $question_id ],
            ["answer_no=%d" , $answer_no ],
        ];
        $ret = $this->t_answer->answer_list($where_arr);
        $next_step = 10;
        $i = 0;
        $type = 1;
        if($ret){
            foreach( $ret as &$item ){
                $item['difficult_str'] = E\Equestion_difficult_new::get_desc($item['difficult']);
                if( $type == $item['answer_type']){
                    $i++;
                    $item['step_str'] = $item['answer_type_name'].$i;
                }else{
                    $type = $item['answer_type'];
                    $i = 1;
                    $item['step_str'] = $item['answer_type_name'].$i;
                }

                //取出题目对应的知识点
                $item['know_str'] = '';
                $know_arr = $this->t_question_knowledge->answer_know_get($item['step_id']);
                if($know_arr){
                    $item['know_str'] = json_encode($know_arr);
                }

            }
            $next_step = (int)end($ret)['step'] + 10;
        }

        //获取题目信息
        $question_info = $this->t_question->get_question_info($question_id);

        //所有知识点
        $knowledge = [];

        //查找该科目的解题类型
        $answer_type = [];

        if($question_info){        
            $where_arr = [
                ["subject=%d" , $question_info['subject'] ],
            ];

            $know_list = $this->t_knowledge_point->knowledge_list($where_arr,null);
            //所有知识点
            $knowledge = [];
            if($know_list['list']){              
                foreach( $know_list['list'] as &$item){
                    $arr['name'] = $item['title'];
                    $arr['id'] = $item['knowledge_id'];
                    $arr['pId'] = $item['father_id'];
                    $arr['editType'] = 2;
                    $knowledge[] = $arr;
                }
            }

            //查找该科目的解题类型
            $answer_type = $this->t_answer_type->answer_type_list($question_info['subject'],1);

        }
        $knowledge = json_encode($knowledge);
        
        //查找是否有其他标准答案
        $other_answers = $this->t_answer->answer_others($question_id,$answer_no);
     
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712221148",
                                                  'ret'=>$ret,
                                                  'next_step'=>$next_step,
                                                  'question'=>$question_info,
                                                  'knowledge'=>$knowledge,
                                                  'other_answers'=>$other_answers,
                                                  'answer_type'=>$answer_type,
                                                  'answer_no' => $answer_no
        ]);
        
    }

    public function answer_add(){
        $editType   = $this->get_in_int_val('editType',1);
        $step = $this->get_in_int_val('step');
        $step_id = $this->get_in_int_val('step_id');
        $question_id = $this->get_in_int_val('question_id');
        $answer_type = $this->get_in_int_val('answer_type');
        $answer_no = $this->get_in_int_val('answer_no');
        $difficult   = $this->get_in_int_val('difficult',1);
        $score   = $this->get_in_str_val('score',0);
        $detail   = $this->get_in_str_val('detail','');

        $knowledge_old   = $this->get_in_str_val('knowledge_old','');
        $knowledge_new   = $this->get_in_str_val('knowledge_new','');

        $knowledge_old = !empty($knowledge_old) ? array_column( json_decode($knowledge_old,true),'knowledge_id' ):[];
        $knowledge_new = !empty($knowledge_new) ? explode(',',$knowledge_new):[];

        $data = [
            "step"   => $step,
            "question_id"   => $question_id,
            "answer_type"   => $answer_type,
            "difficult"   => $difficult,
            "score"   => $score,
            "detail"   => $detail,
            "answer_no" => $answer_no
        ];

        //dd($data);
        //dd($editType);

        if( $editType == 1 ){
            $ret = $this->t_answer->row_insert($data);
            if($ret){
                $step_id = $this->t_answer->get_last_insertid();
                $this->question_or_answer_know_add($step_id,$knowledge_old,$knowledge_new,2);

                $result['status'] = 200;
                $result['msg'] = "添加成功";
            }else{
                $result['status'] = 500;
                $result['msg'] = "添加失败";
            }
            return $this->output_succ($result); 
        }

        if( $editType == 2 ){
            $this->question_or_answer_know_add($step_id,$knowledge_old,$knowledge_new,2);
            $ret = $this->t_answer->field_update_list($step_id,$data);
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
        $step_id = $this->get_in_int_val('step_id');
        $this->t_answer->del_by_id($step_id);
        return $this->output_succ(); 
    }

    public function textbook_knowledge_list(){     
        $textbook_id = $this->get_in_int_val('id_textbook');
        $subject = $this->get_in_int_val('id_subject',1);
        $grade = $this->get_in_int_val('id_grade',301);
        //获取该科目所有的教材
        $textbook = $this->t_textbook->textbook_get_by_subject($subject);
        if($textbook){
            $text_arr = array_column($textbook, 'textbook_id');
            if( empty($textbook_id) || !in_array($textbook_id, $text_arr))
                $textbook_id = $text_arr[0];
        }
       
        $where_arr = [
            ["subject=%d" , $subject,-1 ],
        ];
        $exit_list = $this->t_textbook_knowledge->textbook_knowledge_get($textbook_id,$grade,$subject);
        $exit_know = [];
        $exit_id_arr = [];
        if($exit_list){       
            foreach( $exit_list as &$item){
                $arr['name'] = $item['title'];
                $arr['id'] = $item['knowledge_id'];
                $arr['pId'] = $item['father_id'];
                $exit_know[] = $arr;
            }
            $exit_id_arr = array_column($exit_know, 'id');
        }

        $ret_list = $this->t_knowledge_point->knowledge_list($where_arr,null);
        $ret = [];
        if($ret_list['list']){       
            foreach( $ret_list['list'] as &$item){
                $arr['name'] = $item['title'];
                $arr['id'] = $item['knowledge_id'];
                $arr['pId'] = $item['father_id'];
                if( $exit_id_arr && in_array($item['knowledge_id'], $exit_id_arr)){
                    $arr['checked'] = true;
                }else{
                    $arr['checked'] = false;
                }
                $ret[] = $arr;
            }
        }
        //dd($ret);
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712181048",'ret'=> json_encode($ret),'exit_know'=> json_encode($exit_know),'textbook'=>$textbook,'textbook_id'=>$textbook_id ]);
    }

    public function textbook_knowledge_add(){
        $textbook_id = $this->get_in_int_val('textbook_id');
        $subject = $this->get_in_int_val('subject');
        $grade = $this->get_in_int_val('grade');

        $knowledge_old   = $this->get_in_str_val('knowledge_old','');
        $knowledge_new   = $this->get_in_str_val('knowledge_new','');

        $knowledge_old = !empty($knowledge_old) ? array_column( json_decode($knowledge_old,true),'id' ):[];
        $knowledge_new = !empty($knowledge_new) ? explode(',',$knowledge_new):[];

        $delArr = array_diff($knowledge_old,$knowledge_new);
        //dd($delArr);
        $addArr = array_diff($knowledge_new,$knowledge_old);
        //删除
        $deleNum = 0;
        if(!empty($delArr)){
            $deleNum = $this->t_textbook_knowledge->dele_by_id_arr($textbook_id,$grade,$subject,$delArr); 
        }
        //添加
        $addNum = 0;
        if(!empty($addArr)){
            $addNum = $this->t_textbook_knowledge->add_id_arr($textbook_id,$grade,$subject,$addArr);
        }

        if($addNum !=0 || $deleNum != 0){
            $result['status'] = 200;
            $result['msg'] = "更新成功";
            return $this->output_succ($result);
        }else{
            $result['status'] = 500;
            $result['msg'] = "更新失败";
            return $this->output_succ($result);
        } 
    }

    public function textbook_list(){
        $subject   = $this->get_in_int_val('id_subject',1);
        $open_flag   = $this->get_in_int_val('id_open_flag',1);
        $textbook = $this->t_textbook->textbook_list($subject,$open_flag);
        if($textbook){
            foreach($textbook as &$item){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']); ;
            }
        }
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712181048",'textbook'=> $textbook]);
    }

    public function textbook_add(){
        $editType   = $this->get_in_int_val('editType',1); //1:add 2:update
        $textbook_id   = $this->get_in_int_val('textbook_id');
        $data['name']  = $this->get_in_str_val('name');
        $data['subject']  = $this->get_in_int_val('subject');
        $data['open_flag']  = $this->get_in_int_val('open_flag',1);

        if( $editType == 1 ){
            $is_exit = $this->t_textbook->is_exit($data['name'],$data['subject']);
            if($is_exit){
                $result['status'] = 500;
                $result['msg'] = "该版本已经存在，请不要重复添加";
                return $this->output_succ($result); 
            }
            $ret = $this->t_textbook->row_insert($data);
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
            $ret = $this->t_textbook->field_update_list($textbook_id,$data);
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

    public function textbook_dele(){
        $textbook_id = $this->get_in_int_val('textbook_id');
        $this->t_textbook->del_by_id($textbook_id);
        return $this->output_succ(); 
    }

    public function question_type_list(){
        $subject   = $this->get_in_int_val('id_subject',1);
        $open_flag   = $this->get_in_int_val('id_open_flag',1);
        $question_type = $this->t_question_type->question_type_list($subject,$open_flag);
        if($question_type){
            foreach($question_type as &$item){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']); ;
            }
        }
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712181048",'question_type'=> $question_type]);
    }

    public function question_type_add(){
        $editType   = $this->get_in_int_val('editType',1); //1:add 2:update
        $id   = $this->get_in_int_val('id');
        $data['name']  = $this->get_in_str_val('name');
        $data['subject']  = $this->get_in_int_val('subject');
        $data['open_flag']  = $this->get_in_int_val('open_flag',1);

        if( $editType == 1 ){
            $is_exit = $this->t_question_type->is_exit($data['name'],$data['subject']);
            if($is_exit){
                $result['status'] = 500;
                $result['msg'] = "该题型已经存在，请不要重复添加";
                return $this->output_succ($result); 
            }
            $ret = $this->t_question_type->row_insert($data);
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
            $ret = $this->t_question_type->field_update_list($id,$data);
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

    public function question_type_dele(){
        $question_type_id = $this->get_in_int_val('question_type_id');
        $this->t_question_type->del_by_id($question_type_id);
        return $this->output_succ(); 
    }

    public function answer_type_list(){
        $subject   = $this->get_in_int_val('id_subject',1);
        $open_flag   = $this->get_in_int_val('id_open_flag',1);
        $list = $this->t_answer_type->answer_type_list($subject,$open_flag);
        if($list){
            foreach($list as &$item){
                $item['subject_str'] = E\Esubject::get_desc($item['subject']); ;
            }
        }
        return $this->pageView(__METHOD__,null, [ "_publish_version" => "201712181048",'list'=> $list]);
    }

    public function answer_type_add(){
        $editType   = $this->get_in_int_val('editType',1); //1:add 2:update
        $id   = $this->get_in_int_val('id');
        $data['answer_type_no']  = $this->get_in_int_val('answer_type_no');
        $data['name']  = $this->get_in_str_val('name');
        $data['subject']  = $this->get_in_int_val('subject');
        $data['open_flag']  = $this->get_in_int_val('open_flag',1);

        if( $editType == 1 ){
            $is_exit = $this->t_answer_type->is_exit($data['name'],$data['subject']);
            if($is_exit){
                $result['status'] = 500;
                $result['msg'] = "该科目的步骤类型已经存在，请不要重复添加";
                return $this->output_succ($result); 
            }
            $ret = $this->t_answer_type->row_insert($data);
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
            $ret = $this->t_answer_type->field_update_list($id,$data);
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

}