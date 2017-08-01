/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_ass_test_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			require_adminid:	$('#id_require_adminid').val(),
            assistantid:	$('#id_assistantid').val(),
			success_flag:	$('#id_success_flag').val(),
			order_confirm_flag:	$('#id_order_confirm_flag').val()
        });
    }

    Enum_map.append_option_list("success_flag", $("#id_success_flag"));
    Enum_map.append_option_list("success_flag", $("#id_order_confirm_flag "));


	$('#id_require_adminid').val(g_args.require_adminid);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_success_flag').val(g_args.success_flag);
	$('#id_order_confirm_flag').val(g_args.order_confirm_flag);

    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });

    $(".opt-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
       
        var $fail_greater_4_hour_flag = $("<select> <option value=0>否</option> <option value=1>是</option>  </select>") ;
        var $success_flag = $("<select><option value=0>未设置</option><option value=1>成功</option><option value=2>失败</option></select>") ;
        var $test_lesson_fail_flag=$("<select></select>") ;
        var $fail_reason=$("<textarea></textarea>") ;
        Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true );
        $success_flag.val(opt_data.success_flag );
        $fail_reason.val(opt_data.fail_reason);
        $test_lesson_fail_flag.val(opt_data.test_lesson_fail_flag);
        $fail_greater_4_hour_flag .val(opt_data.fail_greater_4_hour_flag);

        var arr=[
            ["学生", opt_data.nick  ],
            ["老师", opt_data.realname],
            ["上课时间", opt_data.lesson_start_str   ],
            ["是否成功",  $success_flag ],
            ["是否离上课4个小时以前(不付老师工资)", $fail_greater_4_hour_flag],
            ["失败类型", $test_lesson_fail_flag],
            ["失败原因", $fail_reason],
        ];

        var update_show_status =function ()  {
            var show_flag =  $success_flag.val()==2 ;
            $fail_greater_4_hour_flag.key_value_table_show( show_flag);
            $test_lesson_fail_flag.key_value_table_show( show_flag);
            $fail_reason.key_value_table_show( show_flag);
            $test_lesson_fail_flag.html("");
            if ($fail_greater_4_hour_flag.val() ==1 ) { //不付老师工资
                Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true, [100,106,107,108,109,110,111,112,113 ] );
            }else{
                Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true ,
                                            [1,2,109,110,111,112,113] );
            }
        };

        $success_flag.on("change",update_show_status);
        $fail_greater_4_hour_flag.on("change",update_show_status);



        $.show_key_value_table("课程确认", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/confirm_test_lesson_ass", {
                    "lessonid"                 : opt_data.lessonid ,
                    "success_flag"             : $success_flag.val(),
                    "fail_reason"              : $fail_reason.val(),
                    "test_lesson_fail_flag"    : $test_lesson_fail_flag.val(),
                    "fail_greater_4_hour_flag" : $fail_greater_4_hour_flag.val(),
                });
            }
        },function(){
            update_show_status();
        });
    });

    $(" .opt-set-success ").on("click", function() {
        var opt_data = $(this).get_opt_data();

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type": "select", // or "list"
            "url": "/ss_deal/get_course_list_js",
            select_primary_field: "courseid",
            select_display: "",
            select_no_select_value: -1, // 没有选择是，设置的值
            select_no_select_title: "[全部]", // "未设置"

            //其他参数
            "args_ex": {
                userid: opt_data.userid,
                teacherid: opt_data.teacherid
            },
            //字段列表
            'field_list': [
                {
                    title: "科目",
                    render: function(val, item) {
                        return item.subject_str;
                    }
                }, {
                    title: "课时数",
                    render: function(val, item) {
                        return item.assigned_lesson_count / 100;
                    }

                }
            ],
            filter_list: [],

            "auto_close": true,
            //选择
            "onChange": function(v) {
                $.do_ajax("/ss_deal/course_order_set_test_lessonid", {
                    "courseid": v,
                    "lessonid": opt_data.lessonid
                });

            },
            //加载数据后，其它的设置
            "onLoadData": null,

        });



    });
    

    $(".opt-set-fail").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var fail_type = opt_data.ass_fail_type;

        var $ass_test_lesson_order_fail_flag=$("<select/>");
        var $ass_test_lesson_order_fail_desc =$("<textarea/>");
        var arr=[
            ["上课时间", opt_data.lesson_start_str] ,
            ["学生", opt_data.nick ] ,
            ["老师", opt_data.realname] ,
            ["失败分类", $ass_test_lesson_order_fail_flag ] ,
            ["失败说明", $ass_test_lesson_order_fail_desc ] ,
        ];

        var t2=new Array(); 
        if(fail_type==1){
            for(var i=0;i<1605;i++) {
                t2[i]=i; 
            }
            t2[2000]=2000;
            console.log(t2);
            Enum_map.append_option_list("ass_test_lesson_order_fail_flag",$ass_test_lesson_order_fail_flag, true,t2);
        }else if(fail_type==2){
            t2[0]=0;
            for(var i=1700;i<2001;i++) {
                t2[i]=i; 
            }
            console.log(t2);
            Enum_map.append_option_list("ass_test_lesson_order_fail_flag",$ass_test_lesson_order_fail_flag, true,t2);
        }
        $ass_test_lesson_order_fail_flag.val( opt_data.ass_test_lesson_order_fail_flag);
        $ass_test_lesson_order_fail_desc.val( opt_data.ass_test_lesson_order_fail_desc);

        var dlg=$.show_key_value_table( "失败设置", arr , {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax( "/ss_deal/set_order_fail_info_ass", {
                    "lessonid" : opt_data.lessonid,
                    "ass_test_lesson_order_fail_flag" : $ass_test_lesson_order_fail_flag.val(),
                    "ass_test_lesson_order_fail_desc" : $ass_test_lesson_order_fail_desc.val(),
                });
            }});

    });

    $(".opt-success-info-list").on("click",function(){
        var lessonid = $(this).data("lessonid");
        console.log(lessonid); 
        $.do_ajax( "/ss_deal/get_test_lesson_confirm_info", {
            "lessonid" : lessonid,
        },function(res){
            var data= res.data;
            var arr=[
                ["是否课前4小时取消",  data.fail_greater_4_hour_flag_str ],
                ["失败类型", data.test_lesson_fail_flag_str ],
                ["失败说明", data.fail_reason ],
                ["设置时间", data.confirm_time_str ],
                ["设置人", data.confirm_adminid_account ],
            ];

            $.show_key_value_table("试听失败详情", arr, "");

        });

    });

    $(".opt-order_confirm-info-list").on("click",function(){
        var lessonid = $(this).data("lessonid"); 
        $.do_ajax( "/ss_deal/get_test_lesson_confirm_info", {
            "lessonid" : lessonid,
        },function(res){
            var data= res.data;
            var arr=[
                ["失败分类", data.ass_test_lesson_order_fail_flag_str ],
                ["失败说明", data.ass_test_lesson_order_fail_desc  ],
                ["设置时间", data.ass_test_lesson_order_fail_set_time_str ],
                ["设置人", data.fail_set_adminid_account ],
            ];

            $.show_key_value_table("失败详情", arr, "");

        });

    });


    if (window.location.pathname=="/seller_student_new2/get_ass_test_lesson_info" || window.location.pathname=="/seller_student_new2/get_ass_test_lesson_info/") {
        $(".opt-set-success").show();
        $(".opt-set-fail").show();
        $(".opt-confirm").show();
    }else{
        $(".opt-set-success").hide();
        $(".opt-set-fail").hide();
        $(".opt-confirm").hide();
    }


	$('.opt-change').set_input_change_event(load_data);
});

