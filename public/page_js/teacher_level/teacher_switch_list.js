/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-teacher_switch_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page({
			      teacher_money_type : $('#id_teacher_money_type').val(),
			      // teacherid          : $('#id_teacherid').val(),
			      batch              : $('#id_batch').val(),
			      status             : $('#id_status').val()
        });
    }

    Enum_map.append_option_list("teacher_money_type",$("#id_teacher_money_type"),false,[0,1,2,3]);
    Enum_map.append_option_list("switch_status",$("#id_status"));
	  $('#id_teacher_money_type').val(g_args.teacher_money_type);
	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_batch').val(g_args.batch);
	  $('#id_status').val(g_args.status);

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
                        "status" : 1,
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



});
