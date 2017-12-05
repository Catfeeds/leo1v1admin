<?php
namespace App\Models;
use \App\Enums as E;
class t_order_activity_config extends \App\Models\Zgen\z_t_order_activity_config
{
    public function __construct()
    {
        parent::__construct();
    }



    public function get_list($where_arr,$page_num)
    {
        $where_str=$this->where_str_gen( $where_arr);
        $sql = $this->gen_sql("select * from %s where  %s order by id desc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,$page_num,10);

    }

    public function set_activity_info($phone,$admin_revisiterid){
        $sql = sprintf("update %s set admin_revisiterid = %u  where phone = '%s'",
                       self::DB_TABLE_NAME,
                       $admin_revisiterid,
                       $phone
        );
        $this->main_update( $sql  );
    }

    public function del_by_id($id){
        $sql=$this->gen_sql("delete from %s where id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_update($sql);

    }

    public function get_by_id($id){
        $sql=$this->gen_sql("select * from %s where id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
        );
        return $this->main_get_row($sql);
    }

    public function get_by_activity_id($id,$activity_id){
        $sql=$this->gen_sql("select id,activity_id from %s where id !=%u and activity_id=%u"
                            ,self::DB_TABLE_NAME
                            ,$id
                            ,$activity_id
        );
        return $this->main_get_row($sql);
    }


    public function get_activity_all_list($id){
        $sql=$this->gen_sql("select id,title from %s "
                            ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_activity_exits_list($idStr){
        if( $idStr && !strpos($idStr, ")") ){
            $idStr = "(" .$idStr. ")";
            $sql=$this->gen_sql("select id,title from %s where id in %s "
                                ,self::DB_TABLE_NAME
                                ,$idStr
            );
            return $this->main_get_list($sql);

        }
        return null;
    }

    public function get_current_activity($open_flag,$page_num){

        $where_arr = [
            ['date_range_start<=%d',time()],
            ['date_range_end>=%d',time()],
        ];
        if($open_flag != -1 && ( $open_flag == 1 || $open_flag == 2) ){
            $where_arr[] = ['open_flag = %d',$open_flag];
        }else{
            $where_arr[] = ['open_flag != %d ',0];
        }

        $where_str=$this->where_str_gen( $where_arr);

        $sql = $this->gen_sql("select * from %s where  %s order by power_value desc ,id desc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return  $this->main_get_list_by_page($sql,null);

    }

    public function get_all_activity($id,$open_flag,$title,$page_num){
        $where_arr = [
            ['id = %d',$id,-1],
            ['open_flag = %d',$open_flag,-1],
        ];
        if ($title) {
            $where_arr[]=sprintf( "title like '%s%%'",$this->ensql($title));
        }

        $where_str=$this->where_str_gen( $where_arr);

        $sql = $this->gen_sql("select * from %s where  %s order by id desc ",
                              self::DB_TABLE_NAME,
                              [$where_str]
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }
}
