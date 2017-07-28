/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_warning_stu_info_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			assistantid:	$('#id_assistantid').val(),
			ass_renw_flag:	$('#id_ass_renw_flag').val(),
			master_renw_flag:	$('#id_master_renw_flag').val(),
			renw_week:	$('#id_renw_week').val(),
			done_flag:	$('#id_done_flag').val()
        });
    }


    Enum_map.append_option_list("renw_type", $('#id_ass_renw_flag'));
    Enum_map.append_option_list("renw_type", $('#id_master_renw_flag'),false,[0,1,2]);

	$('#id_assistantid').val(g_args.assistantid);
	$('#id_ass_renw_flag').val(g_args.ass_renw_flag);
	$('#id_master_renw_flag').val(g_args.master_renw_flag);
	$('#id_renw_week').val(g_args.renw_week);
	$('#id_done_flag').val(g_args.done_flag);

    $.admin_select_user($("#id_assistantid"),"assistant", load_data);

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        console.log(opt_data.renw_week);
     
        var $ass_renw_flag  = $("<select />") ;
        var $renw_price=$("<input></input>") ;
        var $no_renw_reason =$("<textarea></textarea>") ;
        var $renw_week  = $("<select><option value=\"0\">无</option><option value=\"1\">第一周</option><option value=\"2\">第二周</option><option value=\"3\">第三周</option><option value=\"4\">第四周</option></select>");
        
        Enum_map.append_option_list("renw_type", $ass_renw_flag, true );
        $ass_renw_flag.val(opt_data.ass_renw_flag );
        $renw_price.val(opt_data.renw_price/100);
        $renw_week.val(opt_data.renw_week);
        $no_renw_reason.val(opt_data.no_renw_reason);

        var arr=[
            ["是否续费", $ass_renw_flag  ],
            ["续费金额", $renw_price ],
            ["未续费原因", $no_renw_reason   ],
            ["续费截止日期",$renw_week]
        ];

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val=$ass_renw_flag.val();
            if (val==1 ) {
                show_field( $no_renw_reason ,false );
                show_field( $renw_price ,true );
                show_field( $renw_week ,true );
            }else if(val==2){
                show_field( $no_renw_reason ,true );
                show_field( $renw_price ,false );
                show_field( $renw_week ,false );
            }else if(val==3){
                show_field( $no_renw_reason ,true );
                show_field( $renw_price ,false );
                show_field( $renw_week ,true );
            }else{
                show_field( $no_renw_reason ,false );
                show_field( $renw_price ,false );
                show_field( $renw_week ,false );
            }
        };

        $ass_renw_flag.on("change",function(){
            reset_ui();
        });
      
        $.show_key_value_table("录入续费信息", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/update_ass_student_renw_info_new", {
                    "userid"                   : opt_data.userid ,
                    "start_time"               : g_args.start_time,
                    "ass_renw_flag"            : $ass_renw_flag.val(),
                    "renw_price"               : $renw_price.val()*100,
                    "no_renw_reason"           : $no_renw_reason.val(),
                    "renw_week"                : $renw_week.val(),
                    "id"                       : opt_data.id
                });
            }
        },function(){
            reset_ui();
        });

    });

    $(".opt-edit-leader").on("click",function(){
        var opt_data=$(this).get_opt_data();
        
        var $master_renw_flag  = $("<select />") ;
        var $master_no_renw_reason =$("<textarea></textarea>") ;
        Enum_map.append_option_list("renw_type", $master_renw_flag, true,[0,1,2] );
        $master_renw_flag.val(opt_data.master_renw_flag );
        $master_no_renw_reason.val(opt_data.master_no_renw_reason);

        var arr=[
            ["是否成功", $master_renw_flag  ],
            ["未续费原因", $master_no_renw_reason   ],
        ];

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val=$master_renw_flag.val();
            if (val==1 || val==0 ) {
                show_field( $master_no_renw_reason ,false );
            }else if(val==2){
                show_field( $master_no_renw_reason ,true );
            }
        };

        $master_renw_flag.on("change",function(){
            reset_ui();
        });
        
        $.show_key_value_table("确认续费信息", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/update_ass_student_renw_info_master_new", {
                    "userid"                   : opt_data.userid ,
                    "start_time"               : g_args.start_time,
                    "master_renw_flag"         : $master_renw_flag.val(),
                    "master_no_renw_reason"    : $master_no_renw_reason.val(),
                    "id"                       : opt_data.id
                });
            }
        },function(){
            reset_ui();
        });

    });

    $(".opt-type-change-list").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var id = opt_data.id;
        var title = "学生类型修改记录";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>操作时间</td><td>修改前状态</td><td>修改后状态</td><td>不续费理由</td><td>续费截止日期</td><td>操作人</td></tr></table></div>");                     

        $.do_ajax("/user_deal/get_renw_flag_change_list",{
            "id" : id
        },function(result){
            if(result.ret!=0){
                BootstrapDialog.alert(result.info);
                return ;
            }

            $.each(result.data,function(i,item){
                html_node.find("table").append("<tr><td>"+item['add_time_str']+"</td><td>"+item['ass_renw_flag_before_str']+"</td><td>"+item['ass_renw_flag_cur_str']+"</td><td>"+item['no_renw_reason']+"</td><td>"+item['renw_end_day']+"</td><td>"+item['account']+"</td></tr>");
                

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

            dlg.getModalDialog().css("width","1024px");

        });
        
        
    });


    if (window.location.pathname=="/user_manage_new/ass_warning_stu_info_new" || window.location.pathname=="/user_manage_new/ass_warning_stu_info_new/") {
        $(".opt-edit-leader").hide();
    }else{
        $(".opt-edit").hide();
    }


	$('.opt-change').set_input_change_event(load_data);
});


