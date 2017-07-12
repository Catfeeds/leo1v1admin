/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_assess.d.ts" />

$(function(){
   


    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val()
        });
    }

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery :function() {
            load_data();
        }
    });


	$('#id_teacherid').val(g_args.teacherid);

    $.admin_select_user($("#id_teacherid"),"teacher",load_data);
    $('#opt-add-assess').on('click',function(){
       
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/teacher_info/get_all_teacher_info_new",
            //其他参数
            "args_ex" : {
                
            },

            select_primary_field   : "teacherid",
            select_display         : "nick",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",

            //字段列表
            'field_list' :[
                {
                    title:"teacherid",
                    width :50,
                    field_name:"teacherid"
                },{
                    title:"性别",
                    field_name:"gender_str"
                },{
                    title:"昵称",
                    field_name:"nick"
                },{
                    title:"真实姓名",
                    field_name:"realname"
                },{
                    title:"电话",
                    field_name:"phone"
                }
            ] ,
            //查询列表
            filter_list:[
                [
                    {
                        size_class: "col-md-4" ,
                        title :"性别",
                        type  : "select" ,
                        'arg_name' :  "gender"  ,
                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部" 
                        },{
                            value :  1 ,
                            text :  "男" 
                        },{
                            value :  2 ,
                            text :  "女" 
                        }]
                    },{
                        size_class : "col-md-8" ,
                        title      : "姓名/电话",
                        'arg_name' : "nick_phone"  ,
                        type       : "input" 
                    }
                ]
            ],
            "auto_close" : true,
            "onChange"   : function( val) {
                var teacherid = val ;
                var me=this;
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
                        $.do_ajax("/teacher_info/add_teacher_assess", {
                            "teacherid":teacherid,
                            "content":id_content.val(),
                            "assess_res":id_res.val(),
                            "advise_reason":id_advise_reason.val()
                        });
                    }
                });

            },
            "onLoadData" : null
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
                        url: '/teacher_info/assess_del',
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
                $.do_ajax("/teacher_info/update_teacher_assess", {
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

  

