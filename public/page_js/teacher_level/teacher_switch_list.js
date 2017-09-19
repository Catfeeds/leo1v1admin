/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-teacher_switch_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page({
			      teacher_money_type : $('#id_teacher_money_type').val(),
			      // teacherid          : $('#id_teacherid').val(),
			      batch              : $('#id_batch').val(),
			      status             : $('#id_status').val(),
			      not_start: $('#id_not_start').val(),
			      not_end: $('#id_not_end').val(),
        });
    }

    Enum_map.append_option_list("teacher_money_type",$("#id_teacher_money_type"),false,[0,1,2,3]);
    Enum_map.append_option_list("switch_status",$("#id_status"));
    Enum_map.append_option_list( "month", $("#id_not_start"));
    Enum_map.append_option_list( "month", $("#id_not_end"));
	  $('#id_teacher_money_type').val(g_args.teacher_money_type);
	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_batch').val(g_args.batch);
	  $('#id_status').val(g_args.status);
	  $('#id_not_start').val(g_args.not_start);
	  $('#id_not_end').val(g_args.not_end);

	  $('.opt-change').set_input_change_event(load_data);

    $(".opt-switch_upload").on("click",function(){
        var data = $(this).get_opt_data();

        BootstrapDialog.show({
	          title   : "切换申请",
	          message : "是否提交切换申请?",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/teacher_level/switch_upload",{
                        "id"     : data.id,
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

    $(".opt-first_check").on("click",function(){
        var data      = $(this).get_opt_data();
        check_switch_info(data,1);
    });

    $(".opt-finally_check").on("click",function(){
        var data      = $(this).get_opt_data();
        check_switch_info(data,2);
    });

    var check_switch_info = function(data,type){
        var id_status = $("<select>");
        var arr = [
            ["是否通过",id_status]
        ];
        Enum_map.append_option_list("boolean",id_status,true);

        $.show_key_value_table("审核",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_level/check_switch_info",{
                    "id"     : data.id,
                    "status" : id_status.val(),
                    "type"   : type
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });
    }


});
