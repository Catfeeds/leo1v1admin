/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_simulate-new_teacher_money_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      date_type_config   : $('#id_date_type_config').val(),
			      date_type          : $('#id_date_type').val(),
			      opt_date_type      : $('#id_opt_date_type').val(),
			      start_time         : $('#id_start_time').val(),
			      end_time           : $('#id_end_time').val(),
			      teacher_id         : $('#id_teacherid').val(),
			      teacher_money_type : $('#id_teacher_money_type').val(),
			      level              : $('#id_level').val(),
			      not_start: $('#id_not_start').val(),
			      not_end: $('#id_not_end').val(),
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list( "teacher_money_type", $("#id_teacher_money_type"));
    Enum_map.append_option_list( "level", $("#id_level"));
    Enum_map.append_option_list( "month", $("#id_not_start"));
    Enum_map.append_option_list( "month", $("#id_not_end"));
	  $('#id_teacherid').val(g_args.teacher_id);
	  $('#id_not_start').val(g_args.not_start);
	  $('#id_not_end').val(g_args.not_end);
	  $('#id_teacher_money_type').val(g_args.teacher_money_type);
	  $('#id_level').val(g_args.level);
    $.admin_select_user( $("#id_teacherid"),"teacher", load_data);


    $(".opt-set_simulate_info").on("click",function(){
	      var data = $(this).get_opt_data();
        var id_level_simulate = $("<select/>");
        var arr = [
            ["模拟等级",id_level_simulate]
        ];
        Enum_map.append_option_list("new_level",id_level_simulate,true);
        id_level_simulate.val(data.level_simulate);

        $.show_key_value_table("更改模拟信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_simulate/update_teacher_simulate_info",{
                    "level_simulate" : id_level_simulate.val(),
                    "teacherid"      : data.teacherid
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });

    });

    $("#id_reset_money_count").on("click",function(){
	      BootstrapDialog.show({
	          title   : "清除统计信息",
	          message : "是否清除统计信息?",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/teacher_simulate/del_redis_simulate_money",{
                    },function(result){
                        if(result.ret==0){
                            dialog.close();
                            BootstrapDialog.alert(result.info);
                        }
                    })

		            }
	          }]
        });
    });

    $("#id_reset_level_count").on("click",function(){
        BootstrapDialog.show({
	          title   : "重置等级信息",
	          message : "是否重置模拟等级信息？",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/teacher_simulate/get_level_simulate_list",{
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


	  $('.opt-change').set_input_change_event(load_data);
});
