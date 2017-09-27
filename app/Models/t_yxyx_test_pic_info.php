<?php
namespace App\Models;
use \App\Enums as E;
class t_yxyx_test_pic_info extends \App\Models\Zgen\z_t_yxyx_test_pic_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function add_test($test_title, $test_des, $grade, $subject, $test_type, $pic, $poster, $create_time,$adminid, $custom_type) {
        $res = $this->row_insert([
            "test_title"  => $test_title,
            "test_des"    => $test_des,
            "grade"       => $grade,
            "subject"     => $subject,
            "test_type"   => $test_type,
            "pic"         => $pic,
            "poster"      => $poster,
            "create_time" => $create_time,
            "adminid"     => $adminid,
            "custom_type" => $custom_type,
            "visit_num"   => 0,
            "share_num"   => 0,
        ]);
        return $res;
    }

    public function update_test($id,$test_title, $test_des, $grade, $subject, $test_type, $pic, $poster, $custom_type) {
        $res = $this->field_update_list( ["id" => $id],[
            "test_title"  => $test_title,
            "test_des"    => $test_des,
            "grade"       => $grade,
            "subject"     => $subject,
            "pic"         => $pic,
            "poster"      => $poster,
            "custom_type" => $custom_type,
        ]);
        return $res;
    }

    public function get_one_info($id) {
        $where_arr = [
            'id='.$id,
        ];
        $sql = $this->gen_sql_new( "select id, test_title, test_des, grade, subject, visit_num, share_num,"
                                   ." custom_type, test_type, pic, poster, create_time"
                                   ." from %s "
                                   ." where %s"
                                   ,self::DB_TABLE_NAME
                                   ,$where_arr
        );
        return $this->main_get_row($sql);
    }
    public function add_field_num($id, $field) {
        $where_arr = [
            'id='.$id,
        ];
        $sql = $this->gen_sql_new( "update %s set {$field}={$field}+1"
                                   . " where %s"
                                   ,self::DB_TABLE_NAME
                                   ,$where_arr
        );
        $this->main_update($sql);
    }


    public function get_all($grade, $subject, $test_type, $page_info, $order_by_str){
        $where_arr = [
            ['y.grade=%u', $grade , -1],
            ['y.subject=%u', $subject , -1],
            ['y.test_type=%u', $test_type , -1],
        ];
        $sql = $this->gen_sql_new(
            "select y.id, y.test_title, y.test_des, y.grade, y.subject, y.visit_num, y.share_num ,"
            ." y.custom_type, y.test_type, y.poster, y.create_time, a.account"
            ." from %s y "
            ." left join %s a on a.uid=y.adminid"
            ." where %s $order_by_str"
            ,self::DB_TABLE_NAME
            ,t_manager_info::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

    public function get_other_info($id_str, $create_time) {
        $where_arr = [
            ['id in (%s)', $id_str, 1],
            "create_time<$create_time",
        ];
        $sql = $this->gen_sql_new("select id, test_title, poster"
                                  ." from %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_id_poster( $id=0, $start_time,$end_time){
        $where_arr = [
            ['id!=%s', $id, 0],
            ['create_time>%s', $start_time, 0],
            ['create_time<%s', $end_time, 0],
        ];
        $sql = $this->gen_sql_new("select id, poster"
                                  ." from %s"
                                  ." where %s"
                                  ." order by create_time desc"
                                  ." limit 100"
                                  , self::DB_TABLE_NAME
                                  , $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_for_wx($grade, $subject, $test_type, $page_info, $wx_openid){
        $where_arr = [
            ['y.grade=%u', $grade , -1],
            ['y.subject=%u', $subject , -1],
            ['y.test_type=%u', $test_type , -1],
        ];
        $sql = $this->gen_sql_new( "select y.id, y.test_title, y.create_time, tv.flag"
                                    ." from %s y "
                                    ." left join %s tv on y.id=tv.test_pic_info_id"
                                    ." and tv.wx_openid='$wx_openid'"
                                    ." where %s"
                                    ." group by y.id"
                                    ,self::DB_TABLE_NAME
                                    ,t_yxyx_test_pic_visit_info::DB_TABLE_NAME
                                    ,$where_arr
        );
        // dd($sql);
        return $this->main_get_list_by_page($sql,$page_info);
    }


}
