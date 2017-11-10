/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-wx_monitor_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }


    $("#id-add-wx-record").on("click",function(){
        var id_template_type = $("<select/>");
        Enum_map.append_option_list("template_type", id_template_type);

        var arr=[
            ["推送类型",id_template_type]
        ];

        $.show_key_value_table("选择推送类型", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
               // console.log(id_template_type.val());
                var template_type = id_template_type.val();
                var id_first_sentence  = $("<textarea/>");
                var id_end_sentence  = $("<textarea/>");
                var id_keyword1  = $("<textarea/>");
                var id_keyword2  = $("<textarea/>");
                var id_keyword3  = $("<textarea/>");
                var id_url = $("<input/>");
                var id_teacherid = $("<input/>");
                var id_subject=$("<select/>");
                var id_grade_part_ex=$("<select/>");
                var id_identity=$("<select/>");
                var id_create_time=$("<select><option value=\"-1\">全部</option><option value=\"1\">入职一周</option><option value=\"2\">入职一个月</option></select>");
                var id_tea_qua=$("<select><option value=\"-1\">全部</option><option value=\"1\">已冻结</option><option value=\"2\">已限课</option><option value=\"3\">已反馈</option></select>");
                var id_tra=$("<select><option value=\"-1\">全部</option><option value=\"1\">高于25%</option><option value=\"2\">低于10%</option><option value=\"3\">10% - 25%</option></select>");
                Enum_map.append_option_list("subject", id_subject);
                Enum_map.append_option_list("grade_part_ex", id_grade_part_ex);
                Enum_map.append_option_list("identity", id_identity);

                if(template_type==4){
                    var arr=[
                        ["开始语", id_first_sentence],
                        ["结束语", id_end_sentence],
                        ["用户姓名", id_keyword1],
                        ["资料名称", id_keyword2],
                        ["跳转地址", id_url],
                        ["老师科目", id_subject],
                        ["老师年级", id_grade_part_ex],
                        ["老师类型", id_identity],
                        ["入职情况", id_create_time],
                        ["教学质量", id_tea_qua],
                        ["转化率", id_tra],
                        ["老师（可不选）", id_teacherid]
                    ];

                }else{
                    var arr=[
                        ["开始语", id_first_sentence],
                        ["结束语", id_end_sentence],
                        ["待办主题", id_keyword1],
                        ["待办内容", id_keyword2],
                        ["日期", id_keyword3],
                        ["跳转地址", id_url],
                        ["老师科目", id_subject],
                        ["老师年级", id_grade_part_ex],
                        ["老师类型", id_identity],
                        ["入职情况", id_create_time],
                        ["教学质量", id_tea_qua],
                        ["转化率", id_tra],
                        ["老师（可不选）", id_teacherid]
                    ];
                }

                $.show_key_value_table("推送消息", arr ,[{
                    label: '微信预览',
                    cssClass: 'btn-warning',
                    action : function(dialog) {
                        var title     = "微信预览";
                        var myDate = new Date();
                        if(template_type==4){
                            var html_node = $("<div style=\"font-size:20px\">资料领取通知</div><div>"+myDate.toLocaleDateString()+"</div><br><div>"+id_first_sentence.val()+"</div><div><font style=\"font-size:16px\">用户姓名: </font>"+id_keyword1.val()+"</div><div><font style=\"font-size:16px\">资料名称: </font>"+id_keyword2.val()+"</div><div>"+id_end_sentence.val()+"</div><br><br><div><a href=\""+id_url.val()+"\" target=\"_blank\">详情</a></div>");
                        }else{
                            var html_node = $("<div style=\"font-size:20px\">待办事项提醒</div><div>"+myDate.toLocaleDateString()+"</div><br><div>"+id_first_sentence.val()+"</div><div><font style=\"font-size:16px\">待办主题: </font>"+id_keyword1.val()+"</div><div><font style=\"font-size:16px\">待办内容: </font>"+id_keyword2.val()+"</div><div><font style=\"font-size:16px\">日期: </font>"+id_keyword3.val()+"</div><div>"+id_end_sentence.val()+"</div><br><br><div><a href=\""+id_url.val()+"\" target=\"_blank\">详情</a></div>");
                        }
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

                        dlg.getModalDialog().css("width","680px");



                    }
                },{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action : function(dialog) {

                        $.do_ajax( '/ss_deal/send_wx_template_to_teacher', {
                            "subject"                : id_subject.val(),
                            "grade_part_ex"          : id_grade_part_ex.val(),
                            "identity"               : id_identity.val(),
                            "url"                    : id_url.val(),
                            "create_time"            : id_create_time.val(),
                            "tea_qua"                : id_tea_qua.val(),
                            "tra"                    : id_tra.val(),
                            "template_type"          : template_type,
                            "first_sentence"         : id_first_sentence.val(),
                            "end_sentence"           : id_end_sentence.val(),
                            "keyword3"               : id_keyword3.val(),
                            "keyword2"               : id_keyword2.val(),
                            "keyword1"               : id_keyword1.val(),
                            "send_teacherid"              : id_teacherid.val()

                        });

                    }

                }],function(){
                    $.admin_select_user(id_teacherid,"teacher");
                });



            }
        });

    });


  $('.opt-change').set_input_change_event(load_data);
});
