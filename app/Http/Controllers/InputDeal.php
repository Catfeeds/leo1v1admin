<?php
namespace App\Http\Controllers;

use \App\Enums as E;

use Illuminate\Support\Facades\Redis;
trait  InputDeal {
    public function set_in_value($key,$value ) {
        global $g_request;
        $g_request->offsetSet($key,$value);
    }

    public function set_filed_for_js($field_name,$v,$type_str = "number"){
        $this->set_in_value($field_name,$v);
        global $g_request;
        /** @var $g_request Illuminate\Http\Request */

        $this->last_in_values[$field_name]=$v;
        $this->last_in_types[$field_name]= $type_str ;
    }


    public function check_in_not_has_and_set( $field_name ,$value) {
        if (!$this->check_in_has($field_name)) {
            $this->set_in_value($field_name,$value);
        }
    }

    public function check_in_has( $field_name  ) {
        global $g_request;
        /** @var $g_request Illuminate\Http\Request */
        return $g_request->has($field_name) && $g_request->$field_name !== null  ;
    }

    public function get_in_boolean_val(  $field_name ,$def_value=0  ){
        return $this->get_in_int_val($field_name,$def_value,E\Eboolean::class );
    }

    public function get_in_enum_val( $enum_class, $def_value=0, $field_name="" ){
        if (!$field_name) {
            $field_name = $enum_class::$field_name;
        }
        return $this->get_in_int_val($field_name, $def_value, $enum_class );

    }

    public function get_in_enum_list( $enum_class, $def_value="-1", $field_name="" ){
        if (!$field_name) {
            $field_name=$enum_class::$field_name;
        }
        $str = $this->get_in_str_val($field_name, $def_value );
        $this->last_in_types[$field_name]=array( "type" => "enum_list" , "enum_class" => $enum_class );
        $ret_arr = preg_split("/,/",$str);
        $id_list = [];
        foreach($ret_arr as $item ) {
            if (trim($item) !="" ) {
                $id_list[]=intval($item);
            }
        }
        return $id_list;
    }

    public function get_in_int_list(  $field_name, $def_value="-1"  ){
        $str = $this->get_in_str_val($field_name, $def_value );
        $ret_arr = preg_split("/,/",$str);
        $id_list = [];
        foreach($ret_arr as $item ) {
            if (trim($item) !="" ) {
                $id_list[]=intval($item);
            }
        }
        return $id_list;
    }


    public function get_in_int_val( $field_name, $def_value=0, $enum_class="" ){
        global $g_request;
        /** @var $g_request Illuminate\Http\Request */
        if ($g_request) {
            if($g_request->has($field_name) && $g_request->$field_name !== null ){
                $v = (int) $g_request->$field_name;
            }else{
                $v = $def_value;
            }

            $this->last_in_values[$field_name] = $v;
            if ($enum_class) {
                $this->last_in_types[$field_name]=$enum_class;
            }else{
                $this->last_in_types[$field_name]="number";
            }
        }else{
            $v = $def_value;
        }
        return $v;
    }

    public function get_in_float_val( $field_name, $def_value=0 ){
        global $g_request;
        /** @var $g_request Illuminate\Http\Request */

        if ( $g_request->has($field_name) && $g_request->$field_name !== null    ){
            $v = (float)$g_request->$field_name;
        }else{
            $v = $def_value;
        }
        $this->last_in_values[$field_name]=$v;
        $this->last_in_types[$field_name]="number";
        return $v;
    }

    /**
     * @return string
     */
    public function get_in_str_val( $field_name, $def_value="" ){
        global $g_request;
        /** @var $g_request Illuminate\Http\Request */

        if ($g_request){
            if ( $g_request->has($field_name) && $g_request->$field_name !== null  ){
                if(!( $g_request->$field_name == "")) {
                    $v= $g_request->$field_name;
                }else{
                    $v=$def_value;
                }
            }else{
                $v=$def_value;
            }
            $this->last_in_values[$field_name]=$v;
            $this->last_in_types[$field_name]="string";
        }else{
            $v=$def_value;
        }
        return $v;
    }

    public function get_in_page_num_ex(){
        $page_num = $this->get_in_int_val('page_num',-1);
        if ($page_num < 1) {
            $page_num = 1;
        }
        return $page_num;
    }

    public function get_in_page_num(){
        return $this->get_in_page_info();
    }
    public function get_in_page_info() {
        return [
            "page_num"   => $this->get_in_page_num_ex(),
            "page_count" => $this->get_in_page_count(),
        ];
    }

    public function get_in_page_count(){
        $page_count= $this->get_in_int_val('page_count',10);
        if (!($page_count> 1)) {
            $page_count= 10;
            $this->set_in_value("page_count",$page_count);
        }
        return $page_count;
    }

    public function get_in_grade( $def_value=-1){
        return  $this->get_in_int_val('grade',$def_value,E\Egrade::class );
    }
    public function get_in_account_role( $def_value=-1){
        return  $this->get_in_int_val('account_role',$def_value,E\Eaccount_role::class );
    }

    public function get_in_has_pad( $def_value=-1){
        return  $this->get_in_int_val('has_pad',$def_value,E\Epad_type::class );
    }

    public function get_in_client_ip() {
        //return Illuminate\Http\Request::ip();
        if ( \App\Helper\Utils::check_env_is_testing() ) {
            return "192.168.0.5";
        }
        return $_SERVER["REMOTE_ADDR"];
    }


    public function get_in_id( $def_value=0){
        return  $this->get_in_int_val('id', $def_value);
    }

    public function get_in_courseid( $def_value=0){
        return  $this->get_in_int_val('courseid', $def_value);
    }

    public function get_in_packageid( $def_value=0){
        return  $this->get_in_int_val('packageid', $def_value);
    }

    public function get_in_lessonid( $def_value=0){
        return  $this->get_in_int_val('lessonid', $def_value);
    }
    public function get_in_phone( $def_value=""){
        return  $this->get_in_str_val('phone', $def_value);
    }
   public function get_in_wx_openid( $def_value=""){
        return  $this->get_in_str_val('wx_openid', $def_value);
    }
    // 15601830297-1 ->  15601830297
    public function get_in_phone_ex() {
        $str=$this->get_in_phone();
        $arr=explode("-", $str);
        $phone=$arr[0];
        return $phone;
    }

    public function get_in_type( $def_value=""){
        return  $this->get_in_int_val('type', $def_value);
    }

    public function get_in_opt_type( $def_value=""){
        return  $this->get_in_int_val('opt_type', $def_value);
    }



    public function get_in_parentid( $def_value=0){
        return  $this->get_in_int_val('parentid', $def_value);
    }
    public function get_in_studentid( $def_value=0){
        return  $this->get_in_int_val('studentid', $def_value);
    }
    public function get_in_userid( $def_value=0){
        return  $this->get_in_int_val('userid', $def_value);
    }
    public function get_in_query_text( $def_value="" ) {
        return $this->get_in_str_val("query_text", $def_value);
    }

    public function get_in_adminid( $def_value=0){
        return  $this->get_in_int_val('adminid', $def_value);
    }

    public function get_in_teacherid( $def_value=0){
        return  $this->get_in_int_val('teacherid', $def_value);
    }

    public function get_in_cc_id( $def_value=0){
        return  $this->get_in_int_val('cc_id', $def_value);
    }
    public function get_in_question_type( $def_value=0){
        return  $this->get_in_int_val('question_type', $def_value);
    }

    public function get_in_question_content( $def_value=""){
        return  $this->get_in_str_val('question_content', $def_value);
    }
    public function get_in_teacher_flag( $def_value=0){
        return  $this->get_in_int_val('teacher_flag', $def_value);
    }
    public function get_in_teacher_time( $def_value=0){
        return  $this->get_in_int_val('teacher_time', $def_value);
    }
    public function get_in_cc_flag( $def_value=0){
        return  $this->get_in_int_val('cc_flag', $def_value);
    }
    public function get_in_cc_time( $def_value=0){
        return  $this->get_in_int_val('cc_time', $def_value);
    }


    public function get_in_assistantid( $def_value=0){
        return  $this->get_in_int_val('assistantid', $def_value);
    }



    public function get_in_lesson_account_id( $def_value=0){
        return  $this->get_in_int_val('lesson_account_id', $def_value);
    }





    public function get_in_subject( $def_value=-1){
        return  $this->get_in_int_val('subject',$def_value,E\Esubject::class);
    }



    public function get_in_hour( $def_value=-1){ //04-27
        return  $this->get_in_int_val('hour',$def_value,E\Ehour::class);
    }




    public function get_in_unixtime_from_str($field_name,$def_value=""  ) {
        $date= $this->get_in_str_val($field_name, $def_value);
        return strtotime($date);
    }

    public function get_in_lesson_start_from_str($def_value=""  ) {
        return $this->get_in_unixtime_from_str( "lesson_start", $def_value);
    }

    public function get_in_lesson_end_from_str($def_value=""  ){
        return $this->get_in_unixtime_from_str( "lesson_end", $def_value);
    }


    public function get_in_start_time_from_str($def_value=""  ,$field_name="start_time") {
        return $this->get_in_unixtime_from_str( $field_name, $def_value);
    }

    public function get_in_end_time_from_str($def_value="",$field_name="end_time"  ){
        return $this->get_in_unixtime_from_str($field_name, $def_value);
    }

    public function get_in_end_time_from_str_next_day($def_value=""  ){
        return $this->get_in_unixtime_from_str( "end_time", $def_value)+86400;
    }


    public function get_in_sid( $def_value=0){
        return  $this->get_in_int_val('sid', $def_value);
    }
    public  function set_in_date_range($date_type,$opt_date_type,$start_time,$end_time) {
        $this->set_in_value("date_type",$date_type);
        $this->set_in_value("opt_date_type",$opt_date_type);
        $this->set_in_value("start_time",$start_time);
        $this->set_in_value("end_time",$end_time);
    }

    public function __get( $name ) {
        if (substr($name ,0,2  ) == "t_" || $name=="users") {
            $reflectionObj = new \ReflectionClass( "App\\Models\\$name");
            return $this->$name= $reflectionObj->newInstanceArgs();
        }else if ($name == "account" ){
            return $this->get_account();
        }else{
            throw new \Exception() ;
        }
    }
    public function __call($method,$arg )  {
        if ( preg_match("/^get_in_e_(.*)$/",$method,$ret_arr)) {
            $def_value=0;
            $field_name="";
            if (isset($arg[0] )) {
                $def_value=$arg[0];
            }
            if (isset($arg[1] )) {
                $field_name=$arg[1];
            }
            $class_name= "\\App\\Enums\\E{$ret_arr[1]}";
            return $this->get_in_enum_val($class_name ,$def_value ,$field_name);
        }else if ( preg_match("/^get_in_el_(.*)$/",$method,$ret_arr)) {
            $def_value=-1;
            $field_name="";
            if (isset($arg[0] )) {
                $def_value=$arg[0];
            }
            if (isset($arg[1] )) {
                $field_name=$arg[1];
            }
            $class_name= "\\App\\Enums\\E{$ret_arr[1]}";
            return $this->get_in_enum_list ( $class_name, $def_value ,$field_name);

        }

        throw new \Exception("$method  no find ");
    }

    /**
     * 通过ret的结果,返回信息
     * @param boolean ret
     * @param string  error_info 错误信息
     */
    public function output_ret($ret,$error_info="操作失败,请重试!"){
        if($ret){
            return $this->output_succ();
        }else{
            return $this->output_err($error_info);
        }
    }

    public function output_succ($arr=null) {
        return outputjson_success($arr );
    }

    public function output_err( $errno, $array=null) {
        return outputjson_error($errno,$array);
    }

    public function output_ajax_table($ret_list, $arr=[] ) {
        $ret_list["page_info"] = $this->get_page_info_for_js($ret_list["page_info"]);
        return $this->output_succ( array_merge(["data" => $ret_list], $arr) );
    }

    public function output_api_ret_info($ret_info =null) {
        foreach ($ret_info["list"] as &$item) {
            foreach ( $item as $k=>$v ) {
                if (is_int($k))  {
                    unset($item[$k]);
                }
            }
        }
        unset($ret_info["total_num"]);
        unset($ret_info["per_page_count"]);
        return outputjson_success($ret_info);
    }

    public function get_in_order_by_str( $no_order_in_db_field_list=[],$def="", $key_map=[] ) {
        $str=$this->get_in_str_val("order_by_str",$def);
        \App\Helper\Utils::logger("shaixun_J: $str ");

        if ($str) {
            $arr=preg_split("/ /",$str);
            $field_name=$arr[0];
            $order_flag=$arr[1];
            if (!($order_flag == "asc"|| $order_flag == "desc")) {
                dd("order str :err :$str");
            }
            if(!preg_match('/^[a-z_0-9]*$/',$field_name) ) {
                dd("xxx :err :$str, [$field_name]");
            }
            if (in_array( $field_name , $no_order_in_db_field_list) ) {
                return array(false, "", $field_name, $order_flag=="asc" );
            }else{
                if(isset ($key_map[$field_name ] ) ) {
                    $str= $key_map[$field_name ]." $order_flag";
                }

                return array(true,"order by  $str", $field_name, $order_flag=="asc" );
            }

        }else{
            return array(true,"", "", true );
        }
    }
    //@desn:获取范围
    //@param:$is_money 0:按原始数据 1数值乘以100
    public function get_in_intval_range($field_name ,$def_value ="" ,$is_money=0)
    {
        $str=trim($this->get_in_str_val($field_name,$def_value));
        if ($str==="") {
            return ["start" => null, "end" => null ];
        }
        $arr   = preg_split("/-/",$str );
        $start = intval ($arr[0]);
        if (isset($arr[1] )) {
            $end= intval ($arr[1]);
        }else{
            $end=$start;
        }
        //如果范围为金钱格式[数值乘以100]
        if($is_money == 1){
            $start *= 100;
            $end *= 100;
        }
        return ["start"=>$start,"end" => $end ];
    }

    public function get_in_date_range_day($init_start_date, $date_type=0 , $date_type_config=[] )
    {
        return $this->get_in_date_range($init_start_date,0, $date_type, $date_type_config, 1 );
    }
    public function get_in_date_range_week($init_start_date, $date_type=0 , $date_type_config=[] )
    {
        return $this->get_in_date_range($init_start_date,0, $date_type, $date_type_config, 2 );
    }
    public function get_in_date_range_month($init_start_date, $date_type=0 , $date_type_config=[]  )
    {
        return $this->get_in_date_range($init_start_date,0, $date_type, $date_type_config, 3 );
    }

    /**
     * @desc:组合时间筛选条件
     * @param:$init_start_date 开始时间[init]
     * @param:$init_end_date  结束时间[int]
     * @param:$date_type  默认显示筛选根据
     * @param:$date_type_config  日期筛选根据数组
     * @param:$opt_date_type  日期显示区间 [1:日2:周3:月]
     * @param:$date_field_index 扩展配置
     * @param:$timepacler_flag 时间段处理标识  [不处理$endtime]
     */
    public function get_in_date_range(
        $init_start_date,$init_end_date,$date_type=0,$date_type_config=[],$opt_date_type=0,$date_field_index=0,$timepacker_flag=false
    ){
        $now=time(NULL);
        if (is_int($init_start_date)) {
            $init_start_date=date("Y-m-d", $now+$init_start_date*86400);
        }
        if (is_int($init_end_date)) {
            $init_end_date=date("Y-m-d", $now+$init_end_date*86400);
        }
        if ($opt_date_type ==E\Eopt_date_type::V_1 ) {//日
            $init_end_date=$init_start_date;
        } else if ($opt_date_type ==E\Eopt_date_type::V_2 ) {//周
            $week_info       = \App\Helper\Utils::get_week_range(strtotime($init_start_date) ,1 );
            $init_start_date = date("Y-m-d",$week_info["sdate"] );
            $init_end_date   = date("Y-m-d",$week_info["edate"]  );
        }else if ($opt_date_type ==E\Eopt_date_type::V_3 ) {//月
            $init_start_date = date("Y-m-01",  strtotime($init_start_date));
            $init_end_date   = date("Y-m-d",  strtotime(date("Y-m-01",  (strtotime($init_start_date)+86400*32)     ))-86400 );
        }

        if ($date_field_index==0) {
            $date_type_config_str="date_type_config";
            $date_type_str="date_type";
            $opt_date_type_str="opt_date_type";
            $start_time_str="start_time";
            $end_time_str="end_time";
        }else{
            $date_type_config_str="date_type_config_$date_field_index";
            $date_type_str="date_type_$date_field_index";
            $opt_date_type_str="opt_date_type_$date_field_index";
            $start_time_str="start_time_$date_field_index";
            $end_time_str="end_time_$date_field_index";
        }

        //选择日期
        $this->last_in_values["$date_type_config_str"]=json_encode($date_type_config);
        $this->last_in_types["$date_type_config_str"]="string";

        $date_type     = $this->get_in_int_val($date_type_str,$date_type);
        $opt_date_type = $this->get_in_int_val($opt_date_type_str,$opt_date_type);


        $start_time  = $this->get_in_start_time_from_str($init_start_date, $start_time_str);
        $end_time    = $this->get_in_end_time_from_str($init_end_date,$end_time_str);
        if ( !($timepacker_flag  &&   $opt_date_type ==0) ) { //按时间段,不处理 $end_time
            $end_time   += 86400;
        }

        if ( $end_time - $start_time >30*86400  ) {
            $this->switch_tongji_database();
        }

        if (count($date_type_config)>0) {
            if (isset( $date_type_config[$date_type] )) {
                return [$start_time,$end_time,$date_type_config[$date_type][0]];
            }else{
                echo "error : no find date_type=$date_type" ;
                exit;
            }
        }else{
            return [$start_time,$end_time];
        }
    }

    public function output_bool_ret($ret) {
        if ($ret) {
            return outputjson_success();
        }else {
            return outputjson_error("操作失败");
        }
    }


}