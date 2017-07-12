/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_warning_stu_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			assistantid:	$('#id_assistantid').val(),
			ass_renw_flag:	$('#id_ass_renw_flag').val(),
			master_renw_flag:	$('#id_master_renw_flag').val(),
			renw_week:	$('#id_renw_week').val()
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
    Enum_map.append_option_list("renw_type", $('#id_ass_renw_flag'));
    Enum_map.append_option_list("renw_type", $('#id_master_renw_flag'),false,[0,1,2]);

	$('#id_assistantid').val(g_args.assistantid);
	$('#id_ass_renw_flag').val(g_args.ass_renw_flag);
	$('#id_master_renw_flag').val(g_args.master_renw_flag);
	$('#id_renw_week').val(g_args.renw_week);

    $.admin_select_user($("#id_assistantid"),"assistant", load_data);

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
     
        var $ass_renw_flag  = $("<select />") ;
        var $renw_price=$("<input></input>") ;
        var $no_renw_reason =$("<textarea></textarea>") ;
        var $renw_week  = $("<select><option value=\"0\">无</option><option value=\"1\">第一周</option><option value=\"2\">第二周</option><option value=\"3\">第三周</option><option value=\"4\">第四周</option><option value=\"5\">第五周</option></select>");
        
        Enum_map.append_option_list("renw_type", $ass_renw_flag, true );
        $ass_renw_flag.val(opt_data.ass_renw_flag );
        $renw_price.val(opt_data.renw_price/100);
        $renw_week.val(opt_data.renw_week);
        $no_renw_reason.val(opt_data.no_renw_reason);

        var arr=[
            ["是否续费", $ass_renw_flag  ],
            ["续费金额", $renw_price ],
            ["未续费原因", $no_renw_reason   ],
            ["计划续约周",$renw_week]
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
                show_field( $renw_price ,true );
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
                $.do_ajax("/ss_deal/update_ass_student_renw_info", {
                    "userid"                   : opt_data.userid ,
                    "start_time"               : g_args.start_time,
                    "ass_renw_flag"            : $ass_renw_flag.val(),
                    "renw_price"               : $renw_price.val()*100,
                    "no_renw_reason"           : $no_renw_reason.val(),
                    "renw_week"                : $renw_week.val()
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
                $.do_ajax("/ss_deal/update_ass_student_renw_info_master", {
                    "userid"                   : opt_data.userid ,
                    "start_time"               : g_args.start_time,
                    "master_renw_flag"         : $master_renw_flag.val(),
                    "master_no_renw_reason"    : $master_no_renw_reason.val(),
                });
            }
        },function(){
            reset_ui();
        });

    });

    if (window.location.pathname=="/user_manage_new/ass_warning_stu_info" || window.location.pathname=="/user_manage_new/ass_warning_stu_info/") {
        $(".opt-edit-leader").hide();
    }else{
        $(".opt-edit").hide();
    }


	$('.opt-change').set_input_change_event(load_data);
});


