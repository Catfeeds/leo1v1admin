/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-return_record.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            sid: g_args.sid,
            is_warning_flag:	$('#id_is_warning_flag').val()
        });
    }

    Enum_map.append_option_list( "is_warning_flag",$('#id_is_warning_flag') );
	$('#id_is_warning_flag').val(g_args.is_warning_flag);

    $(".opt-audio").each(function(){
        var opt_data=$(this).get_opt_data();
        if (!opt_data.record_url ) {
            $(this).hide();
            // $(this).parent().find(".opt-edit").hide();
        }
    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $revisit_type =$("<select/>");
        var $lesson_total = $("<input/>");
        var $revisit_person= $("<select> <option value=\"爸爸\">爸爸</option> <option value=\"妈妈\">妈妈</option> <option value=\"孩子\">孩子</option>  <option value=\"其他\">其他</option> </select>");
        var $operator_note= $("<textarea/>");
        var $operation_satisfy_flag =$("<select/>");
        var $operation_satisfy_type =$("<select/>");
        var $operation_satisfy_info  = $("<textarea/>");
        var $record_tea_class_flag =$("<select/>");
        var $child_performance   = $("<textarea/>");
        var $tea_content_satisfy_flag  =$("<select/>");
        var $tea_content_satisfy_type =$("<select/>");
        var $tea_content_satisfy_info  = $("<textarea/>");
        var $other_parent_info  = $("<textarea/>");



        Enum_map.append_option_list( "revisit_type", $revisit_type);
        Enum_map.append_option_list( "set_boolean",  $operation_satisfy_flag,true);
        Enum_map.append_option_list( "set_boolean",  $record_tea_class_flag,true);
        Enum_map.append_option_list( "set_boolean",  $tea_content_satisfy_flag,true);
        Enum_map.append_option_list( "operation_satisfy_type", $operation_satisfy_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_type", $tea_content_satisfy_type,true);

        $revisit_type.val(opt_data.revisit_type );
        $revisit_person.val(opt_data.revisit_person );
        $operator_note.val(opt_data.operator_note );
        $operation_satisfy_flag.val(opt_data.operation_satisfy_flag );
        $operation_satisfy_type.val(opt_data.operation_satisfy_type );
        $operation_satisfy_info.val(opt_data.operation_satisfy_info );
        $record_tea_class_flag.val(opt_data.record_tea_class_flag );
        $child_performance.val(opt_data.child_performance );
        $tea_content_satisfy_flag.val(opt_data.tea_content_satisfy_flag );
        $tea_content_satisfy_type.val(opt_data.tea_content_satisfy_type);
        $tea_content_satisfy_info.val(opt_data.tea_content_satisfy_info );
        $other_parent_info.val(opt_data.other_parent_info );



        var arr=[
            ["回访时间", opt_data.revisit_time ],
            ["通话时长", opt_data.duration],
            ["类型",  $revisit_type ],
            ["回访对象",  $revisit_person ],
            ["说明",  $operator_note ],
            ["家长对于我们的软件操作和体验是否满意",  $operation_satisfy_flag ],
            ["家长对于我们的软件操作和体验不满意的类型",  $operation_satisfy_type ],
            ["家长对于我们的软件操作和体验不满意的具体描述",  $operation_satisfy_info ],
            ["是否完成反馈老师对于近期课程的评价和不足",  $record_tea_class_flag ],
            ["学生在校近期表现",  $child_performance ],
            ["家长对于老师教学内容和水平是否满意",  $tea_content_satisfy_flag ],
            ["家长对于老师教学内容和水平不满意的类型",  $tea_content_satisfy_type ],
            ["家长对于老师教学内容和水平不满意的具体描述",  $tea_content_satisfy_info ],
            ["退费预警其他情况说明",  $other_parent_info ],
        ];

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val1=$operation_satisfy_flag.val();
            var val2=$tea_content_satisfy_flag.val();
            if (val1==1 || val1==0) {
                show_field( $operation_satisfy_type ,false );
                show_field( $operation_satisfy_info,false );
            }else{
                show_field( $operation_satisfy_type ,true);
                show_field( $operation_satisfy_info,true);
            }
            if (val2==1 || val2==0) {
                show_field( $tea_content_satisfy_type ,false );
                show_field( $tea_content_satisfy_info,false );
            }else{
                show_field( $tea_content_satisfy_type ,true);
                show_field( $tea_content_satisfy_info,true);
            }

        };

        $operation_satisfy_flag.on("change",function(){
            reset_ui();
        });
        $tea_content_satisfy_flag.on("change",function(){
            reset_ui();
        });



        $.show_key_value_table("编辑",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog){
                $.do_ajax("/user_deal/set_revisit",{
                    userid : g_args.sid,
                    revisit_time: opt_data.revisit_time,
                    revisit_type: $revisit_type.val() ,
                    revisit_person: $revisit_person.val() ,
                    operator_note: $operator_note.val(),
                    operation_satisfy_flag: $operation_satisfy_flag.val(),
                    operation_satisfy_type: $operation_satisfy_type.val(),
                    operation_satisfy_info: $operation_satisfy_info.val(),
                    record_tea_class_flag: $record_tea_class_flag.val(),
                    child_performance: $child_performance.val(),
                    tea_content_satisfy_flag: $tea_content_satisfy_flag.val(),
                    tea_content_satisfy_type: $tea_content_satisfy_info.val(),
                    tea_content_satisfy_info: $tea_content_satisfy_info.val(),
                    other_parent_info: $other_parent_info.val()
                });
            }
        },function(){
            reset_ui();
        });

    });

    $(".opt-edit-new").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $revisit_type =$("<select/>");
        var $lesson_total = $("<input/>");
        var $revisit_person= $("<select> <option value=\"爸爸\">爸爸</option> <option value=\"妈妈\">妈妈</option> <option value=\"孩子\">孩子</option>  <option value=\"其他\">其他</option> </select>");
        var $operator_note= $("<textarea/>");
        var $operation_satisfy_flag =$("<select/>");
        var $operation_satisfy_type =$("<select/>");
        var $operation_satisfy_info  = $("<textarea/>");
        var $child_class_performance_flag =$("<select/>");
        var $child_class_performance_type =$("<select/>");
        var $child_class_performance_info  = $("<textarea/>");
        var $school_score_change_flag  =$("<select/>");
        var $school_score_change_info   = $("<textarea/>");
        var $school_work_change_flag =$("<select/>");
        var $school_work_change_type =$("<select/>");
        var $school_work_change_info  = $("<textarea/>");
        var $tea_content_satisfy_flag  =$("<select/>");
        var $tea_content_satisfy_type =$("<select/>");
        var $tea_content_satisfy_info  = $("<textarea/>");

        var $other_parent_info  = $("<textarea/>");
        var $other_warning_info   = $("<textarea/>");
        var $is_warning_flag  =$("<select/>");



        Enum_map.append_option_list( "revisit_type", $revisit_type);
        Enum_map.append_option_list( "set_boolean",  $operation_satisfy_flag,true);
        Enum_map.append_option_list( "set_boolean",  $school_work_change_flag,true);
        Enum_map.append_option_list( "child_class_performance_type",  $child_class_performance_type,true);
        Enum_map.append_option_list( "operation_satisfy_type", $operation_satisfy_type,true);
        Enum_map.append_option_list( "child_class_performance_flag", $child_class_performance_flag,true);
        Enum_map.append_option_list( "school_score_change_flag", $school_score_change_flag,true);
        Enum_map.append_option_list( "school_work_change_type", $school_work_change_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_type", $tea_content_satisfy_type,true);
        Enum_map.append_option_list( "tea_content_satisfy_flag", $tea_content_satisfy_flag,true);

        $revisit_type.val(opt_data.revisit_type );
        $revisit_person.val(opt_data.revisit_person );
        $operator_note.val(opt_data.operator_note );
        $operation_satisfy_flag.val(opt_data.operation_satisfy_flag );
        $operation_satisfy_type.val(opt_data.operation_satisfy_type );
        $operation_satisfy_info.val(opt_data.operation_satisfy_info );
        $child_class_performance_flag.val(opt_data.child_class_performance_flag );
        $child_class_performance_type.val(opt_data.child_class_performance_type);
        $child_class_performance_info.val(opt_data.child_class_performance_info);
        $tea_content_satisfy_flag.val(opt_data.tea_content_satisfy_flag );
        $tea_content_satisfy_type.val(opt_data.tea_content_satisfy_type);
        $tea_content_satisfy_info.val(opt_data.tea_content_satisfy_info );
        $other_parent_info.val(opt_data.other_parent_info );
        $other_warning_info.val(opt_data.other_warning_info );
        $school_score_change_flag.val(opt_data.school_score_change_flag );
        $school_score_change_info.val(opt_data.school_score_change_info);
        $school_work_change_flag.val(opt_data.school_work_change_flag );
        $school_work_change_type.val(opt_data.school_work_change_type );
        $school_work_change_info.val(opt_data.school_work_change_info );




        var arr=[
            ["回访时间", opt_data.revisit_time ],
            ["通话时长", opt_data.duration],
            ["类型",  $revisit_type ],
            ["回访对象",  $revisit_person ],
            ["说明",  $operator_note ],
            ["软件操作是否满意",  $operation_satisfy_flag ],
            ["软件操作不满意的类型",  $operation_satisfy_type ],
            ["软件操作不满意的具体描述",  $operation_satisfy_info ],
            ["孩子课堂表现",  $child_class_performance_flag ],
            ["孩子课堂表现不好的类型",  $child_class_performance_type ],
            ["孩子课堂表现不好的具体描述",  $child_class_performance_info ],
            ["学校成绩变化",  $school_score_change_flag ],
            ["学校成绩变差的具体描述",  $school_score_change_info ],
            ["学业变化",  $school_work_change_flag ],
            ["学业变化的类型",  $school_work_change_type ],
            ["学业变化的具体描述",  $school_work_change_info ],
            ["对于老师or教学是否满意",  $tea_content_satisfy_flag ],
            ["对于老师or教学不满意的类型",  $tea_content_satisfy_type ],
            ["对于老师or教学不满意的具体描述",  $tea_content_satisfy_info ],
            ["家长意见或建议",  $other_parent_info ],
            ["其他预警问题",  $other_warning_info ],
        ];

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val1=$operation_satisfy_flag.val();
            var val2=$tea_content_satisfy_flag.val();
            var val3=$child_class_performance_flag.val();
            var val4=$school_score_change_flag.val();
            var val5=$school_work_change_flag.val();
            if (val1==1 || val1==0) {
                show_field( $operation_satisfy_type ,false );
                show_field( $operation_satisfy_info,false );
            }else{
                show_field( $operation_satisfy_type ,true);
                show_field( $operation_satisfy_info,true);
            }
            if (val2==1 || val2==0 || val2==2) {
                show_field( $tea_content_satisfy_type ,false );
                show_field( $tea_content_satisfy_info,false );
            }else{
                show_field( $tea_content_satisfy_type ,true);
                show_field( $tea_content_satisfy_info,true);
            }
            if (val3==1 || val3==0 || val3==2) {
                show_field( $child_class_performance_type ,false );
                show_field( $child_class_performance_info,false );
            }else{
                show_field( $child_class_performance_type ,true);
                show_field( $child_class_performance_info,true);
            }
            if (val4==1 || val4==0) {
                show_field( $school_score_change_info,false );
            }else{
                show_field( $school_score_change_info,true);
            }

            if (val5==2 || val5==0) {
                show_field( $school_work_change_type ,false );
                show_field( $school_work_change_info,false );
            }else{
                show_field( $school_work_change_type ,true);
                show_field( $school_work_change_info,true);
            }


        };

        $operation_satisfy_flag.on("change",function(){
            reset_ui();
        });
        $tea_content_satisfy_flag.on("change",function(){
            reset_ui();
        });
        $child_class_performance_flag.on("change",function(){
            reset_ui();
        });
        $school_score_change_flag.on("change",function(){
            reset_ui();
        });
        $school_work_change_flag.on("change",function(){
            reset_ui();
        });

        $.show_key_value_table("编辑",arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog){
                $.do_ajax("/user_deal/set_revisit",{
                    userid : g_args.sid,
                    revisit_time: opt_data.revisit_time,
                    revisit_type: $revisit_type.val() ,
                    revisit_person: $revisit_person.val() ,
                    operator_note: $operator_note.val(),
                    operation_satisfy_flag: $operation_satisfy_flag.val(),
                    operation_satisfy_type: $operation_satisfy_type.val(),
                    operation_satisfy_info: $operation_satisfy_info.val(),
                    child_class_performance_flag: $child_class_performance_flag.val(),
                    child_class_performance_type: $child_class_performance_type.val(),
                    child_class_performance_info: $child_class_performance_info.val(),
                    school_score_change_flag: $school_score_change_flag.val(),
                    school_score_change_info: $school_score_change_info.val(),
                    school_work_change_flag: $school_work_change_flag.val(),
                    school_work_change_type: $school_work_change_type.val(),
                    school_work_change_info: $school_work_change_info.val(),
                    tea_content_satisfy_flag: $tea_content_satisfy_flag.val(),
                    tea_content_satisfy_type: $tea_content_satisfy_type.val(),
                    tea_content_satisfy_info: $tea_content_satisfy_info.val(),
                    other_parent_info: $other_parent_info.val(),
                    other_warning_info: $other_warning_info.val(),
                    sys_operator:opt_data.sys_operator
                });
            }
        },function(){
            reset_ui();
        });

    });


    $(".opt-warning-record").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_warning_deal_info  = $("<textarea />");
        var id_warning_deal_url = $("<div><input class=\"warning_deal_url\" id=\"warning_deal_url\" type=\"text\"readonly ><div ><span><a class=\"upload_gift_pic\" id=\"id_upload_warning_deal\" href=\"javascript:;\">上传</a></span><span><a style=\"margin-left:20px\" href=\"javascript:;\" id=\"id_del_warning_deal\">删除</a></span></div></div>");
        var id_is_warning_flag = $("<select><option value=\"1\">预警中</option><option value=\"2\">已解决</option></select>");
        id_warning_deal_info.val(opt_data.warning_deal_info);
        id_is_warning_flag.val(opt_data.is_warning_flag);
        id_warning_deal_url.find("#warning_deal_url").val(opt_data.url);
        var arr = [
            ["预警处理方案",  id_warning_deal_info ],
            ["相关图片上传",  id_warning_deal_url ],
            ["预警解决",  id_is_warning_flag ]
        ];

        id_warning_deal_url.find("#id_del_warning_deal").on("click",function(){
            id_warning_deal_url.find("#warning_deal_url").val("");
        });
        $.show_key_value_table("预警处置", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                $.do_ajax("/user_deal/set_revisit_warning_deal_info", {
                    userid : g_args.sid,
                    revisit_time: opt_data.revisit_time,
                    warning_deal_url : id_warning_deal_url.find("#warning_deal_url").val(),
                    warning_deal_info: id_warning_deal_info.val(),
                    is_warning_flag:id_is_warning_flag.val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_warning_deal',true,function (up, info, file) {
                var res = $.parseJSON(info);

                $("#warning_deal_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

        });


        

    });

    $(".show_pic").on('click',function(){
        var url = $(this).data("url");
        $.wopen(url);
        
    });


    $(".opt-audio").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var   url = opt_data.record_url;
        if (opt_data.load_wav_self_flag) {
            var file=opt_data.record_url.split("/")[4];
            file=file.split(".")[0]+".mp3";
            url= "/audio/"+file;
        }

        var html_node = $(" <div  style=\"text-align:center;\"  > <div id=\"drawing_list\" style=\"width:100%\"  > </div>  <audio preload=\"none\"  > </audio> </div> <br>  <a href=\""+url+"\" class=\"btn btn-primary \"  target=\"_blank\"> 下载 </a> ");

        var audio_node   = html_node.find("audio" );

        BootstrapDialog.show({
            title    : "录音:"+opt_data.phone ,
            message  : html_node,
            closable : true,
            onhide   : function(dialogRef){
            },
            onshown: function() {


                    //加载mp3
                    audiojs.events.ready(function(){
                        var as = audiojs.createAll({}, audio_node  );
                        as[0].load(url);
                        as[0].play();

                    });
            }
        });


    });


    $('.opt-change').set_input_change_event(load_data);

    $("#id_reload_ytx").on("click",function(){
        $.do_ajax("/ss_deal/sync_ytx",{});
    });

});
