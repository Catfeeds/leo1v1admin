<?php
namespace App\Models;
use Illuminate\Support\Facades\Log;
use App\Models\Zgen as Z;

/**
 * 模型抽象类
 *
 * 一个关于各种模型的基本行为类，每个模型都必须继承这个类的方法
 * @package     Class
 * @author      jim
 */
/**
 * @property   \App\Console\Tasks\TaskController $task
 */
abstract class NewModel
{
    static function test() {

    }
    /**
       @var NewDB
     */
    var $db = null;

    public $field_id1_name="id";
    public $field_id2_name="";
    public $field_table_name="xxx.sss";

    public $last_sql;
    public $config_fix="";
    public $readony_on_select=true;
    public $readony_on_tongji_flag=false;
    /**
     * 构造函数
     * @global $this ->db 数据库类
     * @global $table 数据库表列表
     *
     * @return \NewModel
     */
    function __construct(  )
    {
        $this->db=NewDB::get($this->config_fix);
    }

    function switch_readonly_database() {
        $this->readony_on_tongji_flag=false;
        if ($this->config_fix){
            $this->db=NewDB::get($this->config_fix."_readonly");
        }else{
            $this->db=NewDB::get("readonly");
        }
    }

    function switch_readwrite_database() {
        $this->readony_on_tongji_flag=false;
        $this->db=NewDB::get($this->config_fix);
    }

    function switch_tongji_database() {
        $this->readony_on_tongji_flag=true;
        if ($this->config_fix){
            $this->db=NewDB::get($this->config_fix."_tongji");
        }else{
            $this->db=NewDB::get("tongji");
        }
    }

    /**
       @return NewDB
    */
    function get_db () {
        return $this->db;
    }


    /**
     * 处理插入数据库字符串
     *
     * @param string $string
     * @return string
     */
    function ensql($string)
    {
        $ret=$this->db->quote($string);
        return substr($ret,1,-1);
    }
    /**
     *　@return  int -  影响行数
     */
    public function main_update( $sql  )
    {
        return $this->db_exec($sql);
    }
    public function check_change_select_db() {
        return  ! $this->readony_on_tongji_flag && $this->readony_on_select &&  $this->db->get_transactions() ==0;
    }
    public function main_get_row( $sql)
    {

        $old_db=$this->db;

        if ($this->check_change_select_db() ) {
            $this->switch_readonly_database();
        }

        $result = $this->db_query($sql);

        if ( $this->check_change_select_db() ) {
            $this->db=$old_db;
        }
        if (count($result ) ==1  ) {
            return $result->fetch(\PDO::FETCH_ASSOC);
        }else{
            throw new \Exception('SQL ERROR >1 row ".count." : '.$sql);
        }
    }
    /*
    public function main_get_list_b2( $sql ,$list_key_func=null )
    {
        $result = $this->db_query($sql);
        $list=[];

        if ( ! $list_key_func ) {
            foreach( $result as $item ) {
                $list[] = $item;
            }
        }else{
            foreach( $result as $item ) {
                $list[ $list_key_func($item ) ] = $item;
            }
        }
        return $list;
    }
    */

    public function main_get_list( $sql ,$list_key_func=null )
    {

        $old_db=$this->db;
        if (  $this->check_change_select_db()   ) {
            $this->switch_readonly_database();
        }

        $result = $this->db_query($sql);
        $list=[];

        if ( ! $list_key_func ) {
            while ($item = $result->fetch(\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_NEXT)) {
                $list[] = $item;
            }
        }else{
            while ($item = $result->fetch(\PDO::FETCH_ASSOC,\PDO::FETCH_ORI_NEXT)) {
                $list[ $list_key_func($item) ] = $item;
            }
        }

        if (  $this->check_change_select_db() ) {
            $this->db=$old_db;
        }
        return $list;
    }

    public function main_get_list_as_page( $sql,$list_key_func=null ){
        $ret_arr=[];
        $ret_arr["page_info"] = array(
            "total_num"      => 1,
            "per_page_count" => 100000,
            "page_num"       => 1,
        );
        $ret_arr["list"]=$this->main_get_list($sql ,$list_key_func);
        return $ret_arr;
    }

    public function main_get_one_field_list( $sql   )
    {
        $db_ret=$this->main_get_list($sql);
        $ret_list=[];
        foreach ($db_ret as $item ){
            foreach( $item as  $v ){
                $ret_list[]=$v;
                break;
            }
        }
        return $ret_list;
    }


    /**
     *　@return int - 影响行数
     */
    public function db_exec($sql) {
        $this->last_sql=$sql;
        return $this->do_error( $this->db->exec($sql),$sql);
    }

    public function db_query($sql) {
        $this->last_sql=$sql;
        $start=time(NULL);
        $ret=$this->db->query($sql);
        $end=time(NULL);
        $diff= $end -$start ;
        $hour=date("H");
        if ($diff>  5 && $hour>6 ) {
            if (!$this->readony_on_tongji_flag) {
                \App\Helper\Utils::logger("SLOWSQL:use $diff s:$sql" );

                $account=@$_SESSION["acc"];
                $bt_str= "user:$account<br/>.url:" .substr( @$_SERVER["REQUEST_URI"],0,30). "<br/>";
                $bt_str.= date("H:i:s")." SLOWSQL:use $diff s:$sql <br/>" ;

                $e=new \Exception();
                foreach( $e->getTrace() as &$bt_item ) {
                    //$args=json_encode($bt_item["args"]);
                    $bt_str.= @$bt_item["class"]. @$bt_item["type"]. @$bt_item["function"]."---".
                        @$bt_item["file"].":".@$bt_item["line"]. "<br/>";
                }

                dispatch( new \App\Jobs\send_error_mail("","$bt_str", "" ,\App\Enums\Ereport_error_type::V_2  ));
            }
        }
        return $this->do_error($ret ,$sql);
    }

    function do_error($result,$sql) {
        if ($result===false) {
            $errorInfo=$this->db->errorInfo();
            $str= "MYSQL:ERR:". $errorInfo[1]. ":". $errorInfo[2] . ":$sql";
            throw new \Exception($str);
        }
        return $result;
    }

    public function main_insert($sql)
    {
        return $this->db_exec($sql);
    }

    public function main_get_page_random($sql,$page_count=5 ,$use_group_by_flag=false )
    {
        $pattern     = '/select\s+(.*?)\s+from\s+(.*)/i';
        $replacement = 'from $2';
        $count_query = preg_replace($pattern, $replacement, $sql);


        $ret_list=[];
        $count_query = "select count(*) " . $count_query;

        if ( !$use_group_by_flag ){
            //$count=$this->main_get_value($count_query,0);
            $count=50;
        }else{
            //$count=count($this->main_get_list($count_query ));
            $count=50;
        }
        if ($count>50) {
            $count=50;
        }
        \App\Helper\Utils::logger("row count: $count ");


        if ($count>0) {
            $id_map=[];

            for( $i=0;$i<$page_count; $i++) {
                $limit_start=rand()%$count;
                if (!isset($id_map[$limit_start] )) {
                    $id_map[$limit_start]=true;
                    $tmp_sql=$sql."  limit $limit_start,1";
                    $row=$this->main_get_row($tmp_sql );
                    if($row) {
                        $ret_list[]=$row;
                    }
                }
            }
        }
        if (count($ret_list) ==0 ) {
            $tmp_sql=$sql."  limit 1,1";
            $row=$this->main_get_row($tmp_sql );
            if($row) {
                $ret_list[]=$row;
            }
        }
        return \App\Helper\Utils::list_to_page_info($ret_list);
    }

    public function main_get_list_by_page($sql,$page_info,$page_count=10,$use_group_by_flag=false,$order_str="",$list_key_func=null )
    {
        if(is_array($page_info)){
            $page_num= $page_info["page_num"];
            if ($page_count==10) {
                $page_count= $page_info["page_count"];
                if ($page_count<1) {
                    $page_count=10;
                }
            }
        }else if ($page_info == null )  {
            $page_num   = 1;
            $page_count = 100000000;
        }else{
            $page_num = $page_info;
        }

        $pattern     = '/select\s+(.*?)\s+from\s+(.*)/is';
        $replacement = 'from $2';
        $count_query = preg_replace($pattern, $replacement, $sql);

        if ($page_num== 0xFFFFFFFF+1 || $page_num== 0xFFFFFFFF+2  ) { //get_all
            $page_num   = 1;
            $page_count = 10000;
        }
        $count_query = "select count(1) " . $count_query;
        $ret_arr=array();
        if ( !$use_group_by_flag ){
            $count=$this->main_get_value($count_query,0);
        }else{
            $count=count($this->main_get_list($sql ));
        }


        //for old
        $ret_arr["total_num"]=$count;
        $ret_arr["per_page_count"]=$page_count;

        $ret_arr["page_info"] = array(
            "total_num"      => $count,
            "per_page_count" => $page_count,
            "page_num"       => $page_num,
        );

        $limit_start=($page_num-1)*$page_count;

        $sql.=" $order_str limit $limit_start,$page_count";

        $ret_arr["list"]=$this->main_get_list($sql,$list_key_func);

        return $ret_arr;
    }


    public function main_get_value( $sql, $default_value=0  )
    {
        $row=$this->main_get_row( $sql);
        if (is_array($row)){
            foreach( $row as  $v ){
                return $v;
            }
        }else{
            return  $default_value;
        }
    }

    public function field_get_value( $id, $field_name ) {
        if ( is_array ($id) ) {
            $where_str=$this->where_str_gen($id);
            $sql=sprintf("select %s from %s  where  %s ", $field_name, $this->field_table_name ,
                           $where_str);
            return $this->main_get_value($sql);

        }else{
            return $this->get_field_value($this->field_table_name,$field_name,
                               $this->field_id1_name ,$id);
        }
    }


    public function field_get_list( $id, $field_name_list_str ) {
        if ( is_array ($id) ) {
            $where_str=$this->where_str_gen($id);
            $sql=sprintf("select %s from %s  where  %s ", $field_name_list_str,
                         $this->field_name_list_str,
                         $where_str);

            return $this->main_get_row($sql);
        }else{
            return $this->get_field_list($this->field_table_name,$field_name_list_str,
                               $this->field_id1_name ,$id);
        }
    }


    public function field_update_list ( $id, $set_field_arr) {
        if(is_array($id)){
            $where_str          = $this->where_str_gen($id);
            $set_field_list_str = $this->get_sql_set_str( $set_field_arr);

            $sql = sprintf("update %s set  %s  where  %s", $this->field_table_name , $set_field_list_str,
                         $where_str);
            $this->start_transaction();
            $ret = $this->main_update($sql);
            if ($ret>1) {
                \App\Helper\Utils::logger("UPDATE COUNT ERROR: count=$ret, field_update_list need count=1 : SQL:$sql  ");
                $this->rollback();
                $ret-1;
            }else{
                $this->commit();
            }
        }else{
            $ret = $this->update_field_list($this->field_table_name,$set_field_arr,$this->field_id1_name,$id);
        }

        return $ret;
    }


    function row_insert_ignore( $arr  ) {
        return $this->row_insert($arr,false,true);
    }
    /**
     * 插入数据库数据
     */
    function row_insert( $arr, $update_on_existed=false, $ignore_flag=false, $check_null_flag=false )
    {
        //构建SQL语句
        $name_arr=[];
        $value_arr=[];
        foreach( $arr as $key=> $value ) {
            $name_arr[]=$key;
            if ( $check_null_flag && $value === NULL) {
                $value_arr[]= "NULL" ;
            }else{
                $value_arr[]= "'". $this->ensql($value)."'" ;
            }
        }

        $name_arr_str=join(",", $name_arr);
        $value_arr_str=join(",", $value_arr);

        $ignore_str="";
        if ($ignore_flag ) {
            $ignore_str="ignore";
        }

        $sql = 'insert  ' .$ignore_str.' into ' . $this->field_table_name . " (". $name_arr_str. ") values (" . $value_arr_str. ")";
        if ($update_on_existed) {
            $sql.=" ON DUPLICATE KEY UPDATE ". $this->get_sql_set_str( $arr);
        }
        return $this->main_insert($sql);
    }


    //like sprintf but default use ensql

    // arg use array
    //gen_sql( "update %s where %s"  , "t_abc", ["userid=100"]  )
    public function gen_sql( $fmt_str , $__args__ ) {
        $args=func_get_args();
        $ret_args=[];
        foreach( $args as $index=> $item) {
            if ( $index ==0 ) { //
                $ret_args[]=$item;
            }else{
                if (is_array($item)) {
                    $is_ensql=false;
                    $str=$item[0];
                }else{
                    $is_ensql=true;
                    $str=$item;
                }

                if ($is_ensql) {
                    $ret_args[]=$this->ensql($str);
                }else{
                    $ret_args[]=$str;
                }
            }
        }
        return call_user_func_array("sprintf",$ret_args );
    }

    public function where_arr_adminid_in_list(&$where_arr,$field_name, $in_adminid_list ) {
        if (count ($in_adminid_list)>0)  {
            $where_arr[]=sprintf(  "$field_name in (%s) ",  join(",", $in_adminid_list ));
        }
    }

    public function where_arr_teacherid(&$where_arr,$field_name, $teacherid_arr,$check_flag=true ) {
        if (count ($teacherid_arr)>0)  {
            $where_arr[]=sprintf(  "$field_name in (%s) ",  join(",", $teacherid_arr ));
        }elseif($check_flag){
            $where_arr[] = $field_name ."= -100";
        }else{

        }
    }

    public function where_arr_add_time_range(&$where_arr,$field_name, $start_time, $end_time ) {
        $where_arr[]= ["$field_name>=%d" ,  $start_time,-1 ];
        $where_arr[]= ["$field_name<%d" ,  $end_time,-1 ];
    }
    public function where_arr_add_set_boolean_flag(&$where_arr,$field_name, $value) {
        if ($value==-2) {
            $where_arr[]= "$field_name<>2";
        }else{
            $where_arr[]= ["$field_name=%d" , $value, -1 ];
        }
    }


    /*
    $.admin_select_user(
        $('#id_origin_assistantid'),
        "admin", load_data ,false, {
            " main_type": 1,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                "label": "[未分配]",
                "value": 0
            }]
        }
    );
    */
    //-2 已分配
    public function where_arr_add__2_setid_field(&$where_arr,$field_name, $value) {
        if ($value==-2) {
            $where_arr[]= "$field_name<>0";
        }else{
            $where_arr[]= ["$field_name=%d" , $value, -1 ];
        }
    }

    public function where_arr_add_int_or_idlist ( &$where_arr,$field_name, $value, $def_value=-1 ) {
        if(is_int($value )){
            $where_arr[] = ["$field_name=%d",$value,$def_value];
        }else{
            $where_arr[] = $this->where_get_in_str_query($field_name,$value);
        }
    }

    public function where_arr_add_int_field(&$where_arr,$field_name, $value, $def_value=-1 ) {
        $where_arr[] = ["$field_name=%d" , $value, $def_value ];
    }

    public function where_arr_add_str_field(&$where_arr,$field_name, $value, $def_value="" ) {
        $where_arr[]= ["$field_name='%s'" , $value, $def_value ];
    }

    public function where_arr_add_boolean_for_value_false(&$where_arr,$field_name, $value, $need_is_null_flag=false) {
        if ($value ==0 ) {
            $where_arr[]= "$field_name<>0";
        }else if ( $value==1){
            if ($need_is_null_flag ) {
                $where_arr[]= "($field_name=0 or  $field_name is null)";
            }else{

                $where_arr[]= "$field_name=0";
            }
        }
    }

    public function where_arr_add_boolean_for_value(&$where_arr,$field_name, $value, $need_is_null_flag=false) {
        if ($value ==1 ) {
            $where_arr[]= "$field_name<>0";
        }else if ( $value==0){
            if ($need_is_null_flag ) {
                $where_arr[]= "($field_name=0 or  $field_name is null)";
            }else{

                $where_arr[]= "$field_name=0";
            }
        }
    }


    public function where_arr_add_boolean_for_str_value(&$where_arr,$field_name, $value, $need_is_null_flag=false) {
        if ($value ==1 ) {
            $where_arr[]= "$field_name<>'' ";
        }else if ( $value==0){
            if ($need_is_null_flag ) {
                $where_arr[]= "($field_name='' or  $field_name is null)";
            }else{

                $where_arr[]= "$field_name=''";
            }
        }
    }

    public function gen_sql_new( $fmt_str , $__args__ ) {
        if (is_array($fmt_str)  ) {
            $fmt_str = join($fmt_str, " " );
        }
        $args=func_get_args();
        $ret_args=[];
        foreach( $args as $index=> $item) {
            if ( $index ==0 ) { //
                $ret_args[]=$fmt_str ;
            }else{
                if (is_array($item)) {
                    $str=$this->where_str_gen($item);
                    $is_ensql=false;
                }else{
                    $is_ensql=true;
                    $str=$item;
                }

                if ($is_ensql) {
                    $ret_args[]=$this->ensql($str);
                }else{
                    $ret_args[]=$str;
                }
            }
        }
        return call_user_func_array("sprintf",$ret_args );
    }


    public function get_field_value( $table_name, $field_name,$id_name ,$id_value ) {
        $sql=sprintf("select %s from %s  where  %s='%s' ", $field_name,$table_name,
                     $id_name , $this->ensql($id_value) );
        return $this->main_get_value($sql);
    }
    public function get_field_list ( $table_name, $field_name,$id_name ,$id_value ) {
        $sql=sprintf("select %s from %s  where  %s='%s' ", $field_name,$table_name,
                     $id_name , $this->ensql( $id_value));
        return $this->main_get_row($sql);
    }

    public function row_delete( $id ) {
        $sql=sprintf("delete from %s  where  %s='%s' ",
                     $this->field_table_name,
                     $this->field_id1_name, $this->ensql($id));
        return $this->main_update($sql);
    }

    public function get_sql_set_str($set_field_arr ) {
        $update_str_arr = [];
        foreach( $set_field_arr as $key=> $item ) {
            if (!is_numeric( $key)) { //
                $item=[$key,$item];
            }

            if ( isset($item[2]) &&  $item[2]=="+") {
                $update_str_arr[]=sprintf("%s = %s + (%s)", $item[0], $item[0], $this->ensql( $item[1]) );
            } else if (  isset($item[2]) && $item[2]=="-") {
                $update_str_arr[]=sprintf("%s = %s - (%s)", $item[0], $item[0], $this->ensql( $item[1]) );

            }else{
                if ($item[1]===null) {
                    $update_str_arr[]=sprintf("%s=NULL", $item[0] );
                }else{
                    $update_str_arr[]=sprintf("%s='%s'", $item[0], $this->ensql( $item[1]) );
                }
            }
        }

        return join(",", $update_str_arr );
    }

    public function update_field_list( $table_name, $set_field_arr,$id_name ,$id_value ) {
        $set_field_list_str=$this->get_sql_set_str( $set_field_arr);
        $sql=sprintf("update %s set  %s  where  %s= '%s' ", $table_name, $set_field_list_str,
                     $id_name , $this->ensql( $id_value));
        return $this->main_update($sql);
    }

    public function row_delete_2( $id_value, $id_value_2  ) {
        $sql=sprintf("delete from %s  where  %s='%s' and %s='%s' ", $this->field_table_name ,
                     $this->field_id1_name , $this->ensql($id_value),
                     $this->field_id2_name,  $this->ensql($id_value_2)
        );
        return $this->main_update($sql);
    }

    public function field_get_value_2(  $id_value, $id_value_2  ,$field_name ) {
        $sql=sprintf("select %s from %s  where  %s='%s' and %s='%s' ", $field_name,
                     $this->field_table_name ,
                     $this->field_id1_name ,
                     $this->ensql($id_value),
                     $this->field_id2_name,
                     $this->ensql($id_value_2)
        );
        return $this->main_get_value($sql);
    }

    public function field_get_list_2 ($id_value , $id_value_2, $field_name_list_str  ) {
        $sql = sprintf("select %s from %s  where  %s='%s' and %s='%s' ", $field_name_list_str,$this->field_table_name ,
                       $this->field_id1_name ,
                       $this->ensql($id_value),
                       $this->field_id2_name,
                       $this->ensql($id_value_2)
        );
        return $this->main_get_row($sql);
    }

    public function field_update_list_2( $id_value , $id_value_2 , $set_field_arr ) {
        $set_field_list_str= $this->get_sql_set_str($set_field_arr);
        $sql=sprintf("update %s set  %s  where  %s='%s' and %s='%s' ",
                     $this->field_table_name , $set_field_list_str,
                     $this->field_id1_name , $this->ensql( $id_value) ,
                     $this->field_id2_name, $this->ensql($id_value_2) );
        return $this->main_update($sql);
    }

    function start_transaction(){
        return $this->db->beginTransaction();
    }

    function commit (){
        return $this->db->commit();
    }

    function rollback(){
        return  $this->db->rollBack();
    }

    public function check_and_add_where_limit($where_str){
        $where_str=trim($where_str);
        if ( preg_match("/\bor\b/i",$where_str) &&  $where_str[0] != "(" ) {
            $where_str="($where_str)";
        }
        return $where_str;
    }

    public function where_field_gen($fmt_str ,$value ){
        $args = func_get_args();
        if ( is_array($args[1]) ){
            $tmp_args   = array() ;
            $tmp_args[] = $args[0];
            foreach ($args[1] as $v ) {
                $tmp_args[]= $this->ensql( $v);
            }
            $args= $tmp_args;
        }else{
            $args[1]= $this->ensql($args[1]);
        }
        return $this->check_and_add_where_limit( call_user_func_array( "sprintf",$args  ));
    }

    public function sub_where_str_gen( $where_arr , $join_cmd="and" ) {
       return "(".$this->where_str_gen($where_arr, "$join_cmd").")";
    }

    public function where_str_gen( $where_arr , $join_cmd="and" ){
        $item_arr=[];
        foreach( $where_arr as $k=> $item  ){
            if (is_array($item)) {
                $fmt_str  = $item[0];
                $value    = $item[1];
                $add_flag = true;
                if(isset( $item[2] )){
                    $no_deal_value=$item[2];
                    if (is_array( $value ) ) {
                        if (count($value)==1  && $value[0] == $no_deal_value  ){
                            $add_flag=false;
                        }
                    }else{
                        if ( $value == $no_deal_value ){
                            $add_flag=false;
                        }
                    }
                }
                if ($add_flag){
                    $item_arr[]=$this->where_field_gen($fmt_str,$value );
                }
            }else{
                if ( is_int( $k)) {
                    $item_arr[]= $this->check_and_add_where_limit($item);
                }else{
                    $item_arr[]= sprintf("%s='%s'", $k, $this->ensql($item) )   ;
                }
            }
        }
        if (count ($item_arr)==0 ) {
            return " true ";
        }else{
            return join(" ".$join_cmd . " " ,  $item_arr );
        }
    }

    public function where_get_in_str_query( $field_name, $id_list  ) {
        if (is_array($id_list)) {
            if ( array_key_exists( "start", $id_list))  {
                if ($id_list["start"]===null ) {
                    return "true";
                }else{
                    $start = $id_list["start"];
                    $end   = $id_list["end"];
                    return "($field_name  between $start and $end )";
                }
            }else{
                $new_id_list=[];
                $all_flag=false;
                foreach ( $id_list as $id ) {
                    $id=intval($id);
                    if ($id==-1) {
                        $all_flag=true;
                    }
                    $new_id_list[]= $id;
                }
                if ($all_flag) {
                    return "true";
                }

                if ( count($new_id_list)>0) {
                    return "$field_name in  (" .join("," ,$new_id_list).  ")";
                }else{
                    return "true";
                }
            }
        }else{
            return "false";
        }
    }

    public function where_get_in_str( $field_name, $id_list, $null_is_true=true   ) {
        $new_id_list=[];
        foreach ( $id_list as $id ) {
            $id=intval($id);
            $new_id_list[]= $id;
        }
        if (count($new_id_list)==0){
            if ($null_is_true  ) {
                return "true";
            }else{
                return "false";
            }
        }else{
            return "$field_name in  (" .join("," ,$new_id_list).  ")";
        }
    }

    public function where_get_not_in_str( $field_name, $id_list, $null_is_true=true   ) {
        $new_id_list=[];
        foreach ( $id_list as $id ) {
            $id=intval($id);
            $new_id_list[]= $id;
        }
        if (count($new_id_list)==0){
            if ($null_is_true  ) {
                return "true";
            }else{
                return "false";
            }
        }else{
            return "$field_name not in  (" .join("," ,$new_id_list).  ")";
        }
    }

    public function get_last_insertid(){
        return $this->db->lastInsertId();
    }

    public function __get( $name ) {
        if ($name == "task" ) {
            return $this->$name= new \App\Console\Tasks\TaskController();
        }else if (substr($name ,0,2  ) == "t_") {
            $reflectionObj = new \ReflectionClass( "App\\Models\\$name");
            return $this->$name= $reflectionObj->newInstanceArgs();
        }else{
            throw new \Exception() ;
        }
    }

    public function where_get_subject_grade_str($grade,$subject){
        if($grade==-1 && $subject==-1){
            return true;
        }elseif($grade==-1 && $subject != -1){
            return "t.subject=".$subject;
        }elseif($grade!=-1 && $subject == -1){
            if($grade==101 || $grade==102 || $grade==103){
                return "((t.grade_start=1 and t.grade_end>=1) or t.grade_part_ex in (1,4) or t.second_grade in (1,4) or t.third_grade in (1,4))";
            }elseif($grade==104 || $grade==105){
                return "((t.grade_start>0 and t.grade_start<=2 and t.grade_end>=2) or t.grade_part_ex in (1,4) or t.second_grade in (1,4) or t.third_grade in (1,4))";
            }elseif($grade==106){
                 return "((t.grade_start>0 and t.grade_start<=2 and t.grade_end>=2) or t.grade_part_ex in (1,4,6) or t.second_grade in (1,4,6) or t.third_grade in (1,4,6))";
            }elseif($grade==201 || $grade==202){
                return "((t.grade_start>0 and t.grade_start<=3 and t.grade_end>=3) or t.grade_part_ex in (2,4,5,6) or t.second_grade in (2,4,5,6) or t.third_grade in (2,4,5,6))";
            }elseif($grade==203){
                 return "((t.grade_start>0 and t.grade_start<=4 and t.grade_end>=4) or t.grade_part_ex in (2,4,5,6,7) or t.second_grade in (2,4,5,6,7) or t.third_grade in (2,4,5,6,7))";
            }elseif($grade==301 || $grade==302){
                return "((t.grade_start>0 and t.grade_start<=5 and t.grade_end>=5) or t.grade_part_ex in (3,5,7) or t.second_grade in (3,5,7) or t.third_grade in (3,5,7))";
            }elseif($grade==303){
                 return "((t.grade_start>0 and t.grade_start<=6 and t.grade_end>=6) or t.grade_part_ex in (3,5,7) or t.second_grade in (3,5,7) or t.third_grade in (3,5,7))";
            }
        }else{
            if($grade==101 || $grade==102 || $grade==103){
                return "((((t.grade_start=1 and t.grade_end>=1) or t.grade_part_ex in (1,4)) and t.subject=".$subject.") or (t.second_grade in (1,4) and t.second_subject=".$subject.") or (t.third_grade in (1,4) and t.third_subject=".$subject."))";
            }elseif($grade==104 || $grade==105){
                return "((((t.grade_start>0 and t.grade_start<=2 and t.grade_end>=2) or t.grade_part_ex in (1,4)) and t.subject=".$subject.") or (t.second_grade in (1,4) and t.second_subject =".$subject.") or (t.third_grade in (1,4) and t.third_subject=".$subject."))";
            }elseif($grade==106){
                return "((((t.grade_start>0 and t.grade_start<=2 and t.grade_end>=2) or t.grade_part_ex in (1,4,6)) and t.subject=".$subject.") or (t.second_grade in (1,4,6) and t.second_subject =".$subject.") or (t.third_grade in (1,4,6) and t.third_subject=".$subject."))";

            }elseif($grade==201 || $grade==202){
                return "((((t.grade_start>0 and t.grade_start<=3 and t.grade_end>=3) or t.grade_part_ex in (2,4,5,6)) and t.subject=".$subject.") or (t.second_grade in (2,4,5,6) and t.second_subject =".$subject.") or (t.third_grade in (2,4,5,6) and t.third_subject=".$subject."))";
            }elseif($grade==203){
                return "((((t.grade_start>0 and t.grade_start<=4 and t.grade_end>=4) or t.grade_part_ex in (2,4,5,6,7)) and t.subject=".$subject.") or (t.second_grade in (2,4,5,6,7) and t.second_subject =".$subject.") or (t.third_grade in (2,4,5,6,7) and t.third_subject=".$subject."))";
            }elseif($grade==301 || $grade==302){
                return "((((t.grade_start>0 and t.grade_start<=5 and t.grade_end>=5) or t.grade_part_ex in (3,5,7)) and t.subject=".$subject.") or (t.second_grade in (3,5,7) and t.second_subject =".$subject.") or (t.third_grade in (3,5,7) and t.third_subject=".$subject."))";

            }elseif($grade==303){
                return "((((t.grade_start>0 and t.grade_start<=6 and t.grade_end>=6) or t.grade_part_ex in (3,5,7)) and t.subject=".$subject.") or (t.second_grade in (3,5,7) and t.second_subject =".$subject.") or (t.third_grade in (3,5,7) and t.third_subject=".$subject."))";
            }
        }
    }

    //@desn:获取分页信息[union查询]
    //@param:$is_union false 非联结查询 1 union 2 union all
    public function main_get_list_by_page_with_union($sql,$page_info,$page_count=10,$use_group_by_flag=false,$order_str="",$list_key_func=null,$is_union=null)
    {
        if(is_array($page_info)){
            $page_num= $page_info["page_num"];
            if ($page_count==10) {
                $page_count= $page_info["page_count"];
                if ($page_count<1) {
                    $page_count=10;
                }
            }
        }else if ($page_info == null )  {
            $page_num   = 1;
            $page_count = 100000000;
        }else{
            $page_num = $page_info;
        }

        $pattern     = '/select\s+(.*?)\s+from\s+(.*)/is';
        $replacement = 'from $2';

        if($is_union == 2){
            $sql_array = explode('union all',$sql);
            $count_query_1 = preg_replace($pattern, $replacement, $sql_array[0]); 
            $count_query_2 = preg_replace($pattern, $replacement, $sql_array[1]); 
        }elseif($is_union == 1){
            $sql_array = explode('union all',$sql);
            $count_query_1 = preg_replace($pattern, $replacement, $sql_array[0]); 
            $count_query_2 = preg_replace($pattern, $replacement, $sql_array[1]); 
        }else{
            $count_query = preg_replace($pattern, $replacement, $sql);
        }

        if ($page_num== 0xFFFFFFFF+1 || $page_num== 0xFFFFFFFF+2  ) { //get_all
            $page_num   = 1;
            $page_count = 10000;
        }
        if($is_union){
            $count_query_1 = "select count(1) " . $count_query_1;
            $count_query_2 = "select count(1) " . $count_query_2;
        }else
            $count_query = "select count(1) " . $count_query;
        $ret_arr=array();
        if ( !$use_group_by_flag ){
            if($is_union){
                $count_1 = $this->main_get_value($count_query_1);
                $count_2 = $this->main_get_value($count_query_2);
                $count = $count_1+$count_2;
            }else
                $count=$this->main_get_value($count_query,0);
        }else{
            $count=count($this->main_get_list($sql ));
        }

        //for old
        $ret_arr["total_num"]=$count;
        $ret_arr["per_page_count"]=$page_count;

        $ret_arr["page_info"] = array(
            "total_num"      => $count,
            "per_page_count" => $page_count,
            "page_num"       => $page_num,
        );

        $limit_start=($page_num-1)*$page_count;

        $sql.=" $order_str limit $limit_start,$page_count";

        $ret_arr["list"]=$this->main_get_list($sql,$list_key_func);
        return $ret_arr;
    }

    /**
     * 获取表别名前缀
     * @param string alias 自定义的表别名
     * @return string
     */
    private function get_table_alias($alias=''){
        if($alias!=''){
            $alias .= ".";
        }
        return $alias;
    }

    /**
     * 添加有效课程的条件语句
     * @param string alias 表别名
     * @param array merge_arr 待合并的sql数组
     * @return array
     */
    public function lesson_common_sql($alias='',$merge_arr=[]){
        $alias = $this->get_table_alias($alias);
        $where_arr = [
            $alias."lesson_del_flag=0",
            $alias."confirm_flag!=2",
        ];
        return array_merge($where_arr,$merge_arr);
    }

    /**
     * 课程的时间筛选条件
     * @param int start_time 开始时间
     * @param int end_time   结束时间
     * @param string alias 表别名
     * @param array merge_arr 待合并的sql数组
     * @return array
     */
    public function lesson_start_sql($start_time,$end_time,$alias='',$merge_arr=[]){
        $alias = $this->get_table_alias($alias);
        $where_arr = [
            [$alias."lesson_start>%u",$start_time,0],
            [$alias."lesson_start<%u",$end_time,0],
        ];
        return array_merge($where_arr,$merge_arr);
    }

    /**
     * 时间范围内有效课程的筛选条件
     * @param int start_time 开始时间
     * @param int end_time   结束时间
     * @param string alias   表别名
     * @param array merge_arr 待合并的sql数组
     * @return array
     */
    public function lesson_start_common_sql($start_time,$end_time,$alias='',$merge_arr=[]){
        $where_arr = $this->lesson_start_sql($start_time,$end_time,$alias,$merge_arr);
        $where_arr = $this->lesson_common_sql($alias,$where_arr);
        return $where_arr;
    }

    /**
     * 可带课的老师筛选条件
     * @param string alias 表别名
     * @param array merge_arr 待合并的sql数组
     * @return array
     */
    public function teacher_common_sql($alias='',$merge_arr=[]){
        $alias = $this->get_table_alias($alias);
            $where_arr = [
                $alias."trial_lecture_is_pass=1",
                $alias."train_through_new_time>0",
                $alias."train_through_new=1",
                $alias."wx_use_flag=1",
                $alias."is_test_user=0",
            ];
        return array_merge($where_arr,$merge_arr);
    }

    /**
     * 可带课的老师筛选条件
     * @param string alias 表别名
     * @param array merge_arr 待合并的sql数组
     * @return array
     */
    public function teacher_common_test_sql($alias='',$merge_arr=[]){
        $alias = $this->get_table_alias($alias);
            $where_arr = [
                $alias."trial_lecture_is_pass=1",
                $alias."train_through_new_time>0",
                $alias."train_through_new=1",
                $alias."wx_use_flag=1",
            ];
        return array_merge($where_arr,$merge_arr);
    }


    /**
     * 获取计算学生有效课程的筛选条件
     * @param string alias 表别名
     * @param array merge_arr 待合并的sql数组
     */
    public function student_effective_lesson_sql($alias='',$merge_arr=[]){
        $alias = $this->get_table_alias($alias);
        $where_arr = [
            $alias."lesson_del_flag=0",
            $alias."confirm_flag in (0,1,3)",
        ];
        return array_merge($where_arr,$merge_arr);
    }

    /**
     * 获取计算老师有效课程的筛选条件
     * @param string alias 表别名
     * @param array merge_arr 待合并的sql数组
     */
    public function teacher_effective_lesson_sql($alias='',$merge_arr=[]){
        $alias = $this->get_table_alias($alias);
        $where_arr = [
            $alias."lesson_del_flag=0",
            $alias."confirm_flag !=2",
        ];
        return array_merge($where_arr,$merge_arr);
    }

    /**
     * 模糊查询老师信息
     * @param string search_info 模糊查询的内容
     * @param array  merge_arr   待合并的sql数组
     */
    public function teacher_search_info_sql($search_info,$alias='',$merge_arr=[]){
        if($search_info!=""){
            $alias = $this->get_table_alias($alias);
            if($search_info>0){
                $search_sql = $this->gen_sql_new("(".$alias."teacherid=%u or "
                                                 .$alias."phone like '%s%%'"
                                                 .")"
                                                 ,$search_info
                                                 ,$search_info
                );
            }else{
                $search_sql = $this->gen_sql_new("(".$alias."nick like '%s%%' or "
                                                 .$alias."realname like '%s%%'"
                                                 .")"
                                                 ,$search_info
                                                 ,$search_info
                );
            }
            $merge_arr[] = $search_sql;
        }
        return $merge_arr;
    }

    /**
     * 模糊查询学生信息
     * @param string search_info 模糊查询的内容
     * @param array  merge_arr   待合并的sql数组
     */
    public function student_search_info_sql($search_info,$alias='',$merge_arr=[]){
        if($search_info!=""){
            $alias = $this->get_table_alias($alias);
            if($search_info>0){
                $search_sql = $this->gen_sql_new("(".$alias."userid=%u or "
                                                 .$alias."phone like '%s%%'"
                                                 .")"
                                                 ,$search_info
                                                 ,$search_info
                );
            }else{
                $search_sql = $this->gen_sql_new("(".$alias."nick like '%s%%' or "
                                                 .$alias."realname like '%s%%' "
                                                 .")"
                                                 ,$search_info
                                                 ,$search_info
                );
            }
            $merge_arr[] = $search_sql;
        }
        return $merge_arr;
    }


    /**
     * 模糊查询家长信息
     * @param string search_info 模糊查询的内容
     * @param array  merge_arr   待合并的sql数组
     */
    public function parent_search_info_sql($search_info,$alias='',$merge_arr=[]){
        if($search_info!=""){
            $alias = $this->get_table_alias($alias);
            $search_sql = $this->gen_sql_new("(".$alias."nick like '%%%s%%' or "
                                             .$alias."phone like '%%%s%%' or "
                                             .$alias."parentid like '%%%s%%')"
                                             ,$search_info
                                             ,$search_info
                                             ,$search_info
            );
            $merge_arr[] = $search_sql;
        }
        return $merge_arr;
    }
}