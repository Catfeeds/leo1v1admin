/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-teacher_advance_info_list.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacher_money_type:	$('#id_teacher_money_type').val(),
			teacherid:	$('#id_teacherid').val(),
            accept_flag:	$('#id_accept_flag').val(),
            fulltime_flag:	$('#id_fulltime_flag').val()
        });
    }

    Enum_map.append_option_list("teacher_money_type", $("#id_teacher_money_type"),false,[1,4,5]);

	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_accept_flag').val(g_args.accept_flag);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
    $.admin_select_user($("#id_teacherid"), "teacher", load_data);

    $(".opt-accept").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        BootstrapDialog.confirm("确定同意？", function(val){
            if (val) {
                $.do_ajax( '/teacher_level/set_teacher_advance_require_master', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                    'level_after':opt_data.level_after,
                    'accept_flag':1
                });
            } 
        });

    });

    $(".opt-no-accept").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var accept_info = $("<textarea/>");
        var arr=[
            ["驳回理由",accept_info]  
        ];
        $.show_key_value_table("驳回", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax( '/teacher_level/set_teacher_advance_require_master', {
                    'teacherid' : teacherid,
                    'start_time' :g_args.quarter_start,
                    'level_after':opt_data.level_after,
                    'accept_flag':2,
                    "accept_info":accept_info.val()
                });
 
            }
        });

    });



    $(".show_refund_detail").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var start_time = g_args.quarter_start;
        
        $.do_ajax( "/teacher_level/get_teacher_refund_detail_info",{
            "teacherid" :teacherid,
            "start_time":start_time
        },function(resp){
            var title = "学生退费详情";
            var list = resp.data;
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间</td><td>学生</td><td>老师责任占比</td></tr></table></div>");
            $.each(list,function(i,item){
                html_node.find("table").append("<tr><td>"+item['apply_time_str']+"</td><td>"+item['nick']+"</td><td>"+item['per']+"%</td></tr>");

            });

            
            var dlg=BootstrapDialog.show({
                title:title, 
                message :  html_node   ,
                closable: true, 
                buttons:[{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }],
                onshown:function(){
                    
                }

            });

            dlg.getModalDialog().css("width","800px");
            
        });

    });

	$('.opt-change').set_input_change_event(load_data);
});
