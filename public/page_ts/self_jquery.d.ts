interface JQueryStatic {
    self_upload_process(id,url,ctminfo,ext_file_list,ex_args,complete_func ):void;
    custom_upload_file_process(btn_id,is_public_bucket,complete_func,ctminfo,ext_file_list,bucket_info,noti_origin_file_func):void;
    enum_multi_select($element,enum_name,onChange,id_list?,select_group_list?):void;
    enum_multi_select_new ( $element, enum_name, onChange , id_list?, select_group_list? ):void ;
    intval_range_select ( $element,  onChange  ):void ;
    reload():void;
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
    flow_dlg_show(title,add_func,flow_type,from_key_int,from_key2_int?,from_key_str?):void;
    /*
      $.show_key_value_table("新增申请", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
      }
      });
    */
    show_key_value_table (title:string,arr:Array<Array<any>> ,btn_config?:Object,onshownfunc?: ()=>void, close_flag?:boolean ,width? ):void;
    /*
      $.admin_enum_select( {
      "join_header"  : $header_query_info,
      "enum_type" : null,
      "field_name" : "contract_type",
      "option_map" : {
      1: "xx",
      2:"kkk 2 ",
      3:"nnn3  ",
      },
      "title" : "合同类型",
      "select_value" :g_args.contract_type,
      }) ;

      $.admin_enum_select( {
      "join_header"  : $header_query_info,
      "enum_type" : "subject",
      "title" : "科目",
      "select_value" :g_args.subject,
      "id_list" :[1,2,3,4,5,6],
      }) ;
    */
    admin_enum_select (options):any;
    admin_query_input ( options):any;
    admin_query_origin ( options):any;
    admin_query_admin_group ( options):any;
    admin_date_select ( options):any;
    admin_ajax_select_user ( options):any;


    /*
            "join_header" : null,
            "field_name"  :null,
            "title"  :  "",
            "length_css" : "col-xs-6 col-md-2",
            "as_header_query" : false ,

            "select_value" :null,
            "can_select_all_flag"     : true,


            "opt_type" :  "select", // or "list"
            "url"          : "/user_deal/get_xmpp_server_list_js",
            select_primary_field   : "server_name",
            select_display         : "server_name",
            select_no_select_value : "",
            //select_no_select_title : "[全部]",
            select_no_select_title : "xmpp服务器",
            "th_input_id"  : null,

            //其他参数
            "args_ex" : {
            },
            //字段列表
            'field_list' :[
                {
                title:"ip",
                render:function(val,item) {return item.ip;}
            },{
                title:"权重",
                render:function(val,item) {return item.weights ;}
            },{
                title:"名称",
                render:function(val,item) {return item.server_name;}
            },{

                title:"说明",
                render:function(val,item) {return item.server_desc;}
            }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : function(v) {
                $("id_xmpp_server_name").val(v);
                load_data();
            },
            //加载数据后，其它的设置
            "onLoadData"       : null,
    */
    admin_ajax_select_dlg_ajax ( options):any;
    admin_query_common(options):any;


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
    get_table_key(fix):string;
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

    table_head_static(height? ):any;

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

    admin_select_dlg_ajax_more(conf:Object):void;

    admin_header_query (config ): any;

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
        "can_select_all_flag"     : true
    });
    */
    admin_select_user_new(obj:Object) ;

    //设置table  thead 不动
    tbody_scroll_table(height?):void;


}
