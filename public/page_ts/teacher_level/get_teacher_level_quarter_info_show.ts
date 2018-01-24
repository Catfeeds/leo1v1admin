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

    $("#id_select_all").on("click", function() {
        $(".opt-select-item").iCheck("check");
    });

    $("#id_withhold_agree").on("click",function(){
        BootstrapDialog.alert("开发中!!!");
        return;
        if(g_account !="jack" && g_account!= "jim" && g_account != "ted"){
            BootstrapDialog.alert("没有权限!!!");
        }

        alert(111); 
    });
    $("#id_advance_agree").on("click",function(){
        BootstrapDialog.alert("开发中!!!");
        return;
        if(g_account !="jack" && g_account!= "jim" && g_account != "ted"){
            BootstrapDialog.alert("没有权限!!!");
        }

        alert(111);  
    });


    $("#id_select_other").on("click", function() {
        $(".opt-select-item").each(function() {
            var $item = $(this);
            if ($item.iCheckValue()) {
                $item.iCheck("uncheck");
            } else {
                $item.iCheck("check");
            }
        });
    });


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

    $(".opt-advance-require-deal").on("click",function(){
        if(g_account !="jack" && g_account!= "jim" && g_account != "ted"){
            BootstrapDialog.alert("没有权限!!!");
            return;
        }

        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var teacher_money_type = opt_data.teacher_money_type;

        var id_accept_flag = $("<select/>");

        Enum_map.append_option_list("accept_flag", id_accept_flag, true,[1,2] );
        var arr=[
            ["总得分", opt_data.total_score],
            ["申请晋升",opt_data.level_after_str],
            ["审批结果",id_accept_flag]
        ];
        $.show_key_value_table("晋升审批", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/teacher_level/set_teacher_advance_require_master_2018', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.start_time,
                    'accept_flag':id_accept_flag.val(),
                    'old_level':opt_data.level,
                    'level_after':opt_data.level_after,

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

    $(".opt-advance-withhold-deal").on("click",function(){
        if(g_account !="jack" && g_account!= "jim" && g_account != "ted"){
            BootstrapDialog.alert("没有权限!!!");
            return;
        }

        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var teacher_money_type = opt_data.teacher_money_type;

        var id_withhold_final_trial_flag = $("<select/>");
        

        Enum_map.append_option_list("accept_flag", id_withhold_final_trial_flag, true,[1,2] );
        var arr=[
            ["当前等级", opt_data.level_str],
            ["总得分", opt_data.total_score],
            ["扣款申请", opt_data.withhold_money+"元/月"],
            ["审批结果",id_withhold_final_trial_flag]
        ];
        $.show_key_value_table("扣款审批", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/teacher_level/set_teacher_withhold_require_master_2018', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.start_time,
                    'withhold_final_trial_flag':id_withhold_final_trial_flag.val(),                    
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

    $(".opt-edit-test").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var id_lesson_count           = $("<input/>");
        var id_cc_order_num           = $("<input/>");
        var id_cr_order_num           = $("<input/>");
        var id_stu_num                = $("<input/>");
        var id_record_avg_score       = $("<input/>");
        var id_level = $("<select/>");

        Enum_map.append_option_list_v2s("new_level", id_level, true );

        var arr=[
            ["课耗平均", id_lesson_count],
            ["签单数(CC)", id_cc_order_num],
            ["签单数(CR)", id_cr_order_num],
            ["常规学生数", id_stu_num],
            ["反馈平均分数", id_record_avg_score],
            ["老师等级",id_level]
        ];
        id_lesson_count.val(opt_data.lesson_count);
        id_cc_order_num.val(opt_data.cc_order_num);
        id_cr_order_num.val(opt_data.other_order_num);
        id_stu_num.val(opt_data.stu_num);
        id_record_avg_score.val(opt_data.record_score_avg);
        id_level.val(opt_data.level);
        $.show_key_value_table("编辑", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/ajax_deal2/update_advance_data_for_test', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.start_time,
                    'lesson_count' :id_lesson_count.val()*100,
                    'cc_order_num' :id_cc_order_num.val(),
                    'cr_order_num' :id_cr_order_num.val(),
                    'stu_num' :id_stu_num.val(),
                    'record_avg_score' :id_record_avg_score.val(),
                    'level'    : id_level.val()
                });
            }
        });

    });

  $('.opt-change').set_input_change_event(load_data);
});
