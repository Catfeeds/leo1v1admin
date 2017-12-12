<?php
namespace App\Models;
use \App\Enums as E;
class t_cr_week_month_info extends \App\Models\Zgen\z_t_cr_week_month_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_data_by_type($create_time,$type){
        $where_arr = [
            ["create_time=%u",$create_time,-1],
            ["type=%u",$type,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_row($sql);
    }
    public function get_student_list_new($type,$create_time){
    	if($type == 2 || $type == 3){
    		$where_arr = [
	            ["create_time=%u",$create_time,-1],
	            " type=2 or type =3",
	        ];
    	}else if($type ==1){
    		$where_arr = [
	            ["create_time=%u",$create_time,-1],
	            " type=1",
	        ];
    	}

        $sql = $this->gen_sql_new("select student_list  from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);

    }
    public function get_info_by_type_and_time($type,$create_time){
        $where_arr = [
            ["create_time=%u",$create_time,-1],
            ["type=%u",$type,-1]
        ];
        $sql = $this->gen_sql_new("select id from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_all_info_by_type_and_time($type,$create_time){
        $where_arr = [
            ["create_time=%u",$create_time,""],
            ["type=%u",$type,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_tongji(){
        $sql = "select count(s.userid) as total_student , sum(if(o.orderid>0 and contract_type = 0 and   contract_status>0, 1,0)) as total_order,  sum(if(o.orderid>0 and contract_type = 3 and   contract_status>0, 1,0)) as total_renew_order , sum(if(k.global_tq_called_flag=2,1,0)) as total_call from db_weiyi.t_student_info s left join db_weiyi.t_order_info o on s.userid = o.userid left join db_weiyi.t_seller_student_new k on k.userid = s.userid where reg_time > 1475251200 and reg_time < 1506787200 and is_test_user  =0 ";
        return $this->main_get_row($sql);
    }
    public function get_tongji2(){
        $sql = "select userid,phone from t_student_info where phone_location='免商店充值卡 ' or phone_location='' or phone_location = '鹏博士' and is_test_user = 0  and reg_time > 1475251200 and reg_time < 1506787200 ";
        return $this->main_get_list($sql);
    }
    public function get_teacher_info(){
        $sql = "select * from t_teacher_info where  train_through_new_time > 1501516800 and train_through_new_time < 1509465600 and is_test_user  = 0";
        return $this->main_get_list($sql);
    }
    public function get_all_teacher_info(){
        $sql = "select * from t_teacher_info where  is_test_user  = 0";
        return $this->main_get_list($sql);
    }
    public function get_lesson_teacher_info(){
        $sql = "select distinct(t.teacherid)  ,t.phone, t.phone_location from t_lesson_info l left join t_teacher_info t on t.teacherid = l.teacherid where lesson_start > 1501516800 and lesson_start < 1509465600 and  lesson_del_flag=0  and  lesson_type<1000 and t.is_test_user = 0";
        return $this->main_get_list($sql);
    }
    //-------------------------------------------------------
    public function get_total_province($start_time,$end_time){
        $where_arr = [
            ["reg_time>%u",$start_time,-1],
            ["reg_time<%u",$end_time,-1],
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select count(userid) as total,phone_location from t_student_info where %s group by phone_location", $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_total_grade_num($start_time,$end_time){
         $where_arr = [
            ["reg_time>%u",$start_time,-1],
            ["reg_time<%u",$end_time,-1],
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select t.grade ,count(s.userid) as total from t_student_info s  left join t_seller_student_new k on s.userid = k.userid left join t_test_lesson_subject t on t.userid = s.userid where %s group by t.grade", $where_arr);
        dd($sql);
        return $this->main_get_list($sql);
    }
    public function get_total_subject_num($start_time,$end_time){
         $where_arr = [
            ["reg_time>%u",$start_time,-1],
            ["reg_time<%u",$end_time,-1],
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select t.subject ,count(s.userid) as total from t_student_info s  left join t_seller_student_new k on s.userid = k.userid left join t_test_lesson_subject t on t.userid = s.userid where %s group by t.subject", $where_arr);
        return $this->main_get_list($sql);
    }
    //-------------------------------------------------------------
    public function get_order_province($start_time,$end_time){
        $where_arr = [
            ["order_time>%u",$start_time,-1],
            ["order_time<%u",$end_time,-1],
            "is_test_user=0",
            "contract_type =0 ",
            "contract_status>0 ",
            " price>0"
        ];
        $sql = $this->gen_sql_new("select count( s.userid) as total, s.phone_location from t_order_info o  left join t_student_info s on s.userid = o.userid  where %s group by phone_location", $where_arr);
        return $this->main_get_list($sql);
    }
    public function get_total_order_grade_num($start_time,$end_time){
        $where_arr = [
            ["order_time>%u",$start_time,-1],
            ["order_time<%u",$end_time,-1],
            "is_test_user=0",
            "contract_type =0 ",
            "contract_status>0 ",
            " price>0"
        ];
        $sql = $this->gen_sql_new("select count( s.userid) as total, o.grade from t_order_info o  left join t_student_info s on s.userid = o.userid  where %s group by o.grade", $where_arr);
        return $this->main_get_list($sql);
    }
    public function get_total_order_subject_num($start_time,$end_time){
        $where_arr = [
            ["order_time>%u",$start_time,-1],
            ["order_time<%u",$end_time,-1],
            "is_test_user=0",
            "contract_type =0 ",
            "contract_status>0 ",
            " price>0"
        ];
        $sql = $this->gen_sql_new("select count(s.userid) as total, o.subject from t_order_info o  left join t_student_info s on s.userid = o.userid  where %s group by o.subject", $where_arr);
        return $this->main_get_list($sql);
    }
    //
    public function get_renew_province($start_time,$end_time){
        $where_arr = [
            ["order_time>%u",$start_time,-1],
            ["order_time<%u",$end_time,-1],
            "is_test_user=0",
            "contract_type =3 ",
            "contract_status>0 ",
            " price>0"
        ];
        $sql = $this->gen_sql_new("select count( s.userid) as total, s.phone_location from t_order_info o  left join t_student_info s on s.userid = o.userid  where %s group by phone_location", $where_arr);
        return $this->main_get_list($sql);
    }
    public function get_total_renew_grade_num($start_time,$end_time){
        $where_arr = [
            ["order_time>%u",$start_time,-1],
            ["order_time<%u",$end_time,-1],
            "is_test_user=0",
            "contract_type =3 ",
            "contract_status>0 ",
            " price>0"
        ];
        $sql = $this->gen_sql_new("select count( s.userid) as total, o.grade from t_order_info o  left join t_student_info s on s.userid = o.userid  where %s group by o.grade", $where_arr);
        return $this->main_get_list($sql);
    }
    public function get_total_renew_subject_num($start_time,$end_time){
        $where_arr = [
            ["order_time>%u",$start_time,-1],
            ["order_time<%u",$end_time,-1],
            "is_test_user=0",
            "contract_type =3 ",
            "contract_status>0 ",
            " price>0"
        ];
        $sql = $this->gen_sql_new("select count(s.userid) as total, o.subject from t_order_info o  left join t_student_info s on s.userid = o.userid  where %s group by o.subject", $where_arr);
        return $this->main_get_list($sql);
    }



    //-------------------------------
    public function get_total($start_time,$end_time){
        $where_arr = [
            ["reg_time>%u",$start_time,-1],
            ["reg_time<%u",$end_time,-1],
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select count(s.userid) as total,s.phone_location ,t.subject,t.grade from t_student_info s  left join t_seller_student_new k on s.userid = k.userid left join t_test_lesson_subject t on t.userid = s.userid
where %s group by s.phone_location,t.subject,t.grade",$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_total_order($start_time,$end_time){
        $where_arr = [
            ["order_time>%u",$start_time,-1],
            ["order_time<%u",$end_time,-1],
            "is_test_user=0",
            "contract_type =0 ",
            "contract_status>0 ",
            " price>0"
        ];
        $sql = $this->gen_sql_new("select count( s.userid) as total, s.phone_location,o.subject,o.grade from t_order_info o  left join t_student_info s on s.userid = o.userid  where %s group by s.phone_location,o.subject,o.grade", $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_total_total_renew($start_time,$end_time){
        $where_arr = [
            ["order_time>%u",$start_time,-1],
            ["order_time<%u",$end_time,-1],
            "is_test_user=0",
            "contract_type =3 ",
            "contract_status>0 ",
            " price>0"
        ];
        $sql = $this->gen_sql_new("select count( s.userid) as total, s.phone_location,o.subject,o.grade from t_order_info o  left join t_student_info s on s.userid = o.userid  where %s group by s.phone_location,o.subject,o.grade", $where_arr);
        return $this->main_get_list($sql);
    }
    //----------------------------------------------
    public function get_total_province_teacher($start_time,$end_time){
        $where_arr = [
            [" train_through_new_time>%u",$start_time,-1],
            [" train_through_new_time<%u",$end_time,-1],
            "is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select count(teacherid) as total,phone_location from t_teacher_info where %s group by phone_location", $where_arr);
        return $this->main_get_list($sql);
    }
    public function get_total_province_lesson_teacher($start_time,$end_time){
        $where_arr = [
            ["  lesson_start>%u",$start_time,-1],
            ["  lesson_start<%u",$end_time,-1],
            "t.is_test_user=0",
            "lesson_del_flag=0  ",
            "lesson_type<1000 "
        ];
        $sql = $this->gen_sql_new("select count(distinct(t.teacherid))  as total,phone_location from t_lesson_info l left join t_teacher_info t on t.teacherid = l.teacherid  where %s group by phone_location", $where_arr);
        return $this->main_get_list($sql);

    }

    public function get_total_province_lesson_student($start_time,$end_time){
        $where_arr = [
            ["  lesson_start>%u",$start_time,-1],
            ["  lesson_start<%u",$end_time,-1],
            "s.is_test_user=0",
            "lesson_del_flag=0  ",
            "lesson_type<1000 "
        ];
        $sql = $this->gen_sql_new("select count(distinct(s.userid))  as total,phone_location from t_lesson_info l left join t_student_info s on s.userid = l.userid  where %s group by phone_location", $where_arr);
        return $this->main_get_list($sql);

    }
    public function get_test_lesson($start_time,$end_time){
        $where_arr = [
            ["  lesson_start>%u",$start_time,-1],
            ["  lesson_start<%u",$end_time,-1],
            "  (s.is_test_user = 0 OR s.is_test_user IS NULL)"
        ];
        $sql = $this->gen_sql_new("select l.grade, l.subject, count(*) as total "
                                ."from t_lesson_info l left join t_student_info s on s.userid = l.userid "
                                ." where %s group by l.grade, l.subject",$where_arr);
        return $this->main_get_list($sql);
    }
    public function get_test_lesson_subject($start_time,$end_time){
        $where_arr = [
            ["  lesson_start>%u",$start_time,-1],
            ["  lesson_start<%u",$end_time,-1],
            "  (s.is_test_user = 0 OR s.is_test_user IS NULL)"
        ];
        $sql = $this->gen_sql_new("select s.phone_location, l.subject, count(*) as total "
                                ."from t_lesson_info l left join t_student_info s on s.userid = l.userid "
                                ." where %s group by s.phone_location, l.subject",$where_arr);
        return $this->main_get_list($sql);
    }

    public function get_apply_info(){
        $sql = "select  a.userid, s.nick, a.grade, a. subject ,a.stu_request_test_lesson_demand,a.textbook  ,s.phone_location, t.current_lessonid ,t.require_id ,k.lessonid ,k.success_flag, l.lesson_type,l.lesson_user_online_status ,
o.price,o.contract_status
from t_test_lesson_subject   a  left join t_student_info s on s.userid = a.userid 
left join t_test_lesson_subject_require t on t.require_id = a.current_require_id 
left join t_test_lesson_subject_sub_list k on k.require_id  = t.require_id
left join t_lesson_info l on l.lessonid = k.lessonid
left join t_order_info o on o.from_test_lesson_id  = l.lessonid
where a.stu_request_test_lesson_time > 1483200000 and a.grade in (101,102,103) and s.is_test_user = 0";
        return $this->main_get_list($sql);
    }
    public function get_apply_info_a1(){
        $sql = "select  a.userid, s.nick, a.grade, a. subject ,a.stu_request_test_lesson_demand,a.textbook  ,s.phone_location, t.current_lessonid ,t.require_id ,k.lessonid ,k.success_flag, l.lesson_type,l.lesson_user_online_status ,o.price,o.contract_status from t_test_lesson_subject   a  left join t_student_info s on s.userid = a.userid left join t_test_lesson_subject_require t on t.require_id = a.current_require_id left join t_test_lesson_subject_sub_list k on k.require_id  = t.require_id left join t_lesson_info l on l.lessonid = k.lessonid left join t_order_info o on o.from_test_lesson_id  = l.lessonid where a.stu_request_test_lesson_time > 1483200000 and a.grade in (101,102,103,104) and s.is_test_user = 0";
        return $this->main_get_list($sql);
    }
    public function get_apply_info_month($start_time,$end_time,$subject,$grade){
        $sql = "select   a.stu_request_test_lesson_time,a.grade, a.userid,a.subject ,a.stu_request_test_lesson_demand,a.textbook  ,s.phone_location from t_test_lesson_subject   a  left join t_student_info s on s.userid = a.userid left join t_test_lesson_subject_require t on t.require_id = a.current_require_id left join t_test_lesson_subject_sub_list k on k.require_id  = t.require_id left join t_lesson_info l on l.lessonid = k.lessonid left join t_order_info o on o.from_test_lesson_id  = l.lessonid where a.stu_request_test_lesson_time >$start_time and a.stu_request_test_lesson_time < $end_time  and $grade and s.is_test_user = 0 and a.subject=$subject ";
        return $this->main_get_list($sql);
    }
    public function get_apply_info_new($page_info,$start_time,$end_time){
        $where_arr = [
          ["a.stu_request_test_lesson_time>%u",$start_time,-1],
          ["a.stu_request_test_lesson_time<%u",$end_time,-1],
          "a.grade in (101,102,103) ",
          "s.is_test_user = 0 ",
        ];
        $sql = $this->gen_sql_new("select  a.userid, s.nick, a.grade, a. subject ,a.stu_request_test_lesson_demand,a.textbook  ,s.phone_location, t.current_lessonid ,t.require_id ,k.lessonid ,k.success_flag, l.lesson_type,l.lesson_user_online_status ,o.price,o.contract_status"
                                  ." from %s  a  "
                                  ." left join %s s on s.userid = a.userid "
                                  ." left join %s t on t.require_id = a.current_require_id "
                                  ." left join %s k on k.require_id  = t.require_id "
                                  ." left join %s l on l.lessonid = k.lessonid "
                                  ." left join %s o on o.from_test_lesson_id  = l.lessonid "
                                  ." where  %s",
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject_require::DB_TABLE_NAME,
                                  t_test_lesson_subject_sub_list::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }
    public function get_total_apply_info($start_time,$end_time){
        $where_arr = [
          ["a.stu_request_test_lesson_time>%u",$start_time,-1],
          ["a.stu_request_test_lesson_time<%u",$end_time,-1],
          "a.grade in (101,102,103) ",
          "s.is_test_user = 0 ",
        ];
        $sql = $this->gen_sql_new("select a.grade, count(a.userid) as total_num, sum(if(k.lessonid>0, 1,0)) as total_test , sum(if(l.lesson_user_online_status=1, 1,0)) as total_success,  sum(if (   o.contract_status > 0,1,0)) as total_order"
           ." from %s  a  "
        ." left join %s s on s.userid = a.userid "
        ." left join %s t on t.require_id = a.current_require_id "
        ." left join %s k on k.require_id  = t.require_id "
        ." left join %s l on l.lessonid = k.lessonid "
        ." left join %s o on o.from_test_lesson_id  = l.lessonid "
        ." where  %s group by a.grade",
        t_test_lesson_subject::DB_TABLE_NAME,
        t_student_info::DB_TABLE_NAME,
        t_test_lesson_subject_require::DB_TABLE_NAME,
        t_test_lesson_subject_sub_list::DB_TABLE_NAME,
        t_lesson_info::DB_TABLE_NAME,
        t_order_info::DB_TABLE_NAME,
        $where_arr);
        return $this->main_get_list($sql);
    }
    public function get_all_teacher_info_total(){
        $sql = "select teacherid ,subject ,phone_location from t_teacher_info  where train_through_new_time>0 and train_through_new_time<1509163200 and is_test_user=0 ";
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }
    public function get_all_teacher_info_success(){
        $sql = "select distinct(t.teacherid) , t.subject ,t.phone_location from t_teacher_info t left join t_lesson_info l on t.teacherid = l.teacherid   where train_through_new_time>0 and train_through_new_time<1509163200 and is_test_user=0 and lesson_start > 1501516800 and lesson_start < 1509465600 and (l.lesson_type = 0 or l.lesson_type=2)";
        return $this->main_get_list($sql,function($item){
            return $item["teacherid"];
        });
    }
}