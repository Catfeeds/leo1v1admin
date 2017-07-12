<?php
namespace App\Models;
use App\Models\Zgen as Z;
use \App\Models as M;
use \App\Enums as E;

class t_appointment_info extends \App\Models\Zgen\z_t_appointment_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function get_appoint_course_list( $package_type,$page_num)
    {
        $where_arr=[];
        switch($package_type){
                case"0":
                    $where_arr[]= "package_type >= 1000 and package_type < 2000";
                    break;
                case"1":
                $where_arr[]="package_type >= 0 and package_type <1000";
                    break;
                case"2":
                    $where_arr[]="package_type >= 3000";
                    break;
                case"3":
                    $where_arr[]="package_type >= 2000 and package_type <3000";
                    break;
            }

        $cond_str=$this->where_str_gen($where_arr);
        $sql = sprintf("select packageid, package_pic, package_name, grade, subject, lesson_total, effect_start, "
                       ." effect_end,tag_type, current_price, package_deadline, package_type, user_total, package_tags "
                       ." from %s "
                       ." where del_flag = 0 "
                       ." and %s"
                       ." order by packageid desc"
                       ,self::DB_TABLE_NAME
                       ,$cond_str
        );
        return $this->main_get_list_by_page($sql, $page_num, 100);
    }
    
    public function get_package_simple_info($packageid)
    {
        $sql = sprintf("select packageid, package_name, package_intro, suit_student, ".
                       "subject, lesson_total, grade, package_tags, package_target ".
                       "from %s where packageid = %u",
                       self::DB_TABLE_NAME,
                       $packageid
        );

        return $this->main_get_row($sql);
    }
    
    public function get_package_pic($packageid)
    {
        $sql = sprintf("select package_pic from %s where packageid = %u",
                       self::DB_TABLE_NAME,
                       $packageid
        );

        return $this->main_get_value($sql);
    }

    public function set_package_pic($packageid, $package_pic)
    {
        $sql = sprintf("update %s set package_pic = '%s' where packageid = %u ",
                       self::DB_TABLE_NAME,
                       $package_pic,
                       $packageid
        );

        return $this->main_update($sql);
    }


    public function get_appoint_course($page_num)
    {
        $sql = sprintf("select packageid, package_name, grade, subject, lesson_total, effect_start, ".
                       "effect_end,tag_type, current_price, package_deadline, package_type, user_total, package_tags from %s where del_flag = 0",
                       self::DB_TABLE_NAME
                       );

        return $this->main_get_list_by_page($sql, $page_num, 100);
    }


    public function get_open_package_list($packageid,$package_type,$search_str,$page_num){
        $where_arr = array(
            array( "package_type=%u", $package_type, -1 ),
            array( "packageid=%d", $packageid, -1 ),
        );

        $package_type_str= $this->where_get_in_str("package_type", [
                       E\Epackage_type::V_1,
                       E\Epackage_type::V_2,
                       E\Epackage_type::V_3,
                       E\Epackage_type::V_1001,
                       E\Epackage_type::V_2001,
                       E\Epackage_type::V_3001
        ]);

        if ($search_str!=""){
            $where_arr[]=sprintf( "(subject like '%%%s%%' or  package_name like '%%%s%%' )",
                                    $this->ensql($search_str),
                                    $this->ensql($search_str));
        }
          
        $sql =  sprintf("select packageid, package_name, package_type, subject, effect_start, package_end "
                        ." from %s where %s and %s",
                        self::DB_TABLE_NAME,
                        $package_type_str,
                        $this->where_str_gen($where_arr)
        ); 
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function add_appoint($package_name,$package_type,$package_intro,$suit_student,$subject,$grade){
        $this->row_insert([
            "package_name"  => $package_name,
            "package_type"  => $package_type,
            "package_intro" => $package_intro,
            "suit_student"  => $suit_student,
            "subject"       => $subject,
            "grade"         => $grade,
        ]);
        return $this->get_last_insertid();
    }
}