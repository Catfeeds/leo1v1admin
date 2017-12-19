<?php
namespace App\Models;
use \App\Enums as E;
class t_question_type extends \App\Models\Zgen\z_t_question_type
{
	public function __construct()
	{
		parent::__construct();
	}

    public function question_type_list($subject,$open_flag){
        if($subject == 0){
            $where_arr=[
                ["subject=%u", $subject, -1],
                ["open_flag=%u", $open_flag, -1]
            ];

        }else{       
            $where_arr=[
                ["subject=%u or subject=0", $subject, -1],
                ["open_flag=%u", $open_flag, -1]
            ];
        }
        $where_str=$this->where_str_gen( $where_arr);
        $sql = $this->gen_sql("select * from %s where %s order by id asc ",                             
                              self::DB_TABLE_NAME,
                              $where_str
        );

        return $this->main_get_list($sql);
    }

    public function del_by_id($id){
        $sql=$this->gen_sql("delete from %s where id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_update($sql);
 
    }
    public function is_exit($name,$subject){

        $where_arr = [
            ["subject=%d" , $subject,-1 ],
        ];
        if ($name!=""){
            $where_arr[]=sprintf( "name = '%s' ", $this->ensql($name)) ;
        }
        $where_str=$this->where_str_gen( $where_arr);
        $sql = $this->gen_sql("select name from %s where %s order by id asc ",
                              self::DB_TABLE_NAME, [ $where_str ]
        );

        return $this->main_get_row($sql);
 
    }

}











