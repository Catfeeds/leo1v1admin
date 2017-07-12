<?php
namespace App\Models;
use \App\Enums as E;
class t_apply_reg extends \App\Models\Zgen\z_t_apply_reg
{
	public function __construct()
	{
		parent::__construct();
	}

    public function add_info($phone,$name,$education,$residence,$gender,$birth,$english,$polity,$carded,$marry,$child,$email,$post,$dept,$address,$strong,$interest,$non_compete,$is_labor,$is_fre,$fre_name,$education_info,$work_info,$family_info,$add_time){
        $ret_count=$this->row_insert([
            'name'           => $name,
            'gender'         => $gender,
            'birth'          => $birth,
            'work_info'      => $work_info,
            'phone'          => $phone,
            'email'          => $email,
            'education_info' => $education_info,
            'residence'    => $residence,
            'english'         => $english,
            'polity'         => $polity,
            'carded'         => $carded,
            'marry'         => $marry,
            'child'         => $child,
            'post'         => $post,
            'dept'         => $dept,
            'address'         => $address,
            'strong'         => $strong,
            'interest'         => $interest,
            'non_compete'         => $non_compete,
            'is_labor'         => $is_labor,
            'is_fre'         => $is_fre,
            'fre_name'         => $fre_name,
            'family_info'         => $family_info,
            'add_time'         => $add_time
            
        ]);
        if($ret_count == 1){
            return $ret_count;
        }else{
            return fasle;
        }
        
    }

    public function phone_exist($phone){
        $sql = $this->gen_sql_new("select count(*) num from %s where phone = %u",
                              self::DB_TABLE_NAME,
                              $phone
        );
        return $this->main_get_row( $sql  );

    }
    public function apply_info($phone){
        $sql = $this->gen_sql_new("select * from %s where phone = %u",
                                  self::DB_TABLE_NAME,
                                  $phone
        );
        return $this->main_get_row( $sql  );

    }
    public function get_apply_info($page_num,$start_time,$end_time,$user_name){
        $where_arr = [
            ["add_time >= %u",$start_time,-1],
            ["add_time <= %u",$end_time,-1],
        ];
        if ($user_name) {
            $where_arr[]= " (name like '%" . $user_name . "%' or phone like '%" . $user_name . "%' or post like '%" . $user_name . "%') "   ;
        }

        $sql = $this->gen_sql_new("select * from %s where %s order by add_time desc",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list_by_page( $sql ,$page_num,10 );

    }

    public function apply_del($phone){
        $sql = sprintf("delete from %s  where phone = %u ",
                       self::DB_TABLE_NAME,
                       $phone
        );
        return $this->main_update( $sql  ); 
    }
}











