<?php
namespace App\Models;

/**
 * @property t_manager_info  $t_manager_info
*/

class t_assistant_info extends \App\Models\Zgen\z_t_assistant_info
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_ass_info_list_for_hr($is_part_time, $ass_nick, $phone, $score, $page_num)
    {
        $cond_str = $this->gen_cond_for_info_list_for_hr($is_part_time, $ass_nick, $phone, $score);
        $sql = sprintf("select nick, assistant_type, gender, birth, phone, email, rate_score, assistantid,  school, prize ".
                       "from %s where is_quit = 0 %s ",
                       self::DB_TABLE_NAME,
                       $cond_str
        );
        //log::write("get_ass_info_list_for_hr: ".$sql);
        return $this->main_get_list_by_page($sql, $page_num,10);
    }

    public function get_ass_info_new($is_part_time,$assistantid,  $rate_score, $page_num)
    {
        $where_arr = array(
            array( "assistantid = %u", $assistantid, -1 ),
        );
        $this->where_arr_add_int_or_idlist($where_arr,"assistant_type",$is_part_time);

        if($rate_score == 1){
            $where_arr[] = "(rate_score >= 10 and rate_score < 20)";
        }elseif($rate_score == 2){
            $where_arr[] = "(rate_score >= 20 and rate_score < 30)";
        }elseif($rate_score == 3){
            $where_arr[] = "(rate_score >= 30 and rate_score < 40)";
        }elseif($rate_score == 4){
            $where_arr[] = "(rate_score >= 40 and rate_score < 50)";
        }elseif($rate_score == 5){
            $where_arr[] = "rate_score > 50 ";
        }

        $sql=$this->gen_sql_new ("select nick,assistant_type,gender,birth,phone,assign_lesson_count ,"
                                 ." email,rate_score,assistantid,school,prize,work_year"
                                 ." from %s "
                                 ." where is_quit=0 "
                                 ." and %s "
                                 ,self::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list_by_page( $sql,$page_num,10);
    }

    private function gen_cond_for_info_list_for_hr($is_part_time, $ass_nick, $phone, $score)
    {
        $where = "";
        $and = "";
        $str = "";

        if($is_part_time != -1){
            if($and == ""){
                $and = " and ";
            }
            $str .= $and . " assistant_type = " . $is_part_time;
        }

        if($ass_nick != ""){
            if($and == ""){
                $and = " and ";
            }
            $str .= $and . " nick like '%" . $ass_nick . "%' ";
        }

        if($phone != ""){
            if($and == ""){
                $and = " and ";
            }
            $str .= $and . " phone like '%" . $phone . "%' ";
        }

        if($score != -1){
            if($and == ""){
                $and = " and ";
            }
            if($score == 0){
                $str .= $and . " rate_score < 10 ";
            }elseif($score == 1){
                $str .= $and . " rate_score >=10 and rate_score <20 ";
            }elseif($score == 2){
                $str .= $and . " rate_score >=20 and rate_score <30 ";
            }elseif($score == 3){
                $str .= $and . " rate_score >=30 and rate_score <40 ";
            }elseif($score == 4){
                $str .= $and . " rate_score >=40 ";
            }
        }

        if($str != ""){
            $str .= $where . $str;
        }
        return $str . " order by assistantid desc ";
    }

    public function get_ass_page_info($assistantid)
    {
        $sql = sprintf("select nick, gender, birth, work_year, phone, email, base_intro, face, prize, ".
                       "school, assistant_type, rate_score, ass_style, achievement from %s where assistantid = %u ",
                       self::DB_TABLE_NAME,
                       $assistantid
        );
        //log::write("################" . $sql);
        return $this->main_get_row( $sql  );
    }
    public function modify_ass_detail_info($assistantid, $ass_nick, $gender, $work_year, $school,
                                           $email, $ass_style, $achievement, $birth, $base_intro, $assistant_type, $prize)
    {
        $sql = sprintf("update %s set nick = '%s', gender = %u, work_year = %u, school = '%s', base_intro = '%s', " .
                       "email = '%s', ass_style = '%s', achievement = '%s' , birth = %u , assistant_type = %u, prize = '%s' where assistantid = %u ",
                       self::DB_TABLE_NAME,
                       $ass_nick,
                       $gender,
                       $work_year,
                       $school,
                       $base_intro,
                       $email,
                       $ass_style,
                       $achievement,
                       $birth,
                       $assistant_type,
                       $prize,
                       $assistantid);
        //log::write("modify_ass_detail_info: ".$sql);
        return $this->main_update( $sql );
    }


    public function change_ass_face($face, $assistantid)
    {
        $sql = sprintf("update %s set face = '%s' where assistantid = %u",
                       self::DB_TABLE_NAME,
                       $face,
                       $assistantid
        );
        return $this->main_update( $sql  );
    }

    public function add_new_ass($ass_nick, $gender, $birth, $work_year, $phone, $email,
                                $assistant_type, $assistantid, $school)
    {
        $ret_count=$this->row_insert([
            'nick'           => $ass_nick,
            'gender'         => $gender,
            'birth'          => $birth,
            'work_year'      => $work_year,
            'phone'          => $phone,
            'email'          => $email,
            'assistant_type' => $assistant_type,
            'assistantid'    => $assistantid,
            'school'         => $school,
        ]);
        if($ret_count==1) {
            return $this->get_last_insertid();
        }else{
            return false;
        }
    }


    public function get_email_zj($acc)
    {
        $sql = sprintf("select email from %s where nick = '%s'",
                       self::DB_TABLE_NAME,
                       $this->ensql($acc)
        );
        return $this->main_get_row( $sql  );
    }

    public function get_assistantid($name)
    {
        $uid = $this->task->t_manager_info->get_id_by_account($name);
        $sql = $this->gen_sql_new("select assistantid from %s a"
                                  ." join %s m on a.phone = m.phone"
                                  ." where m.uid = %u",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $uid
        );
        return $this->main_get_value($sql);
    }

    public function get_ass_list_for_select($id,$gender,$nick_phone,$page_num)
    {
        $where_arr = array(
            array( "gender=%d", $gender, -1 ),
            array( "assistantid=%d", $id, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=array( "(nick like '%%%s%%' or  phone like '%%%s%%' )",
                                array(
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone)));
        }

        $sql = sprintf("select assistantid as id , nick, phone,gender  from %s  where %s",
                       self::DB_TABLE_NAME,  $this->where_str_gen( $where_arr));
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function get_account_by_id($assistantid) {
        //从email 中得到
        $adminid= $this->get_adminid_by_assistand($assistantid);
        return $this->t_manager_info->get_account($adminid);
    }

    /*
      public function get_teacher_clothes_list($type){
        $where_str=$this->where_str_gen([
            [ "type=%d", $type, -1 ],
        ]);
        $sql =$this->gen_sql("select id as k,name as v from %s where %s"
                             ,self::DB_TABLE_NAME
                             ,[$where_str]
        );

        return $this->main_get_list($sql);
    }
    */

    public function get_assistant_list($assistantid){
        $sql = $this->gen_sql("select * from %s where assistantid = %u ",
                             self::DB_TABLE_NAME,
                             $assistantid
        );
        return $this->main_get_row($sql);
    }
    public function update_assistant_info2($assistantid,$nick,$e_name,$birth,$gender,$work_year,$school,$phone,$email,$assistant_type){
         $sql = $this->gen_sql("update %s set nick = '%s', birth = %u, e_name = '%s', gender = %u, work_year = %u, school = '%s', phone = '%s', email = '%s', assistant_type = %u where assistantid = %u ",
                               self::DB_TABLE_NAME,
                               $nick,
                               $birth,
                               $e_name,
                               $gender,
                               $work_year,
                               $school,
                               $phone,
                               $email,
                               $assistant_type,
                               $assistantid
        );
        return $this->main_update($sql);
    }
    public function update_assistant_info3($assistantid,$nick,$birth,$gender,$work_year,$school,$phone,$email,$assistant_type){
         $sql = $this->gen_sql("update %s set nick = '%s', birth = %u,  gender = %u, work_year = %u, school = '%s', phone = '%s', email = '%s', assistant_type = %u where assistantid = %u ",
                               self::DB_TABLE_NAME,
                               $nick,
                               $birth,
                               $gender,
                               $work_year,
                               $school,
                               $phone,
                               $email,
                               $assistant_type,
                               $assistantid
        );
        return $this->main_update($sql);
    }

     public function get_type_count_by_ass($page_num)
     {
         $sql = $this->gen_sql("select * from %s",
                        self::DB_TABLE_NAME
         );
         return $this->main_get_list_by_page($sql,$page_num);
     }

    public function get_all_assistant_renew($start_time,$end_time )
    {
        $where_arr=[
            [  "o.order_time >= %u", $start_time, -1 ] ,
            [  "o.order_time <= %u", $end_time, -1 ] ,
            "o.contract_status in (1,2,3)" ,
            "(m.uid <> 68 and m.uid <> 74)",
            "m.account_role=1",
        ];
        $sql =$this->gen_sql_new("select  o.sys_operator,count(distinct userid) all_student,sum(o.price) all_price,sum(o.lesson_total*o.default_lesson_count) all_total,sum(if(contract_type=1,lesson_total*default_lesson_count,0)) give_total from %s a, %s m,%s o where a.phone = m.phone and m.account = o.sys_operator and %s group by sys_operator order by all_price desc",
                                 self::DB_TABLE_NAME,
                                 t_manager_info::DB_TABLE_NAME,
                                 t_order_info::DB_TABLE_NAME,
                                 $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_adminid_by_assistand($assistantid){
        $sql = $this->gen_sql_new("select uid from %s a"
                                  ." join %s m on a.phone = m.phone"
                                  ." where a.assistantid = %u",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $assistantid
        );
        return $this->main_get_value($sql);
    }

    public function get_ass_phone_by_lessonid($lessonid){
        $sql = $this->gen_sql_new(" select phone from %s ai ".
                                  " left join %s l on l.assistantid = ai.assistantid ".
                                  " where lessonid = %d",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $lessonid
        );

        return $this->main_get_value($sql);
    }

    public function  set_assign_lesson_count($assistantid,$assign_lesson_count,$assign_lesson){
      $sql = sprintf("update %s set assign_lesson_count  = %s+%s where assistantid = %u",
                       self::DB_TABLE_NAME,
                       $assign_lesson_count,
                       $assign_lesson,
                       $assistantid
        );
        return $this->main_update( $sql  );
    }

    public function get_assistant_detail_info($assistantid){
       $where_arr = [
            ['assistantid=%s',$assistantid,-1]
        ];
        $sql = $this->gen_sql_new("select assistantid, gender,nick,birth from %s where %s ",
          self::DB_TABLE_NAME,
          $where_arr);
        return $this->main_get_row($sql);
    }

    public function get_assistant_detail_info_b2($assistantid){
       $where_arr = [
            ['uid=%s',$assistantid,-1]
        ];
        $sql = $this->gen_sql_new("select a.assistantid, a.gender,m.account ,a.birth from %s a"
          ." left join %s m on m.phone = a.phone where %s ",
          self::DB_TABLE_NAME,
          t_manager_info::DB_TABLE_NAME,
          $where_arr);
        return $this->main_get_row($sql);
    }
}
