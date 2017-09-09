/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-contract_list_seller_payed_new.d.ts" />

function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
        origin_ex : $("#id_origin_ex").val(),
        date_type:    $('#id_date_type').val(),
        opt_date_type:    $('#id_opt_date_type').val(),
        start_time:    $('#id_start_time').val(),
        end_time:    $('#id_end_time').val(),
        grade:    $('#id_grade').val(),
        groupid:    $('#id_groupid').val()
    });
}

$(function(){

    $(".common-table").tbody_scroll_table();

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $('#id_groupid').val(g_args.groupid);
    $('#id_origin_ex').val(g_args.origin_ex);


    $("#id_grade").val(g_args.grade);
    $.enum_multi_select ( $("#id_grade"),"grade", function( ){
        load_data();
    });

    $('.opt-change').set_input_change_event(load_data);


    var get_row_date_query_str=function( a_link )  {
        var opt_data=$(a_link).parent().parent().find(".row-data").get_self_opt_data();
        var start_time = g_args.start_time.substr(0,4)+"-"+opt_data.account;
        var end_time  = start_time ;
        var opt_date_type =1;
        if ( !opt_data.adminid || opt_data.adminid == -1 ) {
            alert("不可查看汇总!");
            return "";
        }
        return "&admin_revisiterid="+opt_data.adminid+
            "&require_adminid="+opt_data.adminid+
            "&start_time="+g_args.start_time+
            "&end_time="+g_args.end_time +
            "&sub_assign_adminid_2=-1"+
            "&opt_date_type="+ g_args.opt_date_type
        ;
    };
    var jump_url_1="/seller_student_new/assign_sub_adminid_list";
    if (g_args.group_adminid>0) {
        jump_url_1="/seller_student_new/assign_member_list";
    }
    //assign_member_list

    $(".td-all_count").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen(jump_url_1+"?accept_flag=1&date_type=5&"+date_str );
        }
    });
    $(".td-all_count_0").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen(jump_url_1+"?seller_resource_type=0&accept_flag=1&date_type=5&"+date_str );
        }
    });

    $(".td-all_count_1").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen(jump_url_1+"?seller_resource_type=1&date_type=5&"+date_str );
        }
    });
    $(".td-no_call").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen(jump_url_1+"?seller_resource_type=-1&date_type=5&tq_called_flag=0&"+date_str );
        }
    });

    $(".td-global_tq_no_call").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen(jump_url_1+"?seller_resource_type=-1&date_type=5&global_tq_called_flag=0&"+date_str );
        }
    });



    $(".td-no_call_0").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen(jump_url_1+"?seller_resource_type=0&date_type=5&tq_called_flag=0&"+date_str );
        }
    });

    $(".td-no_call_1").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen(jump_url_1+"?seller_resource_type=1&accept_flag=1&date_type=5&tq_called_flag=0&"+date_str );
        }
    });
    $(".td-require_test_count").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen("/seller_student_new2/test_lesson_plan_list?require_admin_type=2&accept_flag=1&date_type=1&"+date_str );
        }
    });


    $(".td-order_count").on("click",function(){
        var opt_data=$(this).parent().parent().find(".row-data").get_self_opt_data();
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.do_ajax("/user_deal/get_account_by_adminid" ,{
                "adminid"  : opt_data.adminid
            },function(resp ){
                $.wopen("/user_manage/contract_list?contract_status=-2&"+date_str+"&sys_operator="+  resp.account );
            });
        }
    });


    $(".td-test_lesson_count").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen("/seller_student_new2/test_lesson_plan_list?require_admin_type=2&accept_flag=1&date_type=4&"+date_str );
        }
    });
    $(".td-succ_test_lesson_count").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen("/seller_student_new2/test_lesson_plan_list?require_admin_type=2&accept_flag=1&date_type=4&success_flag=-2&"+date_str );
        }
    });


    $(".td-fail_test_lesson_count").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen("/seller_student_new2/test_lesson_plan_list?require_admin_type=2&accept_flag=1&date_type=4&success_flag=2&"+date_str );
        }
    });






    $(".td-fail_need_pay_count").on("click",function(){
        var date_str=get_row_date_query_str(this);
        if (date_str) {
            $.wopen("/seller_student_new2/test_lesson_plan_list?require_admin_type=2&accept_flag=1&date_type=4&test_lesson_fail_flag=-2&"+date_str );
        }
    });

    $(".common-table" ).table_admin_level_4_init();



});
