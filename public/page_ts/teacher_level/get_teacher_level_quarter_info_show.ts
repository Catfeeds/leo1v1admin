/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info_show.d.ts" />
function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
        advance_require_flag:	$('#id_advance_require_flag').val(),
		    withhold_require_flag:	$('#id_withhold_require_flag').val(),
        teacherid:	$('#id_teacherid').val()
    });
}

$(function(){


    $('#id_advance_require_flag').val(g_args.advance_require_flag);
	  $('#id_withhold_require_flag').val(g_args.withhold_require_flag);

    $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user($("#id_teacherid"), "teacher", load_data);

    $(".opt-advance-require").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var teacher_money_type = opt_data.teacher_money_type;

        var id_level_after = $("<select/>");

        Enum_map.append_option_list_v2s("new_level", id_level_after, true,[2,3,4,11] );
        var arr=[
            ["总得分", opt_data.total_score],
            ["目标等级",id_level_after]
        ];
        $.show_key_value_table("晋升申请", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/teacher_level/set_teacher_advance_require_2018', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.start_time,
                    'level_after':id_level_after.val(),
                });
            }
        });
    });

    $(".opt-advance-withhold-require").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var arr=[
            ["扣款额", opt_data.withhold_money+"元/月"],
        ];
        $.show_key_value_table("扣款申请", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/teacher_level/set_teacher_advance_withhold_require_2018', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.start_time
                });
            }
        });
    });


    $(".opt-update-level-after").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var teacher_money_type = opt_data.teacher_money_type;

        var id_level_after = $("<select/>");
        Enum_map.append_option_list_v2s("new_level", id_level_after, true );
        var arr=[
            ["目标等级",id_level_after]
        ];
        $.show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/teacher_level/update_level_after', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                    'level_after':id_level_after.val(),
                });
            }
        });


    });

    //晋升申请审批(单个老师)
    $(".opt-advance-require_deal").on("click",function(){
        if(g_account !="jack" && g_account!= "jim" && g_account!="江敏" && g_account != "ted"){
            BootstrapDialog.alert("没有权限!!!");
        } 
    });   


    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;

        var id_record_score_avg  = $("<input/>");
        var id_record_num  = $("<input/>");
        var arr=[
            ["反馈次数",id_record_num],
            ["反馈平均得分",id_record_score_avg]
        ];
        $.show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/teacher_level/update_level_record_info', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                    'record_score_avg':id_record_score_avg.val(),
                    'record_num':id_record_num.val(),
                });
            }
        });


    });



    $("#id_add_teacher").on("click",function(){
        var id_teacherid           = $("<input/>");

        var id_score            = $("<input/>");



        var arr = [
            ["老师", id_teacherid],
            ["总得分", id_score],
        ];

        $.show_key_value_table("新增晋升老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                $.do_ajax('/teacher_level/add_teacher_advance_info',{
                    "teacherid"              : id_teacherid.val(),
                    "total_score"                : id_score.val(),
                });
            }
        },function(){
            $.admin_select_user(id_teacherid,"teacher");
        });
    });


    $(".opt-add-hand").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var realname = opt_data.realname;
        var start_time = g_args.quarter_start;
        var teacherid = opt_data.teacherid;
        BootstrapDialog.confirm("确定刷新数据吗？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/update_teacher_advance_info_hand', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                    'realname':opt_data.realname
                });
            }
        });



    });
    $("#id_update_all_info").on("click",function(){
        BootstrapDialog.confirm("确定刷新数据吗？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/update_teacher_advance_info_all', {
                    "teacher_money_type": 6,
                    'start_time' :    g_args.start_time,
                });
            }
        });

    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var realname = opt_data.realname;
        var start_time = g_args.quarter_start;
        var teacherid = opt_data.teacherid;
        BootstrapDialog.confirm("确定删除数据吗？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/del_advance_info', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                });
            }
        });



    });

  $('.opt-change').set_input_change_event(load_data);
});
