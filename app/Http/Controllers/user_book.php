<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class user_book extends Controller
{

    public function phone_user_list(){
        global $g_request;
        /** @var $g_request Illuminate\Http\Request */

        $g_request->offsetSet("register_flag",1);

        return $this->get_booked_user();
    }
    
    public function phone_user_list_all(){
        global $g_request;
        //$g_request->register_flag=1;
        /** @var $g_request Illuminate\Http\Request */

        $g_request->offsetSet("register_flag",-1);
        return $this->get_booked_user();
    }

    
    public function get_tea_schedule()
    {
        $start_date = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-3*86400 ));
        $end_date   = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $phone      = trim($this->get_in_str_val('phone',''));
        $page_num   = $this->get_in_page_num();
        
        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;
        
        $ret_info   = $this->t_book_info->get_tea_schedule($start_date_s,$end_date_s,$phone,$page_num);
        $ret_lesson = array();

        foreach($ret_info['list'] as &$item){
            $item['phone']     = trim($item['phone']);
            $item['book_time'] = date("Y-m-d H:i:s",$item['book_time']);
            
            if($item['book_time_next']>0){
                $item['book_time_next'] = date("Y-m-d",$item['book_time_next']);
            }

            $ret_lesson=$this->t_lesson_info->get_lesson_info_by_userid($item['userid']);
            $item['lesson_num']=$ret_lesson['lesson_count'];
            $item['order_time']=$ret_lesson==0?'未排试听课':date('Y-m-d H:i:s',$ret_lesson['lesson_start']);
            if($ret_lesson['teacherid']>0){
                $item['tea_nick']=$this->t_teacher_info->get_nick($ret_lesson['teacherid']);
            }else{
                $item['tea_nick']='';
            }

            E\Ebook_status::set_item_value_str($item, "status");
            E\Ebook_grade::set_item_value_str($item, "grade");
            E\Etrial_type::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad" );
            \App\Helper\Utils::unixtime2date_for_item( $item,"class_time" );
            \App\Helper\Utils::unixtime2date_for_item( $item,"sys_opt_time" );
        }
        
        return $this->Pageview(__METHOD__,$ret_info);
    }

    public function get_booked_user()
    {
        $register_flag = $this->get_in_int_val('register_flag',0);
        $class_time    = $this->get_in_int_val('class_time',-1);

        $type              = $this->get_in_int_val('type',1);
        $start_date        = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-3*86400 ));
        $end_date          = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $grade             = $this->get_in_int_val('grade',-1);
        $status            = $this->get_in_int_val('status',-1);
        $trial_type        = $this->get_in_int_val('trial_type',-1);
        $page_num          = $this->get_in_page_num();
        $sys_operator_type = $this->get_in_int_val("sys_operator_type");

        $book_user    = trim($this->get_in_str_val('book_user', ''));
        $book_origin  = trim($this->get_in_str_val('book_origin', ''));
        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;
        //logger("xxx");

        $ret_info = $this->t_book_info->get_booked_user($register_flag,$class_time,$type,$start_date_s,$end_date_s,
                                                        $grade,$status,$page_num,$book_user,$book_origin, $trial_type,
                                                        $sys_operator_type,$this->get_account());

        foreach($ret_info['list'] as &$item){
            $item['phone']     = trim($item['phone']);
            $item['book_time'] = date("Y-m-d H:i:s",$item['book_time']);
            if($item['book_time_next']>0){
                $item['book_time_next'] = date("Y-m-d",$item['book_time_next']);
            }
            $item['opt_time'] = ($type==1?  $item["book_time"] : $item["book_time_next"] );

            E\Ebook_status::set_item_value_str($item, "status");
            E\Ebook_grade::set_item_value_str($item, "grade");

            E\Etrial_type::set_item_value_str($item);
            E\Esubject::set_item_value_str($item);
            E\Epad_type::set_item_value_str($item,"has_pad" );
            \App\Helper\Utils::unixtime2date_for_item( $item,"class_time" ) ;
            \App\Helper\Utils::unixtime2date_for_item( $item,"sys_opt_time" ) ;
        }
        //处理disp
        $is_phone_user = ($register_flag==1);

        return $this->Pageview(__METHOD__,$ret_info, [
            "display_add_str"            => css_display ( $is_phone_user),
            "display_td_lesson_time_str" => css_display ( !$is_phone_user),
            "display_sys_operator_str"   => css_display ( !($sys_operator_type ==1 ) ),
        ]);
    }

    public function set_sys_operator() {
        $phone   = $this->get_in_str_val("phone","");
        $account = $this->get_account();
        $ret     = $this->t_book_info->set_sys_operator($phone,$account);
        return outputjson_ret( $ret);
    }

    public function add_book_info() {
        $phone        = $this->get_in_str_val("phone","");
        $nick         = $this->get_in_str_val("nick","");
        $subject      = $this->get_in_subject();
        $grade        = $this->get_in_grade();
        $status       = $this->get_in_int_val( "status" );
        $consult_desc = $this->get_in_str_val( "consult_desc" );

        $ret = $this->t_book_info->row_insert([
            "phone"         => $phone,
            "nick"          => $nick,
            "grade"         => $grade,
            "subject"       => $subject,
            "status"        => $status,
            "consult_desc"  => $consult_desc,
            "book_time"     => time(null),
            "sys_operator"  => $this->get_account(),
            "sys_opt_time"  => time(null),
            "origin"        => "后台添加",
            "register_flag" => 1,
        ]);
        return outputjson_ret($ret);
    }
    
    public function add_book_revisit()
    {
        $phone   = $this->get_in_str_val('phone', '');
        $op_note = $this->get_in_str_val('op_note', '');

        $sys_operator = $this->get_account(); 

        if ($op_note == '' || $phone == '') {
            return outputJson(array('ret' => -1, 'info' => '回访记录不能为空'));
        }
        $ret_add = $this->t_book_revisit->add_book_revisit($phone,$op_note,$sys_operator);
        if ($ret_add === false) {
           return outputJson(array('ret' => -1, 'info' => '系统错误'));
        }

        return outputJson(array('ret' => 0, 'info' => '添加成功'));
    }
    
    public function get_book_revisit()
    {
        $phone = $this->get_in_str_val('phone', '');

        if (empty($phone)) {
           return outputJson(array('ret' => -1, 'info' => '手机号不能为空'));
        }

        $ret_list = $this->t_book_revisit->get_book_revisit_list($phone);
        if ($ret_list === false) {
            return outputJson(array('ret' => -1, 'info' => '系统错误'));
        }

        $revisit_list = array();
        foreach($ret_list as $revisit_record) {
            $revisit_list[] = array(
                'revisit_time' => date('Y-m-d H:i:s', $revisit_record['revisit_time']),
                'op_note'      => $revisit_record['operator_note'],
            );
        }

        return outputJson(array('ret' => 0, 'info' => '成功', 'revisit_list' => $revisit_list));
    }
    
    public function update_user_info(){
        $phone   = $this->get_in_str_val('phone', '');
        $op_note = $this->get_in_str_val('op_note', '');
        $status = $this->get_in_int_val('status', 0);
        $note   = $this->get_in_str_val('note', 0);
        $sys_operator = $_SESSION['acc'];


        if ($op_note) {
            $ret_update = $this->t_book_revisit->add_book_revisit($phone,$op_note,$sys_operator);
            if ($ret_update === false) {
                return outputJson(array('ret' => -1, 'info' => '系统错误'));
            }

        }

        //origin
        //$phone  = $this>get_in_str_val('phone', '');
        $ret_info = $this->t_book_info->update_user_info($phone,$status,$note);

        if ($ret_info === false) {
           return outputJson(array('ret' => -1, 'info' => '系统错误'));
           }
        return outputJson(array('ret' => 0, 'info' => '成功'));
    }
    
    public function update_book_time_next(){
        $phone          = $this->get_in_str_val('phone', '');
        $book_time_next = $this->get_in_str_val('book_time_next', 0);

        $book_time_next_s = strtotime($book_time_next);
        $ret_info = $this->user_book_model->update_book_time_next($phone,$book_time_next_s);

        if ($ret_info === false) {
            outputJson(array('ret' => -1, 'info' => '系统错误'));
        }
        outputJson(array('ret' => 0, 'info' => '成功'));
    }
    
    //设置时间lala
    public function set_class_time()
    {
        $id= $this->get_in_str_val('id');
        $class_time= strtotime($this->get_in_str_val("class_time"));


        $ret_set = $this->t_book_info->field_update_list($id,[
            "class_time" => $class_time
        ]);

        if ($ret_set == false) {
            return outputJson(array('ret' => -1, 'info' => '设置失败'));
        }
        return outputJson(array('ret' => 0, 'info' => '设置成功'));
    }

    //排课lala  设置老师
    public function set_course_teacher()
    {
        $teacherid= $this->get_in_int_val('teacherid',-1);
        $courseid= $this->get_in_int_val('courseid',-1);
         
        $ret_teacher = $this->t_book_info->set_course_teacher($teacherid,$courseid);
        if ($ret_teacher === false) {
            return outputjson_error();
        }

        return outputjson_success();
    }

}
