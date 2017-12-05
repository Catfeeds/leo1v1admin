<?php
namespace App\Models;
class t_book_revisit extends \App\Models\Zgen\z_t_book_revisit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_succ_recvisit_time($phone,$time) {
        $sql=$this->gen_sql("select revisit_time from %s where phone='%s' and   revisit_time=%u ",
                            self::DB_TABLE_NAME,$phone,$time);
        return $this->main_get_value($sql,0)==0;
    }

    public function add_book_revisit($phone, $op_note, $sys_operator)
    {
        if ($phone <10000){
            return;
        }
        $now=time(NULL)-1;
        do{
            $now+=1;
            $ret=$this->check_succ_recvisit_time($phone,$now);
        }while(!$ret);
        /*
          `phone` varchar(16) NOT NULL COMMENT '联系方式',
          `revisit_time` int(10) unsigned NOT NULL COMMENT '回访时间',
          `operator_note` varchar(1024) NOT NULL COMMENT '回访记录',
          `sys_operator` varchar(32) NOT NULL COMMENT '进行回访的人',
         */
        $sql = $this->gen_sql_new(
            " insert ignore into %s (phone, revisit_time, operator_note, ".
            " sys_operator) values('%s', %u, '%s', '%s')",
            SELF::DB_TABLE_NAME,
            $phone,
            $now,
            $op_note,
            $sys_operator
        );
        return $this->main_insert($sql);
    }

    

    public function get_book_revisit_list($phone ,$revisit_show_all_flag)
    {
        $where_arr=[];
        if ( !$revisit_show_all_flag ) {
            $where_arr[]="revisit_time +3600 > admin_assign_time  ";
        }
        $sql =$this->gen_sql_new(
            "select revisit_time, operator_note, sys_operator , 0 as revisit_type  "
            . " from %s r "
            ." join %s n on n.phone=r.phone    "
            ." where n.phone = '%s' and  %s  " ,
            SELF::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $phone,$where_arr);
        return $this->main_get_list($sql);
    }

    //lala   now useless
    public function update_book_revisit($phone, $op_note, $sys_operator)
    {
        /*
          `phone` varchar(16) NOT NULL COMMENT '联系方式',
          `revisit_time` int(10) unsigned NOT NULL COMMENT '回访时间',
          `operator_note` varchar(1024) NOT NULL COMMENT '回访记录',
          `sys_operator` varchar(32) NOT NULL COMMENT '进行回访的人',
         */

        $sql = sprintf(" update %s set operator_note='%s',revisit_time = '%u', sys_operator='%s' ".
                       " where phone = '%u'",
                       self::DB_TABLE_NAME,
                       $op_note,
                       time(),
                       $sys_operator,
                       $phone);
        return $this->main_insert($sql);
    }
    public function get_list_by_account( $page_num,$account,$start_time,$end_time) {

        $where_arr=[
            ["sys_operator='%s'",$account, "" ] ,
            ["revisit_time>=%u",$start_time, -1 ] ,
            ["revisit_time<%u",$end_time, -1 ] ,
        ];

        $sql=$this->gen_sql_new("select * from %s where %s ", self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);
        //$this->gen

    }
    public function get_book_revisit( $page_num,$userid) {

        $where_arr=[
            ["n.userid=%u",$userid, -1] ,
        ];

        $sql=$this->gen_sql_new("select b.revisit_time,b.operator_note,b.operator_audio,b.sys_operator "
                                ."from %s b,%s n where b.phone = n.phone and %s order by b.revisit_time desc",
                                self::DB_TABLE_NAME,
                                t_seller_student_new::DB_TABLE_NAME,
                                $where_arr);
        return $this->main_get_list_by_page($sql,$page_num);

    }

    public function get_rev_info_by_phone_adminid($phone){
        $sql = $this->gen_sql_new("select revisit_time,operator_note,sys_operator from %s where phone = '%s' and operator_note not like '%%操作者:%%' and operator_note not like '%%COMMING:资源%%' order by revisit_time desc ",self::DB_TABLE_NAME,$phone);
        return $this->main_get_list($sql);
    }
    public function get_rev_info_by_phone_adminid_new($phone,$page_num){
        $sql = $this->gen_sql_new("select revisit_time,operator_note,sys_operator from %s where phone = '%s' order by revisit_time desc ",self::DB_TABLE_NAME,$phone);
        return $this->main_get_list_by_page($sql,$page_num);
    }


}
