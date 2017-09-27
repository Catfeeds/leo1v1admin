/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-grab_trial_lesson_list.ts" />
$(function(){

    var visitid = 0;
    var add_or_update_visit = function( grabid, visitid, requireid, succ_flag,fail_reason){
        $.ajax({
            type    : "post",
            url     : "/teacher_info/grab_visit_info",
            dataType: "json",
            data    : {
                'grabid'       : grabid,
                'visitid'      : visitid,
                'requireid'    : requireid,
                'success_flag' : succ_flag,
                'fail_reason'  : fail_reason,
            },
            success : function (ret){
                visitid   = ret.visitid;

                $(".opt-grab_trial_lesson").on("click",function(){
                    var data=$(this).get_opt_data();
                    BootstrapDialog.show({
	                      title: "接受确认",
	                      message : "是否确认接受此课程?<br/><b><font color='red'>点击 确认按钮 代表老师您已经接课成功并同意理优的奖惩制度！<br/>若对课程有任何疑问可直接联系对应排课老师！</font></b>",
	                      buttons: [{
		                        label: "返回",
		                        action: function(dialog) {
			                          dialog.close();
		                        }
	                      }, {
		                        label: "确认",
		                        cssClass: "btn-warning",
		                        action: function(dialog) {
                                dialog.close();
                                $.do_ajax("/teacher_info/course_set_new",{
                                    "require_id"   : data.require_id,
                                    "lesson_start" : data.stu_request_test_lesson_time,
                                    "grade"        : data.grade,
                                },function(result){
                                    var requireid = data.require_id;
                                    if(result.ret==0){
                                        add_or_update_visit( grabid, visitid, requireid,1,'');
                                        BootstrapDialog.alert("抢课成功！请在课程列表中查看该课程！");
                                        setTimeout(function(){
                                            window.location.reload();
                                        },2000);

                                    }else{
                                        add_or_update_visit( grabid, visitid, requireid,0, result.info);
                                        BootstrapDialog.alert(result.info);
                                    }
                                })

		                        }
	                      }]
                    });
                });

           }
        });
    }
    add_or_update_visit(grabid, visitid, 0,0,'');

});
