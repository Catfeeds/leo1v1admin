/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info_admin-teacher_assess.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
    $('#opt-add-assess').on('click',function(){
        var teacherid = g_args.teacherid ;
        var id_content = $("<textarea >");
        var id_res = $("<select />");
        var id_advise_reason = $("<textarea />");
        Enum_map.append_option_list("assess_res",id_res,true,[1,0]);
        var arr = [
            [ "考核内容",  id_content] ,
            [ "考核结果",  id_res] ,
            [ "建议或原因",  id_advise_reason] ,
        ];
        
        $.show_key_value_table("新增考核评估信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/teacher_info_admin/add_teacher_assess", {
                    "teacherid":teacherid,
                    "content":id_content.val(),
                    "assess_res":id_res.val(),
                    "advise_reason":id_advise_reason.val()
                });
            }
        });

    });
    
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data() ;
        var teacherid = opt_data.teacherid;
        var assess_time = opt_data.assess_time;

        BootstrapDialog.show({
            title: '删除',
            message : "确认删除吗？" ,
            closable: false, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    dialog.close();

                    $.ajax({
                        url: '/teacher_info_admin/assess_del',
                        type: 'POST',
                        data: {
				            'teacherid': teacherid,
                            'assess_time':assess_time
			            },
                        dataType: 'json',
                        success: function(data) {
                            window.location.reload();
                        }
                    });
                    

                }
            }]
        }); 
 
    });

    $('.opt-edit').on('click',function(){
        var opt_data = $(this).get_opt_data() ;
        var teacherid = opt_data.teacherid;
        var assess_time = opt_data.assess_time;
        var id_content = $("<textarea >");
        var id_res = $("<select />");
        var id_advise_reason = $("<textarea />");
        Enum_map.append_option_list("assess_res",id_res,true,[1,0]);
        id_content.val(opt_data.content);
        id_res.val(opt_data.assess_res);
        id_advise_reason.val(opt_data.advise_reason);
        var arr = [
            [ "考核内容",  id_content] ,
            [ "考核结果",  id_res] ,
            [ "建议或原因",  id_advise_reason] ,
        ];
        
        $.show_key_value_table("新增考核评估信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/teacher_info_admin/update_teacher_assess", {
                    "teacherid":teacherid,
                    "assess_time":assess_time,
                    "content":id_content.val(),
                    "assess_res":id_res.val(),
                    "advise_reason":id_advise_reason.val()
                });
            }
        });

    });

	$('.opt-change').set_input_change_event(load_data);
});




