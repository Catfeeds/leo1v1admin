/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-teacher_money_type_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page({
			      teacher_money_type : $('#id_teacher_money_type').val(),
			      level              : $('#id_level').val()
        });
    }
    Enum_map.append_option_list("teacher_money_type",$("#id_teacher_money_type"),true);

    var level_map = "level";
    if(g_args.teacher_money_type==6){
        level_map = "new_level";
    }
    Enum_map.append_option_list(level_map,$("#id_level"),true);

	  $('#id_teacher_money_type').val(g_args.teacher_money_type);
	  $('#id_level').val(g_args.level);

	  $('.opt-change').set_input_change_event(load_data);

    $("#id_add_teacher_money_type").on("click",function(){
	      var teacher_money_type = $("#id_teacher_money_type").val();
	      var level              = $("#id_level").val();
        var id_money_101          = $("<input>");
        var id_money_106          = $("<input>");
        var id_money_203          = $("<input>");
        var id_money_301          = $("<input>");
        var id_money_303          = $("<input>");
        var money_101          = $(".grade_101").data("money");
        var money_106          = $(".grade_106").data("money");
        var money_203          = $(".grade_203").data("money");
        var money_301          = $(".grade_301").data("money");
        var money_303          = $(".grade_303").data("money");

        if(teacher_money_type!=7){
            BootstrapDialog.alert("此工资类型不允许修改！");
            return false;
        }

        var arr = [
            ["小一～小五",id_money_101],
            ["小六～初二",id_money_106],
            ["初三",id_money_203],
            ["高一～高二",id_money_301],
            ["高三",id_money_303],
        ];

        id_money_101.val(money_101);
        id_money_106.val(money_106);
        id_money_203.val(money_203);
        id_money_301.val(money_301);
        id_money_303.val(money_303);

        $.show_key_value_table("修改工资",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/update_teacher_money_type",{
                    "teacher_money_type" : teacher_money_type,
                    "level"              : level,
                    "money_101"          : id_money_101.val(),
                    "money_106"          : id_money_106.val(),
                    "money_203"          : id_money_203.val(),
                    "money_301"          : id_money_301.val(),
                    "money_303"          : id_money_303.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });

    });



});

