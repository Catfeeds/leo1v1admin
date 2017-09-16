<?php
namespace App\Models;
use \App\Enums as E;
class t_location_subject_grade_textbook_info extends \App\Models\Zgen\z_t_location_subject_grade_textbook_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_all_info($page_info,$grade,$subject,$address){
        $where_arr = array(
            array( "subject=%u", $subject, -1 ),
            array( "grade=%u", $grade, -1 ),
        );

        if ($address) {
            $address=$this->ensql($address);
            $where_arr[]="(province like '%%".$address."%%' or city like '%%".$address."%%' or educational_system like '%%".$address."%%')";
        }

        $sql = $this->gen_sql_new("select * from %s where %s",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_all_list($province){
        $sql = $this->gen_sql_new("select * from %s where province ='%s'",self::DB_TABLE_NAME,$province);
        return $this->main_get_list($sql);

    }

    public function check_is_exist($province,$city,$grade,$subject){
        $sql = $this->gen_sql_new("select id from %s "
                                  ." where province ='%s' and city='%s' and subject = %u and grade=%u",
                                  self::DB_TABLE_NAME,
                                  $province,
                                  $city,
                                  $subject,
                                  $grade
        );
        return $this->main_get_value($sql);

    }

    public function get_all_location_education_info(){
        $sql = $this->gen_sql_new("select distinct city,educational_system "
                                  ." from %s"
                                  ." where educational_system <> ''",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_no_educational_system_info(){
        $sql = $this->gen_sql_new("select id, city,educational_system "
                                  ." from %s"
                                  ." where educational_system = ''",
                                  self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }

}











