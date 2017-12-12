<?php
namespace App\Http\Controllers;
trait  ViewDeal {
    static $page_self_view_data=[];

    public  $view_ctrl;
    public  $view_action;

    function  getPageData( $page_data,$ex_js_args=null,$showPages=10 ){
        if (is_array($ex_js_args)){
            foreach($ex_js_args as $key =>$value ){
             $this->last_in_values[$key ]=array( "is_page_arg" => false ,
                    "value" => $value  );
            }
        }
        return $this->getTplPageInfoAndJsValue($this->last_in_values,$page_data["page_info"],$page_data["list"],$showPages );
    }

    public function store_gargs_d_ts_file($table_data_list)
    {
        $str="";
        $reload_filed_str="";
        $set_filed_str="";

        $enum_select_html_str="";

        $check_data_range=false;
        if (isset( $this->last_in_values["date_type_config"] ) ) { //id_date_type
            //$str.=  "\tdate_type_config:\tstring;\n";
            $set_filed_str.=
                "\t$('#id_date_range').select_date_range({\n".
                "\t\t'date_type' : g_args.date_type,\n".
                "\t\t'opt_date_type' : g_args.opt_date_type,\n".
                "\t\t'start_time'    : g_args.start_time,\n".
                "\t\t'end_time'      : g_args.end_time,\n".
                "\t\tdate_type_config : JSON.parse( g_args.date_type_config),\n".
                "\t\tonQuery :function() {\n".
                "\t\t\tload_data();\n".
                "\t\t});\n";
            $check_data_range=true;
        }
        foreach( $this->last_in_types as $key => $value)  {
            $is_enum_flag=false;
            $is_enum_list_flag=false;
            $muti_enum_class="";
            if (is_array ($value) ) {
                $muti_enum_class = $value["enum_class"] ;
                $value= $value["type"];
            }
            if ($value == "number" || $value=="string" ) {
                $str.=  "\t$key:\t$value;\n";
            }else if (  $value== "enum_list" ){
                $str.=  "\t$key:\tstring;//枚举列表: $muti_enum_class\n ";
                $is_enum_list_flag=true;
            }else{//枚举
                $str.=  "\t$key:\tnumber;//枚举: $value\n";
                $is_enum_flag=true;
            }
            if ($is_enum_flag) {
                $enum_type_str=preg_replace("/.*E/", "", $value);
                $name=$value::$name;
                $enum_select_html_str.=
                                         "\n".'        <div class="col-xs-6 col-md-2">'."\n".
                                         '            <div class="input-group ">'. "\n".
                                         '                <span class="input-group-addon">'.$name.'</span>'."\n".
                                         '                <select class="opt-change form-control" id="id_'.$key.'" >'."\n".
                                         '                </select>'."\n".
                                         '            </div>'."\n".
                                         '        </div>'."\n";


            }else{
                if ( !in_array( $key ,["page_num","page_count"]) ) {

                    $add_html_filed_flag=false;
                    if ($check_data_range) {
                        if ( !in_array ( $key, ["date_type_config","date_type", "opt_date_type","start_time","end_time"] ) )  {
                            $add_html_filed_flag=true;
                        }

                    }else{
                        $add_html_filed_flag=true;
                    }
                    if ($add_html_filed_flag) {
                        $enum_select_html_str.=
                            "\n".'        <div class="col-xs-6 col-md-2">'."\n".
                            '            <div class="input-group ">'. "\n".
                            '                <span class="input-group-addon">'.$key.'</span>'."\n".
                            '                <input class="opt-change form-control" id="id_'.$key.'" />'."\n".
                            '            </div>'."\n".
                            '        </div>'."\n";
                    }
                }


            }

            $enum_select_html_str.= "{!!\App\Helper\Utils::th_order_gen([[\"$key title\", \"$key\", \"th_$key\" ]])!!}\n";


            if ( !in_array( $key ,["page_num","page_count"]) ) {
                $reload_filed_str.=  "\t\t$key:\t\$('#id_$key').val(),\n";
                $add_set_filed_flag=false;
                if ($check_data_range) {
                    if ( !in_array ( $key, ["date_type_config","date_type", "opt_date_type","start_time","end_time"] ) )  {
                        $add_set_filed_flag=true;
                    }

                }else{
                    $add_set_filed_flag=true;
                }
                if ($add_set_filed_flag) {
                    if ($is_enum_list_flag) {
                        $enum_type_str=preg_replace("/.*E/", "", $muti_enum_class);
                        $set_filed_str.= "\t\$('#id_$key').admin_set_select_field({\n"
                                      .'		"enum_type"    : "'.$enum_type_str.'",' . "\n"
                                      .'		"field_name" : "'.$key .'",'  . "\n"
                                      .'		"select_value" : g_args.'.$key .','  . "\n"
                                      .'		"multi_select_flag"     : true,'  . "\n"
                                      .'		"onChange"     : load_data,'  . "\n"
                                      .'		"th_input_id"  : "th_'.$key .'",'  . "\n"
                                      .'		"only_show_in_th_input"     : false,' . "\n"
                                      .'		"btn_id_config"     : {},' . "\n"
                                      ."	});"  . "\n";
                    }else if ( $is_enum_flag ) {
                        $set_filed_str.= "\t\$('#id_$key').admin_set_select_field({\n"
                            .'		"enum_type"    : "'.$enum_type_str.'",' . "\n"
                            .'		"field_name" : "'.$key .'",'  . "\n"
                            .'		"select_value" : g_args.'.$key .','  . "\n"
                            .'		"onChange"     : load_data,'  . "\n"
                            .'		"multi_select_flag"     : false ,'  . "\n"
                            .'		"th_input_id"  : "th_'.$key .'",'  . "\n"
                            .'		"only_show_in_th_input"     : false,' . "\n"
                            .'		"btn_id_config"     : {},' . "\n"
                            ."	});"  . "\n";
                    }else{
                        $user_type_config=[
                            "userid"=> "student",
                            "studentid"=> "student",
                            "adminid"=> "account",
                            "assistantid"=> "assistant",
                            "teacherid"=> "teacher",
                        ];

                        if (  @$user_type_config[$key] ) {
                            $user_type=$user_type_config[$key];
                            $set_filed_str.= "\t\$('#id_$key').admin_select_user_new({\n"
                                          .'		"user_type"    : "'.$user_type .'",' . "\n"
                                          .'		"select_value" : g_args.'.$key .','  . "\n"
                                          .'		"onChange"     : load_data,'  . "\n"
                                          .'		"th_input_id"  : "th_'.$key .'",'  . "\n"
                                          .'		"only_show_in_th_input"     : false,' . "\n"
                                          .'		"can_select_all_flag"     : true' . "\n"
                                          ."	});"  . "\n";


                        }else{
                            $set_filed_str.=  "\t\$('#id_$key').val(g_args.$key);\n";
                        }
                    }
                }
            }
        }


        $reload_filed_str=substr($reload_filed_str,0,-2)."\n";


        $row_file_name =app_path("../public/page_ts/g_args.d.ts/.row-{$this->view_ctrl}-{$this->view_action}.tmp");
        $row_str="";
        if ( count($table_data_list) >0) {
            $row_item = @$table_data_list[0];
            if (!$row_item) {
                foreach ($table_data_list as $k_item ) {
                    $row_item =$k_item;
                    break;
                }
            }

            if ($row_item) {

                foreach ($row_item as  $r_k=>$r_v )    {
                    if ( !is_int($r_k)) {
                        $row_str.="\t$r_k\t:any;\n";
                    }
                }
            }

            $row_old_data=@file_get_contents( $row_file_name);
            if( $row_old_data != $row_str ) {
                file_put_contents( $row_file_name, $row_str);
                chmod(  $row_file_name,0777);
            }

        }else{
            $row_str=@file_get_contents($row_file_name);
        }



        $data= "interface GargsStatic {\n".
             $str.
             "}\n".
             "declare module \"g_args\" {\n".
             "    export = g_args;\n".
             "}\n".
             "declare var g_args: GargsStatic;\n".

             "declare var g_account: string;\n".
             "declare var g_account_role: any;\n".
             "declare var g_adminid: any;\n".

             "interface RowData {\n".
             $row_str.
             "}\n\n".

             "/*\n".
             "\ntofile: \n\t mkdir -p ../{$this->view_ctrl}; vi  ../{$this->view_ctrl}/{$this->view_action}.ts\n\n".
             "/// <reference path=\"../common.d.ts\" />\n".
             "/// <reference path=\"../g_args.d.ts/{$this->view_ctrl}-{$this->view_action}.d.ts\" />\n".
             "\n".
             "function load_data(){\n".
             "\tif ( window[\"g_load_data_flag\"]) {return;}\n".
             "\t\t$.reload_self_page ( {\n".
             "\t\torder_by_str : g_args.order_by_str,\n".
             $reload_filed_str.
             "\t\t});\n".
             "}\n".

             "$(function(){\n".
             "\n".
             "\n".
             $set_filed_str.
             "\n".
             "\n".
             "\t\$('.opt-change').set_input_change_event(load_data);\n".
             "});\n\n\n\n".

             "*/\n".
             "/* HTML ...\n".
             $enum_select_html_str.
             "*/\n";


        $file_name =app_path("../public/page_ts/g_args.d.ts/{$this->view_ctrl}-{$this->view_action}.d.ts");

        $old_data=@file_get_contents($file_name);
        if( $old_data !=$data || true ) {
            @unlink( $file_name );
            file_put_contents($file_name,$data);
            @chmod(  $file_name,0777);
        }
    }


    public function get_js_g_args($g_args){
        $js_values_str= "<script type=\"text/javascript\" >\n";

        $js_values_str.="\tg_args=".json_encode($g_args ).";\n";
        foreach( $g_args  as $key => $value)  {
            $js_values_str.=  "\tg_$key=" . json_encode($value).";\n";
        }

        $js_values_str.= "</script>\n";
        return $js_values_str;
    }

    /*
        $args = array(
            "grade"         => $grade,
            "subject"   => $subject,
            "question_type" => $question_type,
            "note"          => $note,
            "difficulty"    => $difficulty,
            "opt_type"      => $opt_type,
            'upload_domain_url' => array( "is_page_arg" => false ,
                                          "value" => $this->g_config['qiniu_pub'] )
        );
    */
    function  getTplPageInfoAndJsValue( $args ,$page_info , $table_data_list=null , $showPages=10 ){
        global $g_request;

        $controller = "";
        $method     = "";

        $path_arr   = explode( "/", $g_request->path());
        $controller = $path_arr[0] ;
        if (isset($path_arr[1] )) {
            $method =$path_arr[1] ;
        }

        $page_args = array();
        $g_args    = array();
        foreach ($args as  $k => $item) {
            if (is_array($item)){
                if ( $item["is_page_arg"] ){
                    $page_args[$k]=$item["value"];
                }
                $g_args[$k]=$item["value"];
            }else{
                $page_args[$k]=$item;
                $g_args[$k]=$item;
            }
        }

        $js_values_str = $this->get_js_g_args($g_args);
        if (\App\Helper\Utils::check_env_is_local() ){
            //生成 g_args 的 .d.ts
            $this->store_gargs_d_ts_file($table_data_list);
        }

        $url = $this->get_page_url( $controller,$method,$args );
        $a_page_info = $this->getPageInfo( $url,$page_info["total_num"],
                                           $page_info["per_page_count"],
                                           $page_info["page_num"],
                                           $showPages);
        return [
            "js_values_str"   => $js_values_str,
            'page_info'       => $a_page_info,
            'table_data_list' => $table_data_list,
        ];
    }

    public function _page($page_num,$page_count,$current_page,$send_var,$item_num = 10)
    {
        $page = '';
        $next_url = '';
        $next_10_url = '';
        $previous_url = '';
        $previous_10_url = '';

        if($page_num == '') return false;

        $start = $current_page - 4;
        $end   = $current_page + 5;

        if ($start <= 1)
        {
            $offset = 1 - $start;
            $start  = 1;
            $end   += $offset;

            if ($end > $page_num)
            {
                $end = $page_num + 1;
            }
            else
            {
                $end += 1;
            }
        }
        else
        {
            if ($end > $page_num)
            {
                $offset = $page_num - $end;
                $start += $offset;
                if ( $start< 1)
                {
                    $start = 1;
                }
                $end    = $page_num + 1;
            }
            else
            {
                $end += 1;
            }
        }

        $send_var_with_page_count= str_replace("{PageCount}", $page_count,$send_var);

        $pages=array();
        for($i= $start;$i<$end;$i++)
        {
            $link = str_replace("{Page}", $i,$send_var_with_page_count);

            if($current_page == $i)
            {
                $page.= "<b>".$i."</b>&nbsp;";
            }
            else
            {
                $page.= "<a href='".$link."'>[".$i."]</a>&nbsp;";
            }

            //    if ($i!= $page_num) {
                $pages[] = array('page_num'=>$i,'page_link'=>$link);
            // }
        }

        //上一页/下一页url
        if ($current_page < $page_num)
        {
            $link1= str_replace("{Page}", $current_page+1 ,$send_var_with_page_count);
            $page = $page . "&nbsp;<b><a href='".$link1."' >下一页</a></b>";

            $next_url = $link1;
        }

        if($current_page > 1) {
            if(($current_page-1) == 0)
                $link1 = str_replace("{Page}", 0,$send_var);
            else
                $link1= str_replace("{Page}" , $current_page-1 ,$send_var_with_page_count);
            $page= "<b><a href='".$link1."' >上一页</a></b>&nbsp;&nbsp;".$page;

            $previous_url = $link1;
        }

        // 获取首页/末页url
        $first_page_url = str_replace("{Page}" , 1 ,$send_var_with_page_count);
        $last_page_url  = str_replace("{Page}" , $page_num ,$send_var_with_page_count);

        $prev_page2=array();
        $next_page2=array();
        //上两页url
        if($current_page > 2) {
            $link2= str_replace("{Page}" , $current_page-2 ,$send_var_with_page_count);
            $prev_page2 = array('page_num'=>$current_page-2,'page_url'=>$link2);
        }

        //下两页url
        if($current_page+2 < $page_num) {
            $link2= str_replace("{Page}" , $current_page+2 ,$send_var_with_page_count);
            $next_page2 = array('page_num'=>$current_page+2,'page_url'=>$link2);
        }

        $return = array('page' => $page , 'pages' => $pages ,'prev_page2'=>$prev_page2,'next_page2'=>$next_page2, 'next_url' => $next_url , 'previous_url' => $previous_url, "input_page_num_url" =>  $send_var  );

        $return['first_page_url'] = $first_page_url;
        $return['last_page_url']  = $last_page_url;

        return $return;
    }


    function getPageInfo($url,$total,$page_size,$page,$showPages=10)
    {

        $page_info['result_num']   = $total;
        $page_info['page_num']     = ceil( \App\Helper\Common::div_safe( $total,$page_size));
        $page_info['current_page'] = $page;
        $page_info['page_count'] = $page_size;
        $page_info['pre_page']     = ($page - 1) == 0 ? $page : ($page - 1);
        $page_info['next_page']    = ($page + 1) > $page_info['page_num'] ? $page : ($page + 1);

        $page_info['page']         = $this->_page($page_info['page_num'],$page_size ,$page,$url,$showPages);

        // 范围：当前是从第X项到第Y项
        $offset             = ($page-1)*$page_size;
        $upper              = min($offset + $page_size - 1, $total-1);
        $page_info['range'] = array($offset+1, $upper+1);

        return $page_info;
    }

    function get_page_url($controller,$method,$args){
        $args_str="?";
        foreach( $args as $k =>$v ){
            if ( $k != "page_num") {
                if (!is_array($v)) {
                    $args_str.="$k=". urlencode($v)."&";
                }
            }
        };
        $args_str.="page_num={Page}&page_count={PageCount}";
        return $this->get_url($controller,$method,$args_str);
    }

    function get_url($controller=NULL,$method=NULL,$other)
    {
        if ($controller)
        {
            $controller = trim(strip_tags(strtolower($controller)));
        }

        if ($method)
        {
            $method     = trim(strip_tags(strtolower($method)));
        }

        if(!$controller){
            return '/'.$other;
        }

        $url='/'.$controller;

        if ( $method != 'index' && $method ){
            $url.='/'.$method;
        }

        if(is_array($other)){
            foreach($other as $key => $v){
                $url.='/'.$v;
            }
        }else{
            $url.='/'.$other;
        }
        return $url;
    }

    static function view_with_header_info( $view , $data=[], $mergeData=[] ) {
        $data['cur_http'] = 'http://'.$_SERVER['HTTP_HOST'];
        $data["_power_list"]= session("power_list");
        $data["_account"]=  session("acc") ;
        $data["_face_pic"]=  session("face_pic") ;
        $data["_adminid"]=  session("adminid") ;
        $data["_account_role"] =  session("account_role") ;

        $ctrl=@$mergeData["_ctr"] ;

        if ($ctrl=="stu_manage" || $view=="common.stu_errors" ) {
            $data["_stu_menu_html"] = session("stu_menu_html") ;
        }else if (  $ctrl == "teacher_info_admin"  ) {
            $data["_tea_menu_html"] = session("tea_menu_html") ;
        }else if (  $ctrl == "teacher_info"  ) { //老师后台
            $data["_nick"] =  session("nick") ;
            $data["_face"] =  session("face") ;
        }else if ($ctrl == 'agent_info'){
            $data["_nickname"] = session('nickname');
            $data["_headimgurl"] = session('headimgurl');
        }else{
            $data["_menu_html"] = session("menu_html") ;
        }

        $data= array_merge( $data, static::$page_self_view_data );
        return view( $view,$data,$mergeData )->render();
    }

    function view($method ,$data= []){
        if (preg_match("/([a-zA-Z0-9_]+)::([a-zA-Z0-9_]+)/",$method, $matches)  )  {
            $ctr=$matches[1];
            $action=strtolower($matches[2]);
            return static::view_with_header_info("$ctr.$action", $data ,[
                "_ctr"=> $ctr ,
                "_publish_version"=> \App\Config\publish_version::$version ,
                "_act"=> $action,
                "_origin_act" => @$_REQUEST["_act"],
            ]);
        }
    }

    function error_view($errors) {
        $data=["errors"=>$errors];
        return static::view_with_header_info ("common.errors", $data ,[
            "_ctr"=> "common",
            "_publish_version"=> \App\Config\publish_version::$version ,
            "_act"=> "errors",
        ] );
    }
    function stu_error_view($errors) {
        $data=["errors"=>$errors];
        return static::view_with_header_info ("common.stu_errors", $data ,[
            "_ctr"=> "common",
            "_publish_version"=> \App\Config\publish_version::$version ,
            "_act"=> "errors",
        ] );
    }

    function teacher_error_view($errors) {
        $data=["errors"=>$errors];
        return static::view_with_header_info ("common.teacher_errors", $data ,[
            "_ctr"=> "common",
            "_publish_version"=> \App\Config\publish_version::$version ,
            "_act"=> "errors",
        ] );
    }


    function out_xls($ret_info) {

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("jim ")
                             ->setLastModifiedBy("jim")
                             ->setTitle("jim title")
                             ->setSubject("jim subject")
                             ->setDescription("jim Desc")
                             ->setKeywords("jim key")
                             ->setCategory("jim  category");

        $objPHPExcel->setActiveSheetIndex(0);

        $col_list=[
            "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T", "U","V","W","X","Y","Z",
            "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
            "BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
            "CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ",
            "DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ",
        ];

        if (count($ret_info["list"]) >0 ) {

            //index
            $k_id=0;
            $k_map=[];

            foreach( $ret_info["list"][0] as $k=> $v ) {
                if ( !is_int($k) ) {
                    $k_map[$k]=$k_id;
                    $pos_str=$col_list[$k_id] . "1";
                    $objPHPExcel->getActiveSheet()->setCellValue( $pos_str,$k );
                    $k_id++;
                }
            }

            // dd($k_map);
            foreach( $ret_info["list"] as $index=> $item ) {
                foreach ( $item as $key => $cell_data ) {
                    if(!is_int($key)) {
                        $index_str = $index+2;
                        // dd($k_map);
                        $pos_str   = $col_list[$k_map[$key]].$index_str;
                        // echo $pos_str." ~ ".$cell_data."<br>";
                        $objPHPExcel->getActiveSheet()->setCellValue( $pos_str, $cell_data);
                    }
                }
            }
        }

        $date=\App\Helper\Utils::unixtime2date (time(NULL));
        header('Content-type: application/vnd.ms-excel');
        header( "Content-Disposition:attachment;filename=\"all_$date.xlsx\"");

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save('php://output');

        exit;
    }


    function pageView( $method ,$ret_info=null,$data_ex=array(),$ex_js_args=null,$showPages=10  ){
        if (\App\Helper\Utils::check_env_is_local() ){
            if (preg_match("/([a-zA-Z0-9_]+)::([a-zA-Z0-9_]+)/",$method, $matches)  )  {
                $this->view_ctrl=$matches[1];
                $this->view_action=strtolower($matches[2]);
            }
        }

        if (!$ret_info) {
            $ret_info=\App\Helper\Utils::list_to_page_info([]);
        }

        if (isset($this->last_in_values["page_num"]) && $this->last_in_values["page_num"]==0xFFFFFFFF+2) {
            $this->out_xls($ret_info);
            exit;
        }
        $data=$this->getPageData($ret_info,$ex_js_args,$showPages);
        if (count($data_ex)>0 )  {
            $data=array_merge($data,$data_ex) ;
        }

        return $this->view($method,$data);
    }


    function get_page_info_for_js($in_page_info, $args=[], $showPages=10)
    {
        $controller= "";
        $method= "";
        global $g_request;

        $path_arr=explode( "/", $g_request->path());
        $controller=$path_arr[0] ;
        if (isset($path_arr[1] )) {
            $method =$path_arr[1] ;
        }

        $args=array_merge($args,$this->last_in_values );

        $url=$this->get_page_url( $controller,$method,$args );

        $total     = $in_page_info["total_num"];
        $page_size = $in_page_info["per_page_count"];
        $page      = $in_page_info["page_num"];

        $page_count= $page_size;

        $page_info['result_num']   = $total;
        $page_info['page_num']     = ceil($total/$page_size);
        $page_info['current_page'] = $page;
        $page_info['pre_page']     = ($page - 1) == 0 ? $page : ($page - 1);
        $page_info['next_page']    = ($page + 1) > $page_info['page_num'] ? $page : ($page + 1);
        $page_info['page']         = $this->_page($page_info['page_num'],$page_count,$page,$url,$showPages);

        // 范围：当前是从第X项到第Y项
        $offset             = ($page-1)*$page_size;
        $upper              = min($offset + $page_size - 1, $total-1);
        $page_info['range'] = array($offset+1, $upper+1);

        return $page_info;
    }
}