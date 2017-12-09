interface JQueryStatic {

    self_upload_process(id,url,ctminfo,ext_file_list,ex_args,complete_func ):void;

    custom_upload_file_process (btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list, bucket_info  ,noti_origin_file_func   ):void;

    enum_multi_select ( $element, enum_name, onChange , id_list?, select_group_list? ):void ;
    enum_multi_select_new ( $element, enum_name, onChange , id_list?, select_group_list? ):void ;
    intval_range_select ( $element,  onChange  ):void ;

    reload( ):void;
    isWeiXin():boolean;
    do_ajax( url:string,data: Object ,succ_call_back?: (result :any )=>void, jsonp_flag? ):void;
    do_ajax_t( url:string,data: Object ,succ_call_back?: (result :any )=>void   ):void;
    wopen( url:string,open_self_window?:boolean):void;
    reload_self_page( args:Object, url? ):void;
    filed_init_date_range(date_type,  opt_date_type, start_time,  end_time):void;
    filed_init_date_range_query_str(date_type,  opt_date_type, start_time,  end_time):string;
    get_page_select_date_str(has_date_type?):string;
    plupload_Uploader(config:any):any;
    get_action_str():string;

    flow_dlg_show(title, add_func , flow_type, from_key_int,  from_key2_int?, from_key_str? ):void;

    /*
        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
            }
        });
            */


    show_key_value_table (title:string,arr:Array<Array<any>> ,btn_config?:Object,onshownfunc?: ()=>void, close_flag?:boolean ,width? ):void;

    //<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    /*
    $.admin_select_user(
        $('#id_admin_revisiterid'),
        "admin", load_data ,false, {
            "main_type": 2, //分配用户
            select_btn_config: [{
                "label": "[未分配]",
                "value": 0
            }]
        }
    );
    */
    admin_select_user ( $element:JQuery, user_type:string, call_func?:(id:number)=>void, is_not_query_flag?:boolean, args_ex?: any, th_input_id?:string, select_all_flag?:boolean ):void ;

    dlg_get_html_by_class(item_class:string):string;
    obj_copy_node(item_class:string):JQuery;

    dlg_need_html_by_id( id ):JQuery;


    /*

                    var $input=$("<input style=\"width:180px\"  placeholder=\"驳回理由\"/>");
                    $.show_input(
                        opt_data.nick+":"+ opt_data.subject_str+ ",要驳回, 不计算排课数?! ",
                        "",function(val){
                            $.do_ajax("/ss_deal/set_no_accpect",{
                                'require_id'       : opt_data.require_id,
                                'fail_reason'       :val
                            });
                        }, $input  );
    */
    show_input( title:string,  value:any, ok_func: (v:any)=>void ,$input?:JQuery  ):void;
    md5(str):string;

    base64:any;

    check_in_phone():boolean;

/*
 var time1 = new Date().Format("yyyy-MM-dd");
 var time2 = new Date().Format("yyyy-MM-dd hh:mm:ss");
 */
    DateFormat (unixtime:number, fmt:string) :string;
    strtotime( str) :number;

    do_ajax_get_nick(user_type:string,userid:number,call_func:( id:number,nick:string)=>void):void;

    fiter_obj_field( obj:Object,field_name_list:Array<string> ): any ;
    custom_show_pdf(file_url ,get_abs_url?):void ;



    /*
      <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>
    */

    custom_upload_file (btn_id:string,  is_public_bucket :boolean, complete_func , ctminfo , ext_file_list, noti_process? ):void;
    dlg_set_width(width):void;

    //<script type="text/javascript" src="/page_js/lib/flow.js"></script>
    flow_show_define_list( flowid );
    flow_show_node_list(flowid);
    flow_show_all_info(flowid);
    check_power( powerid: number  ):boolean;
}

interface RowData {

}

interface JQuery {
    get_opt_data(text: string): any;

    table_group_level_4_init(show_flag?):void;
    table_admin_level_4_init(show_flag?):void;
    get_opt_data(): RowData;
    get_self_opt_data(): RowData;
    get_row_opt_data(): RowData;
    set_input_change_event ( func:()=>void   ) :void;

    init_seller_groupid_ex(val? );
    key_value_table_show(show_flag?):void ;

    set_input_readonly ( readonly_flag ):void;


    //<script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
    admin_select_dlg_edit(conf:Object);

    //<script type="text/javascript" src="/page_js/lib/select_date_time_range.js?v={{@$_publish_version}}"></script>
    admin_select_date_time_range(conf:Object);
  datetimepicker(Object):JQuery;

    admin_select_course(Object):JQuery;
    fullCalendar(conf:Object):void;
    fullCalendar(str:string):void;
    fullCalendar(str:string,v:any):void;


    //<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js?v={{@$_publish_version}}"></script>
    admin_select_dlg_ajax(conf:Object):void;

    /*
	  $('#id_contract_type').admin_set_select_field({
		    "enum_type"    : "contract_type",
		    "select_value" : g_args.contract_type,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_contract_type",
        "only_show_in_th_input" :true,
		    "btn_id_config"     : {}
	  });
    */
    admin_set_select_field (conf:Object ):void;

    //<script type="text/javascript" src="/page_js/lib/select_dlg.js?v={{@$_publish_version}}"></script>
    admin_select_dlg(conf:Object):void;
    select_date_range(conf:Object):void;

    iCheck(str:any):JQuery;

    iCheckValue():any;

    html(obj:JQuery):JQuery;
    admin_set_lesson_time(obj:Object);
    admin_select_user(obj:Object) ;
    /*
	  $('#id_studentid').admin_select_user_new({
		    "user_type"    : "student",
		    "select_value" : g_args.studentid,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_studentid",
		    "can_sellect_all_flag"     : true
	  });
    */
    admin_select_user_new(obj:Object) ;

    //设置table  thead 不动
    tbody_scroll_table(height?):void;


}
