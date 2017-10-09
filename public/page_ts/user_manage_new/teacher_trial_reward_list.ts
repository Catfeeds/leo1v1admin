/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-teacher_trial_reward_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config : $('#id_date_type_config').val(),
			date_type        : $('#id_date_type').val(),
			opt_date_type    : $('#id_opt_date_type').val(),
			start_time       : $('#id_start_time').val(),
			end_time         : $('#id_end_time').val(),
			teacherid        : $('#id_teacherid').val(),
			type             : $('#id_reward_type').val(),
			lessonid         : $('#id_lessonid').val(),
			has_lesson 		 : $('#id_has_lesson').val(),
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery          : function() {
            load_data();
        }
    });

    Enum_map.append_option_list("reward_type",$("#id_reward_type"));
    Enum_map.append_option_list("boolean",$("#id_has_lesson"));
	$('#id_reward_type').val(g_args.type);
	$('#id_has_lesson').val(g_args.has_lesson);
    $("#id_teacherid").val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"), "teacher",load_data);

    if (g_args.lessonid != -1 ) {
        $("#id_lessonid").val(g_args.lessonid );
    }
    
    $('.opt-change').set_input_change_event(load_data);
    $(".opt-edit").on("click",function(){
	      var data          = $(this).get_opt_data();
        var id_type       = $("<select/>");
        var id_money_info = $("<input/>");
        var id_money      = $("<input/>");
        var id_add_time   = $("<input/>");
        var id_teacherid  = $("<input/>");

        Enum_map.append_option_list("reward_type",id_type,true);
        id_type.val(data.type);
        id_money_info.val(data.money_info);
        id_money.val(data.money);
        id_add_time.val(data.add_time_str);

        var arr = [
            ["奖励老师",id_teacherid],
            ["奖励类型",id_type],
            ["奖励备注",id_money_info],
            ["奖励金额",id_money],
            ["添加时间",id_add_time],
        ];

        $.show_key_value_table("修改",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/update_teacher_money_list_info",{
                    "id"           : data.id,
                    "type"         : id_type.val(),
                    "money_info"   : id_money_info.val(),
                    "money"        : id_money.val()*100,
                    "add_time"     : id_add_time.val(),
                    "add_time_old" : data.add_time_str,
                    "teacherid" : id_teacherid.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        },function(){
	          id_add_time.datetimepicker({
		            lang       : 'ch',
		            timepicker : true,
		            format     : 'Y-m-d H:i',
	          });
            id_teacherid.val(data.teacherid);
            $.admin_select_user(id_teacherid,"teacher");
        });
    });

    $(".opt-delete").on("click",function(){
        var data = $(this).get_opt_data();
        BootstrapDialog.show({
	        title   : "确认删除？",
	        message : "是否删除此奖励项目?",
	        buttons : [{
		        label  : "返回",
		        action : function(dialog) {
			        dialog.close();
		        }
	        }, {
		          label    : "确认",
		          cssClass : "btn-warning",
		          action   : function(dialog) {
                  $.do_ajax("/user_manage_new/delete_teacher_reward",{
                      "id" : data.id
                  },function(result){
                      if(result.ret==0){
                          window.location.reload();
                      }else{
                          BootstrapDialog.alert(result.info);
                      }
                  })
		          }
	        }]
        });
    });

    $("#id_add_teacher_money").on("click",function(){
	    var id_teacherid  = $("<input/>");
	    var id_type       = $("<select/>");
	    var id_money      = $("<input/>");
	    var id_money_info = $("<input/>");
	    var id_add_time   = $("<input/>");

        var arr = [
            ["老师",id_teacherid],
            ["类型",id_type],
            ["金额",id_money],
            ["金额信息",id_money_info],
            ["添加时间",id_add_time],
        ];
        Enum_map.append_option_list("reward_type",id_type,true);

        $.show_key_value_table("添加奖励",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_money/add_teacher_reward",{
                    "type"       : id_type.val(),
                    "teacherid"  : id_teacherid.val(),
                    "money_info" : id_money_info.val(),
                    "money"      : id_money.val()*100,
                    "add_time"   : id_add_time.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        },function(){
	          id_add_time.datetimepicker({
		            lang       : 'ch',
		            timepicker : true,
		            format     : 'Y-m-d H:i',
	          });
            $.admin_select_user(id_teacherid,"teacher");
        });


    });

});
