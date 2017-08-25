/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_simulate-new_teacher_money_list.d.ts" />

$(function(){
    $("#id_reset_money_count").on("click",function(){
        BootstrapDialog.show({
	          title   : "重置数据",
	          message : "确定重置1-7月的数据么？",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/teacher_simulate/get_month_money_list",{
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    });
		            }
	          }]
        });
    });

	  $('.opt-change').set_input_change_event(load_data);
});
