<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

/*
3. 修改字段的注释，代码如下：

ALTER TABLE `student` MODIFY COLUMN `id` COMMENT '学号';

查看字段的信息，代码如下：

SHOW FULL COLUMNS  FROM `student`;
*/

class table_manage extends Controller
{
    use CacheNick;

    public function get_db_table_name($db_name,$table_name, $set_utf8=true) {
        return $this->get_table("",$set_utf8)->gen_sql( "`%s`.`%s`", $db_name,$table_name );
    }
    public function select_list() {
        $this->get_in_str_val($field_name);


    }
    public function edit_table_data()
    {
        $db_name=$this->get_in_str_val("db_name","db_weiyi");
        $table_name=$this->get_in_str_val("table_name", "t_student_info");
        $id1=trim($this->get_in_str_val("id1"));
        $id2=trim($this->get_in_str_val("id2"));
        /**  @var  $table   \App\Models\t_student_info  */
        $table=$this->$table_name;
        $id1_name=$table->field_id1_name;
        $id2_name=$table->field_id2_name;

        if ($id2_name=="" ) {
            $row=$table->field_get_list($id1,"*");
        }else{
            $row=$table->field_get_list_2($id1,$id2,"*");
        }
        $list=[];
        if ($row) {

            $db_table_name=$this-> get_db_table_name( $db_name,$table_name ,false);
            $table_1=$this->get_table($db_name);
            //得到列信息
            $sql=$table_1->gen_sql( "show FULL COLUMNS  FROM %s" ,$db_table_name );
            $field_list=$table_1->main_get_list($sql,function($item){
                return $item["Field"];
            });
            foreach ($field_list as &$item) {
                $comment=@hex2bin($item["Comment"]) ;
                if ($comment) {
                    $item["Comment"]=$comment;
                }
            }


            foreach ( $row as $k =>$v ) {
                if ( is_int($k) || $k== $id1_name || $k==$id2_name ){
                    continue;
                }
                $list[]=["k" => $k , "v" => $v, "comment" =>  $field_list[$k]["Comment"] ];
            }
        }
        $ret_info=\App\Helper\Utils::list_to_page_info($list);

        return $this->pageView(__METHOD__,$ret_info,[
            "id1_name"   => $id1_name,
            "id2_name"   => $id2_name,
            "db_name"    => $db_name,
            "table_name" => $table_name,
        ]);
    }

    public function opt_table_log()
    {
        $start_time = $this->get_in_start_time_from_str( date("Y-m-d", time( ) -7*86400) );
        $end_time   = $this->get_in_end_time_from_str( date("Y-m-d", time( ) +86400) );
        $adminid = $this->get_in_int_val("adminid",-1);
        $sql_str = $this->get_in_str_val("sql_str","");
        $page_num= $this->get_in_page_num();

        $ret_info=$this->t_opt_table_log->get_list($page_num,$start_time,$end_time,$adminid,$sql_str);
        foreach( $ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"opt_time");
            $item["admin_nick"]=$this->cache_get_account_nick($item["adminid"]);

        }
        return $this->pageView(__METHOD__,$ret_info );
    }

    public function index()
    {
        $config_arr=[
            "db_weiyi"       => "t_student_info",
            "db_tool"        => "t_school_info",
            "db_weiyi_admin" => "t_admin_users",
            "db_account"     => "t_user_info",
        ];
        $db_name    = $this->get_in_str_val("db_name","db_weiyi");
        $table_name = $this->get_in_str_val("table_name", $config_arr[$db_name]);

        //得到table 注释
        $table         = $this->get_table($db_name);
        $db_table_name = $this->get_db_table_name( $db_name,$table_name );

        $sql = $table->gen_sql(" select TABLE_NAME,TABLE_COMMENT from information_schema.TABLES "
                               ." where TABLE_SCHEMA='%s' "
                               ." and TABLE_NAME <> 't_opt_table_log' "
                               ,$db_name
        );
        $table_list = $table->main_get_list($sql);
        foreach($table_list as &$t_item){
            $table_comment=@hex2bin($t_item["TABLE_COMMENT"]);
            if ($table_comment) {
                $t_item["TABLE_COMMENT"]=$table_comment;
            }
        }

        //得到列信息
        $sql  = $table->gen_sql( "show FULL COLUMNS  FROM %s" ,$db_table_name );
        $list = $table->main_get_list($sql);
        foreach($list as &$item){
            $comment=@hex2bin($item["Comment"]);
            if ($comment) {
                $item["Comment"]=$comment;
            }
        }

        $ret_info=\App\Helper\Utils::list_to_page_info($list);

        $create_row=$table->main_get_row("show create table $db_table_name");
        $create_table_str=($create_row["Create Table"]);

        return $this->pageView(__METHOD__,$ret_info,[
            "table_list"       => $table_list,
            "create_table_str" => $create_table_str,
        ] );
    }

    public function change_table_comment() {

        $db_name    = $this->get_in_str_val("db_name","db_weiyi");
        $table_name = $this->get_in_str_val("table_name","t_student_info");
        $comment    = bin2hex($this->get_in_str_val("comment",""));

        $table = $this->get_table($db_name);
        $sql   = $table->gen_sql(
            "alter table %s comment '%s' ",
            [$this-> get_db_table_name( $db_name,$table_name )], $comment);
        $table->main_update($sql);

        return outputjson_success();
    }
    public function change_field_comment() {
        $db_name    = $this->get_in_str_val("db_name","db_weiyi");
        $table_name = $this->get_in_str_val("table_name","t_student_info");
        $comment    = bin2hex($this->get_in_str_val("comment",""));
        $field = $this->get_in_str_val("field","");
        $table=$this->get_table($db_name);

        $db_table_name=$this-> get_db_table_name( $db_name,$table_name );
        $sql= $table->gen_sql( "show create table %s",  [$db_table_name] );
        $arr=$table->main_get_row($sql);
        $arr=explode("\n" ,$arr["Create Table"]);
        $field_key="`$field`";
        $field_key_len=strlen($field_key);
        $field_info="";
        foreach ($arr as $item) {
            $check_key=substr($item,2,$field_key_len );
            if ($field_key==$check_key) {
                $field_info=$item;
            }
        }

        if ($field_info  ) {

            $new_field_info=preg_replace("/ COMMENT .*/",
                                         sprintf( " COMMENT '%s' " , $table->ensql($comment) ),
                                         $field_info);
            if ($new_field_info == $field_info ) { // no comment
                $new_field_info=preg_replace('/,$/',
                                         sprintf( " COMMENT '%s' " , $table->ensql($comment) ),
                                         $field_info);
            }

            $sql=$table->gen_sql(
                "alter table %s modify COLUMN %s ",
                [$db_table_name], [ $new_field_info]);
            $table->main_update($sql);

        }

        return outputjson_success();

    }


    /**
     * @return  \App\Models\t_student_info
     */
    public function get_table($db_name, $set_utf8=true) {
        $table = $this->t_student_info;
        if ($set_utf8) {
            $table->main_get_value("set names utf8" );
        }
        return $table;
    }

    public function dev_info () {
        return $this->view(__METHOD__);
    }

    public function del_row() {
        $db_name=$this->get_in_str_val("db_name","");
        $table_name=$this->get_in_str_val("table_name", "");
        $id1=trim($this->get_in_str_val("id1"));
        $id2=trim($this->get_in_str_val("id2"));
        $field=trim($this->get_in_str_val("field"));
        $value=trim($this->get_in_str_val("value"));

        /**  @var  $table   \App\Models\t_student_info  */
        $table=$this->$table_name;
        $id1_name=$table->field_id1_name;
        $id2_name=$table->field_id2_name;


        if ($id2_name=="" ) {
            $ret=$table->row_delete($id1);
        }else{
            $ret=$table->row_delete_2($id1,$id2);
        }
        $this->t_opt_table_log->row_insert([
            \App\Models\t_opt_table_log::C_opt_time=> time(NULL),
            \App\Models\t_opt_table_log::C_adminid=> $this->get_account_id(),
            \App\Models\t_opt_table_log::C_sql_str => $table->last_sql ,
            \App\Models\t_opt_table_log::C_change_count => $ret,
        ]);

        return outputjson_success();
    }


    public function change_field_value() {
        $db_name=$this->get_in_str_val("db_name","db_weiyi");
        $table_name=$this->get_in_str_val("table_name", "t_student_info");
        $id1=trim($this->get_in_str_val("id1"));
        $id2=trim($this->get_in_str_val("id2"));
        $field=trim($this->get_in_str_val("field"));
        $value=trim($this->get_in_str_val("value"));

        /**  @var  $table   \App\Models\t_student_info  */
        $table=$this->$table_name;
        $id1_name=$table->field_id1_name;
        $id2_name=$table->field_id2_name;


        if ($id2_name=="" ) {
            $ret=$table->field_update_list($id1,[
                $field => $value
            ]);
        }else{
            $ret=$table->field_update_list_2($id1,$id2,[
                $field => $value
            ]);
        }
        $this->t_opt_table_log->row_insert([
            \App\Models\t_opt_table_log::C_opt_time=> time(NULL),
            \App\Models\t_opt_table_log::C_adminid=> $this->get_account_id(),
            \App\Models\t_opt_table_log::C_sql_str => $table->last_sql ,
            \App\Models\t_opt_table_log::C_change_count => $ret,
        ]);

        return outputjson_success();
    }
    public function tq_get_info_by_phone() {
        $adminuin=9747409;
        $adminpassword=strtoupper( md5("123") );
        $client = new \SoapClient("http://webservice.sh.tq.cn/Servers/services/ServerNew?wsdl");

        dd($client->__getFunctions());
        $phone="15601830229";
        $ret=$client->getVisitorInfoByPhone($adminuin, $adminpassword, "", $phone);

        $ret= \App\Helper\Common::xml2array( $ret );
        dd($ret);

    }

    public function tq_wsdl() {

        //adf
        try {

            $client = new \SoapClient("http://webservice.sh.tq.cn/Servers/services/ServerNew?wsdl");

            $adminuin=9747409;
            $adminpassword=strtoupper( md5("123") );

            //"string getPhoneRecordByClient(string $uin, string $adminuin, string $username, string $adminpassword, string $client_id, string $caller_id, string $called_id, string $startTime, string $endTime, string $is_third)"
            $startTime="2016-08-01 00:00:00";
            $endTime="2016-08-02 00:00:00";
            $ret=$client->getPhoneRecordByClient("" ,  $adminuin, "" ,  $adminpassword, "" , "" ,  "",  $startTime,  $endTime,  "");
            $ret_list=\App\Helper\Common::xml2array($ret);
            $arr= $ret_list["RECORD"] ;
            array_shift($arr);

            dd($arr);
            foreach  ($arr as $item ) {
                if(is_array($item["RecordFile"]) ){
                    $item["RecordFile"]="";
                }
                $duration=0;
                if(!is_array($item["duration"]) ){
                    $duration_arr=preg_split("/:/", $item["duration"]);
                    $duration=$duration_arr[0]*3600+ $duration_arr[1]*60+ $duration_arr[2];
                }

                if(is_array($item["Start_time"]) ){
                    $item["Start_time"]=$item["Insert_time"];
                }

                /*
                if(is_array($item["End_time"]) ){
                    $item["End_time"]="";
                }
                */

                $this->t_tq_call_info->add(
                    $item["PhoneRecId"],
                    $item["UIN"],
                    $item["Called_id"],
                    $item["Start_time"],
                    $item["End_time"],
                    $duration,
                    $item["Is_called_phone"],
                    $item["RecordFile"]);
            }

        } catch (\SOAPFault $e) {
            print $e;
        }
    }
    public function query()  {
        $this->check_and_switch_tongji_domain();
        // $this->t_admin_group->switch_tongji_database();

        $db_name   = $this->get_in_str_val("db_name","db_weiyi");
        $sql       = trim($this->get_in_str_val("sql"));
        $page_info = $this->get_in_page_info();
        $this->t_admin_group->db_query("use $db_name");
        $ret_info = null;
        $len      = strlen($sql);
        if ( $sql && $sql[$len-1]==";" ) {
            $sql=trim($sql);
            $sql=substr($sql,0,$len-1);
        }

        $format_sql= $sql;
        $col_name_list=[];
        if ($sql) {

            if (preg_match("/^[ \t]*select/i",$sql) )   {
                if (
                    preg_match("/\blimit[ \t]+[0-9]+/i", $sql )
                    || preg_match("/\bgroup[ \t]+by[ \t]+[0-9]+/i", $sql )
                )  {
                    $ret_info=$this->t_admin_group->main_get_list_as_page($sql);
                }else{
                    $ret_info=$this->t_admin_group->main_get_list_by_page($sql,$page_info);
                }
            }else{
                if (preg_match("/^[ \t]*(desc|explain)[ \t]+(.*)/i", $sql,  $matches ) ) {
                    $format_sql= $matches[2];
                }
                $ret_info=$this->t_admin_group->main_get_list_as_page($sql);
            }
        }
        if (@$ret_info["list"][0] ) {
            foreach ( $ret_info["list"][0] as $key=>$v ) {
                $col_name_list[]= $key;
            }
        }

        //curl 'http://tool.lu/sql/ajax.html' -H 'Host: tool.lu' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:54.0) Gecko/20100101 Firefox/54.0' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'Accept-Language: en-US,en;q=0.5' --compressed -H 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8' -H 'X-Requested-With: XMLHttpRequest' -H 'Referer: http://tool.lu/sql/' -H 'Cookie: uuid=3394583a-afcb-4061-926a-a9e6a20370de; Hm_lvt_0fba23df1ee7ec49af558fb29456f532=1502269817,1502354206,1502504099,1502777709; Hm_lpvt_0fba23df1ee7ec49af558fb29456f532=1502849476; slim_session=%7B%22slim.flash%22%3A%5B%5D%7D' -H 'Connection: keep-alive' --data 'code=DELETE+FROM+table1%0D%0AWHERE+NOT+EXISTS+(%0D%0A%09%09SELECT+*%0D%0A%09%09FROM+table2%0D%0A%09%09WHERE+table1.field1+%3D+table2.field1%0D%0A%09%09)%3B&operate=beauty'


        /*
        // http://www.atool.org/include/SqlFormatter.php/include/SqlFormatter.php
        $url= "http://www.atool.org/include/SqlFormatter.php";//?o=1&c=". base64_encode($format_sql);
        $arr=\App\Helper\Net::rpc($url,["o"=>1, "c"=>base64_encode($format_sql)  ] );
        $new_format_sql= @$arr["c"];
        //$arr= @json_decode(file_get_contents( "http://tool.lu/sql/ajax.html?code=". urlencode($format_sql)  ),true);
        //$new_format_sql= @$arr["text"];
        */
        $file_name = "/tmp/sql.".rand().rand() ;
        file_put_contents($file_name, $format_sql );
        $cmd = app_path("../tools/fsqlf" ). " $file_name";
        \App\Helper\Utils::logger("cmd:$cmd ");

        $format_sql = \App\Helper\Utils::exec_cmd($cmd);
        unlink($file_name);

        return $this->pageView(__METHOD__, $ret_info, [
            "col_name_list" => $col_name_list,
            "format_sql"    => $format_sql
        ]);
    }

    public function table_list() {
        $config_arr=[
            "db_weiyi"       => "t_student_info",
            "db_tool"        => "t_school_info",
            "db_weiyi_admin" => "t_admin_users",
            "db_account"     => "t_user_info",
        ];
        $db_name = $this->get_in_str_val("db_name","db_weiyi");

        //得到table 注释
        $table = $this->get_table($db_name);

        $sql = $table->gen_sql("select TABLE_NAME as table_name,TABLE_COMMENT as table_comment "
                               ." from information_schema.TABLES "
                               ." where TABLE_SCHEMA='%s' "
                               ." and TABLE_NAME <> 't_opt_table_log' "
                               ,$db_name
        );
        $table_list = $table->main_get_list($sql);
        foreach($table_list as &$t_item){
            $table_comment=@hex2bin($t_item["table_comment"]);
            if ($table_comment) {
                $t_item["table_comment"]=$table_comment;
            }
        }
        return $this->pageView(__METHOD__,  \App\Helper\Utils::list_to_page_info($table_list));
    }

    public function get_nick_sql(){
        $sql=$this->get_in_str_val("sql");

        $format_sql= $sql;
        $col_name_list=[];
        if ($sql) {
            if (preg_match("/^[ \t]*select/i",$sql) )   {
            }else{
                if (preg_match("/^[ \t]*(desc|explain)[ \t]+(.*)/i", $sql,  $matches ) ) {
                    $format_sql= $matches[2];
                }
            }
        }
        $file_name="/tmp/sql.".rand().rand() ;
        file_put_contents($file_name, $format_sql );
        $cmd= app_path("../tools/fsqlf" ). " $file_name";
        \App\Helper\Utils::logger("cmd:$cmd ");

        $new_format_sql=\App\Helper\Utils::exec_cmd($cmd);
        unlink($file_name);


        return $this->output_succ(["format_sql"=>$new_format_sql ]);
    }

    public function check_query() {
        $db_name=$this->get_in_str_val("db_name","db_weiyi");
        $sql = trim($this->get_in_str_val("sql"));
        $this->t_admin_group->switch_tongji_database();
        $this->t_admin_group->db_query("use $db_name");
        try {
            if (preg_match("/^[ \t]*select/i",$sql) )   {
                $this->t_admin_group->db_query("explain ". $sql);
            }else{
                if ( preg_match("/^[ \t]*update/i",$sql)
                       || preg_match("/^[ \t]*delete/i",$sql) ) {
                    return $this->output_err("不支持 update, delete");
                }else{
                    $this->t_admin_group->db_query( $sql);
                }
            }
        }catch( \Exception $e ) {
            return $this->output_err(  $e->getMessage() );
        }
        return $this->output_succ();
    }
}