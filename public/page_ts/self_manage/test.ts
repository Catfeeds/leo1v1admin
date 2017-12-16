/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-test.d.ts" />




$(function(){
    var $header_query_info= $("#id_header_query_info").admin_header_query ({
    });
    $.admin_query_input ({
        "join_header"  : $header_query_info,
        "field_name"  : "query_text",
        "title" : "学生姓名",
        "placeholder" : "学生姓名",
        "select_value" : g_args.query_text,
        as_header_query :true,

    });

    $.admin_date_select ({
        "join_header"  : $header_query_info,
        "title" : "时间",
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        as_header_query :true,
    });


    $.admin_enum_select( {
        "join_header"  : $header_query_info,
        "enum_type" : "grade",
        "field_name"  : "grade",
        "title" : "年级",
        "select_value" : g_args.grade,
    });

    $.admin_enum_select( {
        "join_header"  : $header_query_info,
        "enum_type" : "subject",
        "title" : "科目",
        "select_value" :g_args.subject,
        "id_list" :[1,2,3,4,5,6],
    }) ;

    $.admin_enum_select( {
        "join_header"  : $header_query_info,
        "enum_type" : "contract_type",
        "title" : "合同类型",
        "select_value" :g_args.contract_type,
    }) ;

    $.admin_ajax_select_user ({
        "join_header"  : $header_query_info,
        "field_name"  :"userid",
        "title"  :  "学生",
        "length_css" : "col-xs-6 col-md-2",
        "as_header_query" : false ,

        "user_type"    : "student",
        "select_value" : g_args.userid,
        "th_input_id"  : null,
        "can_select_all_flag"   : true
    });

})
