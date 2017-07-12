<?php
namespace App\Models;
class t_book_info extends \App\Models\Zgen\z_t_book_info
{
	public function __construct()
	{
		parent::__construct();
	}
    
    public function get_booked_user( $register_flag,$class_time,$type,$start_time,$end_time,
                                     $grade,$status,$page_num, $book_user,$book_origin,$trial_type,
                                     $sys_operator_type,$account)
    {
        
        if (!empty($book_user)) {
            $user_str = " phone like '%". $this->ensql($book_user)."%' ";
            $sql = sprintf("select * from %s where %s order by book_time desc",
                           self::DB_TABLE_NAME ,
                           $user_str
            );
        }else if(!empty($book_origin)){
            $user_str = "origin like '%".$this->ensql($book_origin)."%'";
            $sql = sprintf("select * from %s where %s order by book_time desc",
                           self::DB_TABLE_NAME ,
                           $user_str
            );
        }else {
            if($type==1){
                $opt_time_filed="book_time";
            }else{
                $opt_time_filed="book_time_next";
            }

            $where_arr=[
                [ "grade=%d", $grade ,-1 ], 
                [ "status=%d", $status ,-1 ] ,
                [ "trial_type=%d ", $trial_type,-1 ] ,
                [ "$opt_time_filed>%u", $start_time ,-1 ] ,
                [ "$opt_time_filed<%u", $end_time ,-1 ] ,
            ];

            if ($sys_operator_type==1 || $sys_operator_type==2  ) {
                $sys_operator="";
                if ($sys_operator_type==1) {
                    $sys_operator=$account;
                }

                $where_arr[]=["sys_operator ='%s'  ", $sys_operator, -1 ];
            }
            //lala
            switch ( $class_time ) {
            case 1 : 
                $where_arr[]=["class_time>%u  ", 0, -1 ];
                break;
            case 2 : 
                $where_arr[]=["class_time= %u  ", 0, -1 ];
                break;
            default:
                break;
            }
            
            $where_arr[]=[" register_flag=%u ", $register_flag, -1 ];
            $where_str=$this->where_str_gen( $where_arr);
             
            $sql = $this->gen_sql("select * from %s where  %s order by %s desc",
                                  self::DB_TABLE_NAME,
                                  [$where_str],  $opt_time_filed);
        }

        $ret_info= $this->main_get_list_by_page($sql,$page_num,10);

        return  $this->reset_phone_location($ret_info );
    }

    public function get_tea_schedule( $start_time, $end_time,$phone,$page_num )
    {
        if($phone!=''){
            $where_str = "phone=".$phone;
        }else{
            $where_str = "true";
        }
        
        $sql = $this->gen_sql("select * from %s
                              where register_flag=0 and class_time>=%u and class_time<=%u and %s
                              order by class_time asc",
                              self::DB_TABLE_NAME,
                              $start_time,
                              $end_time,
                              $where_str
        );
        $ret_info= $this->main_get_list_by_page($sql,$page_num,10);

        return  $this->reset_phone_location($ret_info );
    }

    public function reset_phone_location($ret_info) {
        
        foreach  (  $ret_info["list"] as &$item) {
            if (!$item["phone_location"] ) {
                
                //设置到数据库
                $item["phone_location"] = \App\Helper\Common::get_phone_location($item["phone"]);
                if ($item["phone_location"]) {
                    $this->field_update_list($item["id"] ,[
                        "phone_location"  =>   $item["phone_location"]
                    ]);
                }
            }
        }
            
        return $ret_info  ;
    }

    public function reset_userid( $phone,$userid) {
        $sql=$this->gen_sql("update %s set register_flag=0, userid=%u where phone='%s' and userid <>0",
                            self::DB_TABLE_NAME, $userid, $phone
        );
        return $this->main_update($sql);
        
    }
    public function get_sys_operator_by_phone( $phone )
    {
        $sql=$this->gen_sql("select sys_operator from   %s "
                            ." where phone='%s'  and sys_operator <>''  limit 1",
                            self::DB_TABLE_NAME,
                            $phone);
        return $this->main_get_value($sql);

    }
    public function get_userid_by_phone( $phone )
    {
        $sql=$this->gen_sql("select userid from   %s "
                            ." where phone='%s'  and userid <>0  limit 1",
                            self::DB_TABLE_NAME,
                            $phone);
        return $this->main_get_value($sql);
    }


    public function set_sys_operator( $phone, $sys_operator  ) {
        $sql=$this->gen_sql("update %s set  sys_operator='%s' ,sys_opt_time='%u'"
                            ." where phone='%s'  and sys_operator ='' ",
                            self::DB_TABLE_NAME,
                            $sys_operator,time(NULL),
                            $phone);
        return $this->main_update($sql);
    }

    public function free_on_no_deal() {
        $sql= $this->gen_sql("update %s set sys_operator='',sys_opt_time=0 ".
                             "where sys_opt_time>%u and sys_opt_time <%u and status=0 and assigner=''",
                             self::DB_TABLE_NAME,
                             time(NULL)-5*86400 , time(NULL) -1*3600);
        return $this->main_update($sql);
    }

    public function update_user_info($phone,$status,$note)
    {
        $sql = sprintf("update %s set status=%u,consult_desc='%s' where phone=%u",
                       SELF::DB_TABLE_NAME,
                       $status,
                       $this->ensql($note),
                       $phone
        );
        return $this->main_update($sql);
    }
    //lala 
    public function set_class_time($phone, $class_time)
    {
        $sql = sprintf("update %s set class_time= %u where phone = %u",
                       self::DB_TABLE_NAME,
                       $class_time,
                       $phone
        );
        return $this->main_update($sql);
    }


    //lala设置老师
    public function set_course_teacher($teacherid, $courseid)
    {
        $this->start_transaction();
        // update course
        $ret_course = $this->set_course_teacherid($teacherid,$courseid);
        if ($ret_course === false) {
            $this->rollback();
            return false;
        }
        $this->commit();
        return true;
    }
    private function set_course_teacherid($teacherid, $courseid)
    {
        $sql = sprintf("update %s set teacherid = %u where courseid = %u",
                       SELF::DB_TABLE_NAME,
                       $teacherid,
                       $courseid
        );
        return $this->main_update($sql);
    }
}











