/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-test_lesson_plan_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:                   $('#id_date_type').val(),
            has_1v1_lesson_flag:         $('#id_has_1v1_lesson_flag').val(),
            opt_date_type:               $('#id_opt_date_type').val(),
            start_time:                  $('#id_start_time').val(),
            end_time:                    $('#id_end_time').val(),
            grade:                       $('#id_grade').val(),
            subject:                     $('#id_subject').val(),
            test_lesson_student_status:  $('#id_test_lesson_student_status').val(),
            lessonid:                    $('#id_lessonid').val(),
            userid:                      $('#id_userid').val(),
            teacherid:                   $('#id_teacherid').val(),
            success_flag:                $('#id_success_flag').val(),
            require_admin_type:          $('#id_require_admin_type').val(),
            require_adminid:             $('#id_require_adminid').val(),
            tmk_adminid:                 $('#id_tmk_adminid').val(),
            is_test_user:                $('#id_is_test_user').val(),
            test_lesson_fail_flag:       $('#id_test_lesson_fail_flag').val(),
            accept_flag:                 $('#id_accept_flag').val(),
            seller_groupid_ex:           $('#id_seller_groupid_ex').val(),
            seller_require_change_flag : $('#id_seller_require_change_flag ').val(),
            require_assign_flag:         $('#id_require_assign_flag').val(),
            jw_test_lesson_status:       $('#id_jw_test_lesson_status').val(),
            jw_teacher:                  $('#id_jw_teacher').val(),
            ass_test_lesson_type:        $('#id_ass_test_lesson_type').val(),
			lesson_plan_style:	$('#id_lesson_plan_style').val()
        });
    }

    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("seller_student_status",$("#id_test_lesson_student_status"),false,
                                [110,120,200,210,220] );
    Enum_map.append_option_list("seller_require_change_flag",$("#id_seller_require_change_flag"),false,[1,2,3]);
    Enum_map.append_option_list("account_role",$("#id_require_admin_type"));
    Enum_map.append_option_list("set_boolean",$("#id_success_flag"));
    Enum_map.append_option_list("ass_test_lesson_type",$("#id_ass_test_lesson_type"));
    Enum_map.append_option_list("test_lesson_fail_flag",$("#id_test_lesson_fail_flag"));
    Enum_map.append_option_list("set_boolean",$("#id_accept_flag"));
    Enum_map.append_option_list("boolean",$("#id_is_test_user"));
    Enum_map.append_option_list("boolean",$("#id_require_assign_flag"));
    Enum_map.append_option_list("boolean",$("#id_has_1v1_lesson_flag"));
    Enum_map.append_option_list("jw_test_lesson_status",$("#id_jw_test_lesson_status"));

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex(g_adminid_right);

    $('#id_seller_require_change_flag ').val(g_args.seller_require_change_flag );

    $('#id_require_assign_flag').val(g_args.require_assign_flag);
    $('#id_grade').val(g_args.grade);
    $('#id_subject').val(g_args.subject);
    $('#id_test_lesson_student_status').val(g_args.test_lesson_student_status);
    $('#id_lessonid').val(g_args.lessonid);
    $('#id_test_lesson_fail_flag').val(g_args.test_lesson_fail_flag);
    $('#id_userid').val(g_args.userid);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_has_1v1_lesson_flag').val(g_args.has_1v1_lesson_flag);
    $('#id_tmk_adminid').val(g_args.tmk_adminid);
    $('#id_require_admin_type').val(g_args.require_admin_type);
    $('#id_accept_flag').val(g_args.accept_flag);
    $('#id_success_flag').val(g_args.success_flag);
    $('#id_is_test_user').val(g_args.is_test_user);
    $('#id_require_adminid').val(g_args.require_adminid);
    $('#id_ass_test_lesson_type').val(g_args.ass_test_lesson_type);
    $('#id_jw_test_lesson_status').val(g_args.jw_test_lesson_status);
    $('#id_jw_teacher').val(g_args.jw_teacher);
	  $('#id_lesson_plan_style').val(g_args.lesson_plan_style);

    $.admin_select_user($('#id_tmk_adminid'),"admin",load_data,false,{
        " main_type": -1 ,
        select_btn_config: [{
            "label": "[已分配]",
            "value": -2
        }, {
            "label": "[未分配]",
            "value": 0
        }]
    });
    
    $.admin_select_user($('#id_userid'),"student", load_data);
    $.admin_select_user($('#id_teacherid'),"teacher", load_data);
    $.admin_select_user($('#id_require_adminid'),"admin", load_data);
    $('.opt-change').set_input_change_event(load_data);

    if (window.location.pathname=="/seller_student_new2/test_lesson_plan_list_seller" || window.location.pathname=="/seller_student_new2/test_lesson_plan_list_seller/" || window.location.pathname=="/seller_student_new2/test_lesson_plan_list_ass_leader" || window.location.pathname=="/seller_student_new2/test_lesson_plan_list_ass_leader/" || window.location.pathname=="/seller_student_new2/test_lesson_plan_list_jw_leader" || window.location.pathname=="/seller_student_new2/test_lesson_plan_list_jw_leader/") {
        $(".show_flag").hide();
        $(".limit-require-info").show();
    }else{
        $(".show_seller").hide();
        $(".limit-require-info").hide();
    }

    if (window.location.pathname=="/seller_student_new2/ass_test_lesson_list_tran" || window.location.pathname=="/seller_student_new2/ass_test_lesson_list_tran/" || window.location.pathname=="/seller_student_new2/ass_test_lesson_list" || window.location.pathname=="/seller_student_new2/ass_test_lesson_list/") {
        $(".opt-binding-course-order").hide();
        $(".opt-test_lesson_order_fail").hide();
    }else{
        $(".opt-binding-course-order").show();
        $(".opt-test_lesson_order_fail").show();
    }


    $(".opt-download-test-paper").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.custom_show_pdf(opt_data.stu_test_paper);
        console.log(opt_data.stu_test_paper);
    });

    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var opt_data= $(this).get_opt_data();
        $.wopen('/stu_manage?sid=' + opt_data.userid);
    });

    $(".opt-set-lesson-new ").on("click",function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.jw_test_lesson_status == 2){
            alert("请先解除挂载!");
            return;
        }
        var id_teacherid       = $("<input/>");
        var id_start_time      = $("<input/>");
        var id_top_seller_flag = $("<select />");
        Enum_map.append_option_list("boolean",id_top_seller_flag,true);

        if(opt_data.teacherid > 0){
             id_teacherid.val(opt_data.teacherid);
        }else if(opt_data.green_channel_teacherid > 0){
             id_teacherid.val(opt_data.green_channel_teacherid);
        }

        id_start_time.datetimepicker({
            lang             : 'ch',
            datepicker       : true,
            timepicker       : true,
            format:'Y-m-d H:i',
            step             : 30,
            onChangeDateTime : function(){
            }
        });

        var arr = [
            ["学生",opt_data.nick]  ,
            ["期待时间",opt_data.stu_request_test_lesson_time]  ,
            ["老师",id_teacherid ]  ,
            ["开始时间",id_start_time ]  ,
            ["年级",opt_data.grade_str]  ,
            ["科目",opt_data.subject_str]  ,
        ];

        $.show_key_value_table("排课", arr ,[
            {
                label    : '驳回',
                cssClass : 'btn-danger',
                action   : function(dialog) {
                    var $input = $("<input style=\"width:180px\"  placeholder=\"驳回理由\"/>");
                    $.show_input(
                        opt_data.nick+" : "+ opt_data.subject_str+ ",要驳回, 不计算排课数?! ",
                        "",function(val){
                            $.do_ajax("/ss_deal/set_no_accpect",{
                                'require_id'       : opt_data.require_id,
                                'fail_reason' : val
                            });
                        }, $input  );
                    $input.val("未排课,期待时间已到");
                }
            },{
                label    : '------',
            },{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    var do_post = function() {
                        $.do_ajax("/ss_deal/course_set_new",{
                            'require_id'   : opt_data.require_id,
                            "grade"        : opt_data.grade,
                            'teacherid'    : id_teacherid.val(),
                            'lesson_start' : id_start_time.val(),
                            'top_seller_flag' : opt_data.seller_top_flag
                        });
                    };

                    var now        = (new Date()).getTime()/1000;
                    var start_time = $.strtotime(id_start_time.val());
                    if ( now > start_time ) {
                        alert("上课时间比现在还小.");
                        return ;
                    } else if ( now + 5*3600  > start_time ) {
                        BootstrapDialog.confirm("上课时间离现在很近了,要提交吗?!",function(val){
                            if(val) {
                                do_post();
                            }
                        });
                    }else{
                        do_post();
                    }
                }
            }],function(){
                $.admin_select_user(id_teacherid,"train_through_teacher");
            });
    });



    $(".opt-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if(!( opt_data.lessonid )) {
            alert("还没有排课,无需确认");
            return;
        }

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
            ["老师", opt_data.teacher_nick ],
            ["上课时间", opt_data.lesson_start   ],
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
                $.do_ajax("/ss_deal/confirm_test_lesson", {
                    "require_id"             : opt_data.require_id ,
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

    $(".opt-lesson-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ss_deal/get_lesson_list_by_require_id_js",
            //其他参数
            "args_ex" : {
                require_id:opt_data.require_id
            },
            //字段列表
            'field_list' :[
                {
                    title:"id",
                    render:function(val,item) {
                        return item.require_id;
                    }

                },{

                    title:"时间",
                    render:function(val,item) {
                        return item.set_lesson_time ;
                    }

                },{
                    title:"教务是否接受",
                    //width :50,
                    render:function(val,item) {
                        return $(item.accept_flag_str );
                    }
                },{
                    title:"课程是否成功",
                    render:function(val,item) {
                        return $(item.success_flag_str) ;
                    }

                },{
                    title:"原因",
                    render:function(val,item) {
                        return $( "<div>"+item.test_lesson_fail_flag_str  + "<br> " +
                                  item.fail_reason+ "</div>");
                    }
                },{
                    title:"老师",
                    render:function(val,item) {
                        return item.teacher_nick;
                    }
                },{
                    title:"上课时间",
                    render:function(val,item) {
                        return item.lesson_start;
                    }

                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });


    });



    $(".opt-get_stu_performance").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax('/ss_deal/get_stu_performance_for_seller',{
            "require_id":opt_data.require_id
        },function(result){
            var $stu_lesson_content     = $("<div></div>");
            var $stu_lesson_status      = $("<div></div>");
            var $stu_study_status       = $("<div></div>");
            var $stu_advantages         = $("<div></div>");
            var $stu_disadvantages      = $("<div></div>");
            var $stu_lesson_plan        = $("<div></div>");
            var $stu_teaching_direction = $("<div></div>");
            var $stu_advice             = $("<div></div>");

            var arr = [
                ["试听情况", $stu_lesson_content],
                ["学习态度", $stu_lesson_status],
                ["学习基础情况", $stu_study_status],
                ["学生优点", $stu_advantages],
                ["学生有待提高", $stu_disadvantages],
                ["培训计划", $stu_lesson_plan],
                ["教学方向", $stu_teaching_direction],
                ["意见,建议", $stu_advice],
            ];

            $stu_lesson_content.html(result.data.stu_lesson_content);
            $stu_lesson_status.html(result.data.stu_lesson_status);
            $stu_study_status.html(result.data.stu_study_status);
            $stu_advantages.html(result.data.stu_advantages);
            $stu_disadvantages.html(result.data.stu_disadvantages);
            $stu_lesson_plan.html(result.data.stu_lesson_plan);
            $stu_teaching_direction.html(result.data.stu_teaching_direction);
            $stu_advice.html(result.data.stu_advice);

            $.show_key_value_table("试听评价", arr);
        });

    });
    $(".opt-user-info").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var id_left_info=$("<div />");
        var id_right_info=$("<div />");

        var arr=[
            [id_left_info, id_right_info],
        ];
        var left_info = '地址:'+opt_data.phone_location+'<br>'+'姓名:'+opt_data.nick+'<br>'+'年级:'+opt_data.grade_str+'<br>'+'科目:'+opt_data.subject_str+'<br>'+'学校:'+opt_data.school+'<br>'+'教材:'+opt_data.editionid_str+'<br>'+'试卷:'+opt_data.stu_test_paper_flag_str+'<br>'+'成绩情况:'+opt_data.stu_score_info+'<br>'+'性格信息:'+opt_data.stu_character_info;
        var right_info = '期待时间:'+opt_data.stu_request_test_lesson_time +'<br>'+'期待时间(其它):'+opt_data.stu_request_test_lesson_time_info_str+'<br>'+'正式上课:'+opt_data.stu_request_lesson_time_info_str+'<br>'+'试听内容:'+opt_data.stu_test_lesson_level_str+'<br>'+'试听需求:'+opt_data.stu_request_test_lesson_demand ;
        id_left_info.html(left_info);
        id_right_info.html(right_info);
        id_left_info.css("text-align","left").css("width","250px");
        id_right_info.css("width","350px");
        $.show_key_value_table("试听信息", arr ,"");
        id_left_info.parent().parent().parent().parent().find("thead tr").children("td").eq(0).css("text-align","left").text("基本信息");
        id_left_info.parent().parent().parent().parent().find("thead tr").children("td").eq(1).text("试听信息");
        id_left_info.parent().parent().parent().parent().parent().parent().parent().parent().css("width","700px");

    });

    //init-page
    if(g_args.cur_page=="ass_test_lesson_list" || g_args.cur_page=="ass_test_lesson_list_tran" ) {
        $("#id_require_adminid").parent().parent().hide();
        $("#id_require_admin_type").parent().parent().hide();

        $("#id_set_accept_adminid").parent().parent().hide();
    }else{
        $("#id_ass_test_lesson_type").parent().parent().hide();
        $("#id_add").parent().parent().hide();
    }
    if(g_args.cur_page=="test_lesson_plan_list_jx"){
        $("#id_require_assign_flag").val(1);
        //$("#id_require_assign_flag").parent().parent().attr("display","none")
        $("#id_set_accept_adminid").parent().parent().hide();
    }else{
        $("#id_test_lesson_assign").parent().parent().hide();
        //$(".opt-test-lesson-gz").hide();
    }

    $(".opt-test-lesson-gz").on("click",function(){
        var $this = $(this);
        var opt_data = $this.get_opt_data();
        var accept_adminid = opt_data.accept_adminid;
        if(accept_adminid <= 0){
            alert("请先分配教务!");
            return;
        }
        var require_id = opt_data.require_id;
        $.ajax({
      type     :"post",
      url      :"/ss_deal/jw_test_lesson_status_change",
      dataType :"json",
      data     :{
                "require_id"  : require_id,
                "jw_test_lesson_status" : opt_data.jw_test_lesson_status
            },
            success : function(result){
               load_data();
      }
        });

    });
    $("#id_test_lesson_assign").on("click",function(){
        $.ajax({
      type     :"post",
      url      :"/ss_deal/jw_teacher_work_status_change",
      dataType :"json",
      data     :{
            },
            success : function(result){
               // console.log(result);
                if(result.ret==-1){
                    alert(result.info);
                }else{
                    load_data();
                }
            }
        });
    });


    $("#id_add").on("click",function(){
        var $id_userid  = $("<input/>");
        var $id_subject  = $("<select/>");
        var $id_ass_test_lesson_type = $("<select/>");
        var $id_change_reason = $("<textarea />");
        var $id_change_reason_url = $("<div><input class=\"change_reason_url\" id=\"change_reason_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_change_reason\" href=\"javascript:;\">上传</a></span></div>");

        var $green_channel_teacherid = $("<input></input>") ;
        var $stu_request_test_lesson_demand = $("<textarea/>") ;
        var $id_grade_select = $("<select />");
        var $id_stu_request_test_lesson_time =  $("<input/>") ;
        var $id_change_teacher_reason_type = $("<select />");

        $id_stu_request_test_lesson_time.datetimepicker( {
            lang:'ch',
            timepicker:true,
            format: "Y-m-d H:i",
            onChangeDateTime :function(){
            }
        });


        Enum_map.append_option_list("subject",$id_subject,true);
        Enum_map.append_option_list("ass_test_lesson_type",$id_ass_test_lesson_type,true);
        Enum_map.append_option_list("grade", $id_grade_select, true);
        Enum_map.append_option_list("change_teacher_reason_type", $id_change_teacher_reason_type, true);

        $id_subject.on("change",function(){
            $.do_ajax("/ss_deal/get_stu_grade_by_sid",{
                sid : $id_userid.val()
            },function(ret){
                $id_grade_select.val(ret.data);
            });
        });

        $id_ass_test_lesson_type.on("change",function(){
            if($id_ass_test_lesson_type.val() == 2){
                $id_change_teacher_reason_type.parent().parent().css('display','table-row');
                $id_change_reason.parent().parent().css('display','table-row');
                $id_change_reason_url.parent().parent().css('display','table-row');
            }else{
                $id_change_teacher_reason_type.parent().parent().css('display','none');
                $id_change_reason.parent().parent().css('display','none');
                $id_change_reason_url.parent().parent().css('display','none');

                $id_change_teacher_reason_type.val(0);
                $id_change_reason.val('');
                $id_change_reason_url.val('');
            }
        });

        var arr=[
            ["学生",  $id_userid ]  ,
            ["科目",  $id_subject ]  ,
            ["年级 ", $id_grade_select]  ,
            ["分类",  $id_ass_test_lesson_type ]  ,
            ["换老师类型", $id_change_teacher_reason_type ]  ,
            ["申请原因",  $id_change_reason ],
            ["申请原因(图片)",  $id_change_reason_url ],
            ["绿色通道",$green_channel_teacherid],
            ["期望上课时间",$id_stu_request_test_lesson_time],
            ["试听需求",$stu_request_test_lesson_demand],
        ];


        $.show_key_value_table("排课", arr ,[{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                var require_time= $.strtotime($id_stu_request_test_lesson_time.val() );
                var need_start_time=0;
                var now=(new Date()).getTime()/1000;
                var min_date_time="";
                var nowDayOfWeek = (new Date()).getDay();
                if ( (new Date()).getHours() <18 ) {
                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                }else{
                    if( nowDayOfWeek==5 ||  nowDayOfWeek==6){

                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                    }else{
                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                    }
                }
                need_start_time=$.strtotime(min_date_time );

                // alert(require_time);

                if(!require_time){
                    alert("请选择试听时间!");
                    return;
                }

                if (require_time < need_start_time ) {
                    alert("试听时间不能早于 "+ min_date_time );
                  //  $(me).parent().find(".opt-edit").click();
                    return;
                    //申请时间
                }

                $.do_ajax("/ss_deal/ass_add_require_test_lesson",{
                    userid                         : $id_userid.val(),
                    ass_test_lesson_type           : $id_ass_test_lesson_type.val(),
                    subject                        : $id_subject.val(),
                    green_channel_teacherid        : $green_channel_teacherid.val(),
                    stu_request_test_lesson_time   : $id_stu_request_test_lesson_time.val(),
                    stu_request_test_lesson_demand : $stu_request_test_lesson_demand.val(),
                    grade                          : $id_grade_select.val(),
                    change_teacher_reason_type   : $id_change_teacher_reason_type.val(),
                    change_reason : $id_change_reason.val(),
                    change_reason_url : $id_change_reason_url.find("#change_reason_url").val()
                });
            }
        }],function(){
            $.admin_select_user($id_userid,"student");
            $.admin_select_user( $green_channel_teacherid, "teacher");
            $id_change_teacher_reason_type.parent().parent().css('display','none');
            $id_change_reason.parent().parent().css('display','none');
            $id_change_reason_url.parent().parent().css('display','none');
            $.custom_upload_file('id_upload_change_reason',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#change_reason_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

        });
    });


    $(".opt-upload-test-paper").on("click",function(){
        var $this = $(this);
        var opt_data = $this.get_opt_data();
        if (!$this.data("isset_flag") ) {
            $.custom_upload_file(
                $this.attr("id"),
                false,
                function (up, info, file) {
                    var res = $.parseJSON(info);
                    $.do_ajax('/ss_deal/set_stu_test_paper', {
                        'stu_test_paper': res.key,
                        'test_lesson_subject_id': opt_data.test_lesson_subject_id
                    });
                }, null,
                ["pdf", "zip", "rar", "png", "jpg"]);
            $this.data("isset_flag",true);
            alert("再点一下");
        }

    });


    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();

      if(opt_data.accept_flag !=0 ) {
            alert("教务已处理,不能删除!");
            return;
        }
        BootstrapDialog.confirm(
            "要删除[ "+opt_data.nick +":" + opt_data.subject_str +" ] 的听请求?",
            function(val) {
                if (val) {
                    $.do_ajax("/ss_deal/ass_del_require",{
                        require_id : opt_data.require_id
                    }) ;
                }
            }
        );
    });
    $(".opt-accept-seller-require-change").each(function(){
        var opt_data=$(this).get_opt_data();
        if(opt_data.seller_require_change_flag != 1){
            $(this).hide();
        }
    });
    $(".opt-accept-seller-require-change").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_lesson_start_time = $("<input/>");
        id_lesson_start_time.datetimepicker({
        lang:'ch',
        datepicker:true,
        timepicker:true,
        format:'Y-m-d H:i',
        step:30,
          onChangeDateTime :function(){
            }
      });

        var arr=[
            ["目标上课时间", id_lesson_start_time]  ,
        ];
        id_lesson_start_time.val($.DateFormat(opt_data.require_change_lesson_time , "yyyy-MM-dd hh:mm"  ));
        $.show_key_value_table("申请更换试听课时间", arr ,[
            {
                label    : '驳回',
                cssClass : 'btn-danger',
                action   : function(dialog) {
                    $.do_ajax("/ss_deal/refuce_seller_require_change",{
                        require_id : opt_data.require_id
                    }) ;

                }
            },{

                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    var now = (new Date()).getTime()/1000;
                    var start_time = $.strtotime(id_lesson_start_time.val());
                    if ( now > start_time ) {
                        alert("上课时间比现在还小." );
                        return ;
                    }else{
                        $.do_ajax("/ss_deal/done_seller_require_change",{
                            require_id : opt_data.require_id,
                            lesson_start: id_lesson_start_time.val(),
                            userid:opt_data.userid,
                            lessonid: opt_data.lessonid,
                            teacherid:opt_data.teacherid
                        }) ;

                    }
                }
            }]);
    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        // console.log(opt_data);
        var $nick=$("<input/>").val(opt_data.nick );
        var $parent_name=$("<input/>").val(opt_data.parent_name);
        var $school=$("<input/>").val(opt_data.school );
        var $ass_test_lesson_type = $("<select/>");
        var $gender = $("<select/>");
        var $grade = $("<select/>");
        var $subject = $("<select/>");
        var $id_change_teacher_reason_type = $("<select />").val(opt_data.change_teacher_reason_type);
        var $id_change_reason = $("<textarea />").val(opt_data.change_teacher_reason);
        var $id_change_reason_url = $("<div><input class=\"change_reason_url\" id=\"change_reason_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_change_reason\" href=\"javascript:;\">上传</a></span></div>");

        Enum_map.append_option_list("gender",$gender,true);
        Enum_map.append_option_list("grade",$grade,true);
        Enum_map.append_option_list("subject",$subject,true);
        Enum_map.append_option_list("ass_test_lesson_type",$ass_test_lesson_type,true);
        Enum_map.append_option_list("change_teacher_reason_type", $id_change_teacher_reason_type, true);

        $ass_test_lesson_type .val(opt_data.ass_test_lesson_type);
        $gender.val(opt_data.gender);
        $grade.val(opt_data.grade);
        $subject.val(opt_data.subject);
        var $stu_request_test_lesson_time= $("<input  /> ").val(opt_data.stu_request_test_lesson_time ) ;
        var $editionid= $("<select/> ");
        Enum_map.append_option_list("region_version", $editionid, true);

        var $stu_request_test_lesson_demand = $("<textarea/> ").val(opt_data.stu_request_test_lesson_demand );
        $editionid.val( opt_data.editionid );
        $stu_request_test_lesson_time.datetimepicker({
        lang:'ch',
        datepicker:true,
        timepicker:true,
        format:'Y-m-d H:i',
        step:30,
          onChangeDateTime :function(){
            }
      });

        // console.log($stu_request_test_lesson_time);



        $ass_test_lesson_type.on("change",function(){
            if($ass_test_lesson_type.val() == 2){
                $id_change_teacher_reason_type.parent().parent().css('display','table-row');
                $id_change_reason.parent().parent().css('display','table-row');
                $id_change_reason_url.parent().parent().css('display','table-row');
            }else{
                $id_change_teacher_reason_type.parent().parent().css('display','none');
                $id_change_reason.parent().parent().css('display','none');
                $id_change_reason_url.parent().parent().css('display','none');

                $id_change_teacher_reason_type.val(0);
                $id_change_reason.val('');
                $id_change_reason_url.val('');
                $('#change_reason_url').val('')
            }
        });



        var arr=[
            ["电话", opt_data.phone],
            ["年级", opt_data.grade_str ],
            ["科目", opt_data.subject_str ],
            ["分类", $ass_test_lesson_type],
            ["换老师类型", $id_change_teacher_reason_type],
            ["申请原因", $id_change_reason],
            ["申请原因(图片)", $id_change_reason_url],
            ["学生姓名", $nick],
            ["家长姓名", $parent_name],
            ["性别", $gender],
            ["年级", $grade],
            ["科目", $subject],
            ["学校", $school],
            ["教材版本", $editionid],
            ["试听时间", $stu_request_test_lesson_time],
            ["试听需求",  $stu_request_test_lesson_demand ],
        ];

        $.show_key_value_table("编辑", arr, {
            label    : '提交',
            cssClass : 'btn-primary',
            action   : function(dialog) {

                var require_time= $.strtotime( $stu_request_test_lesson_time.val());
                var need_start_time=0;
                var now=(new Date()).getTime()/1000;
                var min_date_time="";
                if ( (new Date()).getHours() <18 ) {
                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                }else{
                    min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                }
                need_start_time=$.strtotime(min_date_time );

                // alert(1);
                // if(!require_time){

                // }

                if (require_time < need_start_time ) {
                    alert("申请时间不能早于 "+ min_date_time );
                    return;
                    //申请时间
                }

                $.do_ajax("/ss_deal/ass_save_user_info", {
                    require_id:opt_data.require_id,
                    userid: opt_data.userid,
                    phone: opt_data.phone,
                    test_lesson_subject_id:opt_data.test_lesson_subject_id,
                    ass_test_lesson_type: $ass_test_lesson_type.val(),
                    stu_nick:$nick.val(),
                    parent_name:$parent_name.val(),
                    gender:$gender.val(),
                    grade:$grade.val(),
                    subject:$subject.val(),
                    editionid:$editionid.val(),
                    school:$school.val(),
                    stu_request_test_lesson_time:$stu_request_test_lesson_time.val(),
                    stu_request_test_lesson_demand:$stu_request_test_lesson_demand.val(),
                    change_teacher_reason_type   : $id_change_teacher_reason_type.val(),
                    change_reason : $id_change_reason.val(),
                    change_reason_url : $id_change_reason_url.find("#change_reason_url").val()

                });
            }
        },function(){
            $.custom_upload_file('id_upload_change_reason',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#change_reason_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

            if(opt_data.ass_test_lesson_type == 2){ // 是换老师类型
                $id_change_teacher_reason_type.parent().parent().css('display','table-row');
                $id_change_reason.parent().parent().css('display','table-row');
                $id_change_reason_url.parent().parent().css('display','table-row');
            }else{
                $id_change_teacher_reason_type.parent().parent().css('display','none');
                $id_change_reason.parent().parent().css('display','none');
                $id_change_reason_url.parent().parent().css('display','none');
            }
            $('#change_reason_url').val(opt_data.change_teacher_reason_img_url);
            $id_change_teacher_reason_type.find('option[value="'+opt_data.change_teacher_reason_type+'"]').attr('selected',1);

        });


    });


    $(".opt-set-teacher-time").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $teacherid= $("<input/>") ;
        var $lesson_start= $("<input/>") ;
        var arr=[
            ["学生", opt_data.nick  ],
            ["电话", opt_data.phone ],
            ["老师", $teacherid],
            ["上课时间", $lesson_start],
        ];
        $teacherid.val(opt_data.teacherid);
        $lesson_start.val(opt_data.lesson_start );
        $lesson_start.datetimepicker({
        lang:'ch',
        datepicker:true,
        timepicker:true,
        format:'Y-m-d H:i',
        step:30,
            onChangeDateTime :function(){
            }

        });

        $.show_key_value_table("换老师,换时间", arr, {
            label    : '提交',
            cssClass : 'btn-primary',
            action   : function(dialog) {

                var do_post=function() {
                    $.do_ajax("/ss_deal/test_lesson_change", {
                        "require_id": opt_data.require_id,
                        "teacherid" : $teacherid.val(),
                        "grade"        : opt_data.grade,
                        "lesson_start" : $lesson_start.val(),
                        "old_teacherid":opt_data.teacherid,
                        "old_lesson_start":opt_data.lesson_time
                    });

                };


                var now=(new Date()).getTime()/1000;
                var start_time=$.strtotime($lesson_start.val() );
                if ( now > start_time  ) {
                    alert("上课时间比现在还小." );
                    return ;
                } else if (now + 5*3600  > start_time   ) {
                    BootstrapDialog.confirm(
                        "上课 时间离现在很近了, 要提交吗?! ",
                        function(val){
                            if(val) {
                                do_post();
                            }
                        });
                }else{
                    do_post();
                }


            }
        },function(){
            $.admin_select_user($teacherid,"teacher" );

        });

    });

    var init_and_reload=function(  set_func ) {
        $('#id_subject').val(-1);
        $('#id_grade').val(-1);
        $('#id_test_lesson_student_status').val(-1);
        $("#id_userid").val(-1);
        $("#id_accept_flag").val(-1);
        $("#id_success_flag").val(-1);
        $("#id_require_admin_type").val(-1);
        $('#id_test_lesson_fail_flag').val(-1);

        var now=new Date();
        var t=now.getTime()/1000;

        set_func(t);
        load_data();
    };


    $("#id_opt_require").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 2,  0, now-7*86400,  now+14*86400);
            $('#id_test_lesson_student_status').val(200);
            $('#id_require_assign_flag').val(0);
            $("#id_seller_require_change_flag").val(-1);
            $("#id_jw_test_lesson_status").val(-1);
        });
    });
    $("#id_opt_require_assign_done").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 2,  0, now-7*86400,  now+14*86400);
            $('#id_test_lesson_student_status').val(200);
            $('#id_require_assign_flag').val(1);
            $("#id_seller_require_change_flag").val(-1);
            $("#id_jw_test_lesson_status").val(0);
        });
    });
    $("#id_opt_require_assign_done_gz").on("click", function() {
        init_and_reload(function(now) {
            $.filed_init_date_range(2, 0, now - 7 * 86400, now + 14 * 86400);
            $('#id_test_lesson_student_status').val(200);
            $('#id_require_assign_flag').val(1);
            $("#id_seller_require_change_flag").val(-1);
            $("#id_jw_test_lesson_status").val(2);
        });
    });





    $("#id_opt_fail_lesson").on("click", function() {

        init_and_reload(function(now) {
            $.filed_init_date_range(4, 0,
                $.strtotime($.DateFormat(now, "yyyy-MM-01")),
                now);
            $("#id_accept_flag").val(1);
            $("#id_success_flag").val(2);
            $("#id_require_admin_type").val(2);
            $("#id_jw_test_lesson_status").val(-1);


        });
    });

    $("#id_opt_seller_require_change").on("click", function() {

        init_and_reload(function(now) {
            $.filed_init_date_range(5, 0, now - 14 * 86400, now);
            $("#id_seller_require_change_flag").val(1);
            $('#id_require_assign_flag').val(-1);
            $("#id_jw_test_lesson_status").val(-1);

        });
    });

    $(" .opt-binding-course-order ").on("click", function() {
        var opt_data = $(this).get_opt_data();
       // alert(opt_data.require_id);

        console.log(opt_data.lessonid);

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

    $(".opt-test_lesson_order_fail").on("click",function(){
        var opt_data=$(this).get_opt_data();
       // alert(opt_data.require_id);
        console.log(opt_data.require_id);

        var $test_lesson_order_fail_flag=$("<select/>");
        var $test_lesson_order_fail_desc =$("<textarea/>");
        var arr=[
            ["上课时间", opt_data.lesson_start ] ,
            ["学生", opt_data.student_nick ] ,
            ["老师", opt_data.teacher_nick] ,
            ["签约失败分类", $test_lesson_order_fail_flag ] ,
            ["签约失败说明", $test_lesson_order_fail_desc ] ,
        ];

      Enum_map.append_option_list("test_lesson_order_fail_flag",$test_lesson_order_fail_flag, true);
        $test_lesson_order_fail_flag.val( opt_data.test_lesson_order_fail_flag);
        $test_lesson_order_fail_desc.val( opt_data.test_lesson_order_fail_desc);

        var dlg=$.show_key_value_table( "签约失败设置", arr , {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax( "/ss_deal/set_order_fail_info", {
                    "require_id" : opt_data.require_id,
                    "test_lesson_order_fail_flag" : $test_lesson_order_fail_flag.val(),
                    "test_lesson_order_fail_desc" : $test_lesson_order_fail_desc.val(),
                });
            }});

    });


    $("#id_set_accept_adminid").on("click", function() {
        var opt_data = $(this).get_opt_data();
        var select_requireid_list = [];

        $(".opt-select-item").each(function() {
            var $item = $(this);
            if ($item.iCheckValue()) {
                select_requireid_list.push($item.data("requireid"));
            }
        });
        var do_post = function(opt_adminid) {
            $.do_ajax(
                '/ss_deal/set_accept_adminid',
                {
                    'require_id_list': JSON.stringify(select_requireid_list),
                    "accept_adminid": opt_adminid
                });
        }

        $.admin_select_user(
            $('#id_admin_revisiterid'),
            "admin", function(val) {
                do_post(val);
            }, false, {
                "main_type": 3
            }
        );

    });

    $("#id_select_all").on("click", function() {
        $(".opt-select-item").iCheck("check");
    });

    $("#id_select_other").on("click", function() {
        $(".opt-select-item").each(function() {
            var $item = $(this);
            if ($item.iCheckValue()) {
                $item.iCheck("uncheck");
            } else {
                $item.iCheck("check");
            }
        });
    });


    $(".opt-teacher-cancel-class-confirm_test").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var lessonid=opt_data.lessonid;
        var lesson_time = opt_data.lesson_time;
        if(lessonid <=0){
            alert("尚未排课");
            return;
        }
        if(opt_data.cancel_time >0){
            alert("已存在该课程记录");
            return;
        }

        BootstrapDialog.show({
            title: '增加老师4小时内取消课程记录',
            message : "确认要增加吗？" ,
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
                    $.do_ajax("/ss_deal/add_cancel_lesson_four_hour_list", {
                        "lessonid":lessonid,
                        "teacherid" : opt_data.teacherid,
                        "lesson_time":lesson_time
                    });
                }
            }]
        });
    });

    $(".opt-teacher-cancel-class-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        var id_record_info = $("<textarea/>");
        var arr = [
            ["原因", id_record_info]
        ];

        $.show_key_value_table("增加老师4小时内取消课程记录", arr, {
            label    :   "确认",
            cssClass :   "btn-warning",
            action   :   function(dialog){
                if(id_record_info.val() == ''){
                    alert("请输入取消的理由");
                    return;
                }
                $.do_ajax('/ajax_deal2/add_cancel_lesson_four_hour_list',{
                    "teacherid" : opt_data.teacherid,
                    "record_info":id_record_info.val(),
                });
            }
        },function(){
        });
    });
    $("#id_grab_lesson").on("click",function(){
        var id_grab_url    = $("<button class='btn btn-danger'>生成抢单链接</button>");
        var id_grab_lesson = $("<button class='btn btn-danger'>添加至抢单库</button>");
        var arr = [
            ["",""],
            ["",id_grab_url],
            ["",id_grab_lesson],
        ];

        $.show_key_value_table("抢单功能",arr,false,function(){
            id_grab_url.on("click",function(){
                var id           = "";
                var requireid    = "";
                var grade        = "";
                var subject      = "";
                var textbook     = "";
                var lesson_time  = "";
                var lesson_info  = "";
                var textbook_str = "";
                var select_num   = 0;

                $(".opt-select-item").each(function(){
                    if($(this).is(":checked")){
                        requireid = $(this).data("requireid");
                        grade=$(this).data("grade");
                        subject=$(this).data("subject");
                        textbook=$(this).data("textbook");
                        lesson_time=$(this).data("lesson_time");
                        if(id==""){
                            id=requireid;
                        }else{
                            id+=(","+requireid);
                        }
                        if(textbook!="未设置"){
                            textbook_str=",教材:"+textbook;
                        }else{
                            textbook_str="";
                        }
                        lesson_info+=grade+subject+textbook_str+",期待上课时间:"+lesson_time+"<br/>";
                        select_num++;
                    }
                });
                if(select_num==0){
                    BootstrapDialog.alert("未选择试听申请!");
                }else{
                    $.do_ajax("/grab_lesson/add_requireids",{
                        "requireids" : id,
                    },function(result){
                        select_time_limit(result,lesson_info);
                    })

                    // $.do_ajax("/common/base64",{
                    //     "text" : id,
                    //     "type" : "encode"
                    // },function(result){
                    //     select_time_limit(result,lesson_info);
                    // })
                }
            });

            id_grab_lesson.on("click",function(){
                var id        = "";
                var requireid = "";
                $(".opt-select-item").each(function(){
                    if($(this).is(":checked")){
                        requireid = $(this).data("requireid");
                        if(id==""){
                            id=requireid;
                        }else{
                            id+=(","+requireid);
                        }
                    }
                });

                if(id==""){
                    BootstrapDialog.alert("请选择要加入的课程!");
                    return false;
                }

                $.do_ajax("/seller_student_new2/grab_test_lesson_plan",{
                    "requireid"   : id,
                    "grab_status" : 1
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            });
        });
    });

    $(".opt-add-grab-list").on("click",function(){
        var data = $(this).get_opt_data();
        var dom  = $(this);
        var info = "";
        var succ_info="";
        var grab_status=0;
        if(data.grab_status==0){
            info        = "确认添加此申请至抢单库么？";
            succ_info   = "已入库";
            grab_status = 1;
        }else if(data.grab_status==1){
            info      = "确认把此申请从抢单库里去除么？";
            succ_info = "未入库";
        }

        BootstrapDialog.show({
          title   : "操作确认",
          message : info,
          buttons : [{
            label  : "返回",
            action : function(dialog) {
                dialog.close();
            }
          }, {
            label: "确认",
            cssClass: "btn-warning",
            action: function(dialog) {
                    $.do_ajax("/seller_student_new2/grab_test_lesson_plan",{
                        "requireid"   : data.require_id,
                        "grab_status" : grab_status
                    },function(result){
                        dialog.close();
                        if(result.ret==0){
                            BootstrapDialog.alert(result.info);
                            dom.parents("td").siblings(".grab_status").html(succ_info);
                            dom.parent().data("grab_status",grab_status);
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    })
            }
          }]
        });
    });

    $("#id_opt_grab_trial_user_info").on("click",function(){
        var id          = "";
        var requireid   = "";
        var grade       = "";
        var subject     = "";
        var textbook    = "";
        var lesson_time = "";
        var lesson_info = "";
        var textbook_str= "";

        $(".opt-select-item").each(function(){
            if($(this).is(":checked")){
                requireid = $(this).data("requireid");
                grade=$(this).data("grade");
                subject=$(this).data("subject");
                textbook=$(this).data("textbook");
                lesson_time=$(this).data("lesson_time");
                if(id==""){
                    id=requireid;
                }else{
                    id+=(","+requireid);
                }
                if(textbook!="未设置"){
                    textbook_str=",教材:"+textbook;
                }else{
                    textbook_str="";
                }
                lesson_info+=grade+subject+textbook_str+",期待上课时间:"+lesson_time+"<br/>";
            }
        });

        if(id==""){
            BootstrapDialog.alert("未选择试听申请!");
        }else{
            $.do_ajax("/grab_lesson/add_requireids",{
                "requireids" : id,
            },function(result){
                select_time_limit(result,lesson_info);
            })

            // $.do_ajax("/common/base64",{
            //     "text" : id,
            //     "type" : "encode"
            // },function(result){
            //     select_time_limit(result,lesson_info);
            // })
        }
    });

    var select_time_limit = function(result,lesson_info){
        var id_time = $("<input />");
        var arr     = [
            ["填写时间(单位:分钟)",id_time],
        ];
        $.show_key_value_table("填写链接有效时长",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                dialog.close();
                var now  = Date.parse(new Date())/1000;
                var time = id_time.val();
                now = now+time*60;
                var url  = "http://www.leo1v1.com/teacher_info/grab_trial_lesson_list?text="+result.data+"&time="+now;

                var alert_info = url+"<br/>"+lesson_info;
                $.ajax({
                    type     :'post',
                    url      : '/grab_lesson/update_lesson_link',
                    dataType : 'json',
                    data     : {
                        'url'       : url,
                        'text'      : result.data,
                        'live_time' : time
                    },
                    success :function(ret){
                        if (ret.ret == 0) {
                            BootstrapDialog.alert(alert_info);
                        } else {
                            alert(ret.info);
                        }
                    }
                });
            }
        },function(){
            id_time.val("60");
        });
    }

   $(".opt-limit-lesson-require").on("click",function(){
       //alert("开发中");
       var opt_data=$(this).get_opt_data();
       // console.log(opt_data.require_id);
       //  alert(opt_data.require_id);
       if(opt_data.test_lesson_student_status != 200){
           alert("非预约未排课状态,不能申请!");
            return;
        }
       if(opt_data.limit_require_flag==1 && opt_data.limit_accept_flag <2){
           alert("已有申请!");
           return;
       }

       var $limit_require_teacherid =$("<input/>");
        if(opt_data.green_channel_teacherid > 0){
            $limit_require_teacherid.val(opt_data.green_channel_teacherid);
        }
        var $limit_require_lesson_start = $("<input  /> ").val(opt_data.stu_request_test_lesson_time ) ;
        var $limit_require_reason = $("<textarea/> ");

        $limit_require_lesson_start.datetimepicker({
            lang:'ch',
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i',
            step:30,
            onChangeDateTime :function(){
            }
        });

        // console.log($stu_request_test_lesson_time);


        var arr=[
            ["老师",$limit_require_teacherid ],
            ["上课时间", $limit_require_lesson_start],
            ["申请理由", $limit_require_reason],
        ];

        $.show_key_value_table("限课特殊申请", arr, {
            label    : '提交',
            cssClass : 'btn-primary',
            action   : function(dialog) {

                $.do_ajax("/ss_deal/set_limit_lesson_require", {
                    "limit_require_teacherid":$limit_require_teacherid.val(),
                    "limit_require_reason":$limit_require_reason.val(),
                    "limit_require_lesson_start" :$limit_require_lesson_start.val(),
                    "require_id":opt_data.require_id,
                    "require_adminid":opt_data.cur_require_adminid,
                    "grade":opt_data.grade,
                    "subject":opt_data.subject,
                    "is_green_flag":opt_data.is_green_flag
                });

            }
        },function(){
            $.admin_select_user( $limit_require_teacherid, "teacher");
        });


    });


    $(".opt-set-limit-require-agree").on("click",function(){
        var opt_data=$(this).get_opt_data();

        BootstrapDialog.confirm(
            "同意该申请?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/ss_deal/set_limit_accept_flag", {
                        "require_id": opt_data.require_id,
                        "limit_accept_flag":1
                    }) ;
                }
            }
        );

    });

    $(".opt-set-limit-require-refuce").on("click",function(){
        var opt_data=$(this).get_opt_data();

        BootstrapDialog.confirm(
            "驳回该申请?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/ss_deal/set_limit_accept_flag", {
                        "require_id": opt_data.require_id,
                        "limit_accept_flag":2
                    }) ;
                }
            }
        );

    });

    $(".opt-match-teacher").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $teacherid    = $("<input/>") ;
        var $lesson_start = $("<input/>") ;
        // alert(opt_data.require_id);
        // alert(opt_data.except_lesson_time );

        $("<div></div>").admin_select_dlg_ajax({
            "width"    : 1200,
            "opt_type" : "list", // or "list"
            "url"      : "/ss_deal/get_teacher_list",
            //其他参数
            "args_ex" : {
                "lesson_time" : opt_data.except_lesson_time,
                "subject"     : opt_data.subject,
                "grade"       : opt_data.grade
            },

            select_primary_field   : "teacherid",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",

            //字段列表
            'field_list' : [
                {
                    title      : "teacherid",
                    width      : 50,
                    field_name : "teacherid"
                },{
                    title      : "姓名",
                    width      : 50,
                    field_name : "realname"
                },{
                    title      : "电话",
                    width      : 50,
                    field_name : "phone"
                },{
                    title      : "邮箱",
                    width      : 50,
                    field_name : "email"
                },{
                    title:"科目",
                    width      : 50,
                    field_name : "subject"
                },{
                    title      : "年级",
                    width      : 50,
                    field_name : "grade"
                },{
                    title      : "剩余试听课数",
                    width      : 50,
                    field_name : "week_left_num"
                },{
                    title      : "教材",
                    width      : 200,
                    field_name : "textbook"
                },{
                    title      : "精选维度",
                    width      : 50,
                    field_name : "fine_dimension"
                }
            ] ,
            //查询列表
            filter_list : [
            ],
            "auto_close" : true,
            "onChange"   : false,
            "onLoadData" : null
        });
    });




    $(".opt-edit-new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var opt_obj=this;
        var click_type=1;

        edit_user_info_new(opt_data,opt_obj,click_type);

    });

    var edit_user_info_new=function(opt_data,opt_obj,click_type){
       // var opt_data=$(this).get_opt_data();
        //var opt_obj=this;
       // alert(opt_data.test_lesson_subject_id);

        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : opt_data.userid ,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
        } ,function(ret){
            var data=ret.data;
            var html_node = $.dlg_need_html_by_id( "id_dlg_post_user_info_new");
            var show_noti_info_flag=false;
            var $note_info=html_node.find(".note-info");
            var note_msg="";
            if (data.test_lesson_count >0 ) {
                show_noti_info_flag=true;
                note_msg="已有试听课:"+data.test_lesson_count +"次" ;
            }

            if (!show_noti_info_flag) {
                $note_info.hide();
            }else{
                $note_info.find("span").html( note_msg);
            }

            if( data.status !=0 ) {
                html_node.find("#id_stu_rev_info").removeClass("btn-primary");
                html_node.find("#id_stu_rev_info").addClass("btn-warning");
            }else{
                html_node.find("#id_stu_rev_info").addClass("btn-primary");
                html_node.find("#id_stu_rev_info").removeClass("btn-warning");
            }
            html_node.find("#id_send_sms").on("click",function(){
                $.do_ajax("/user_deal/get_admin_wx_info",{},function(resp){
                    var data=resp.data;
                    var xing=$.trim(data.name).substr(0,1);
                    var dlg=BootstrapDialog.show({
                        title: "发送信息内容:",
                        message : "您好，我是刚刚联系您的"+xing+"老师 ，如果您还需要申请我们的试听课，请添加一下我的微信："+data.wx_id+"。我们会尽快帮您安排，理优教育服务热线："+data.phone,
                        closable: true,
                        buttons: [{
                            label: '返回',
                            action: function(dialog) {
                                dialog.close();
                            }
                        },{
                            label: '发送',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                $.do_ajax("/user_deal/send_seller_sms_msg", {
                                    "phone":opt_data.phone,
                                    "name":xing,
                                    "wx_id":data.wx_id,
                                    "seller_phone":data.phone,
                                },function( resp){
                                    alert("发送成功");
                                } );
                            }
                        }]
                    });


                    /*
                      BootstrapDialog.show();
                    */

                });
            });

            html_node.find("#id_stu_rev_info") .on("click",function(){
                $(opt_obj).parent().find(".opt-return-back-list").click();
            });
            var id_stu_nick          = html_node.find("#id_stu_nick");
            var id_par_nick          = html_node.find("#id_par_nick");
            var id_grade             = html_node.find("#id_stu_grade");
            var id_gender            = html_node.find("#id_stu_gender");
            var id_address           = html_node.find("#id_stu_addr");
            var id_subject           = html_node.find("#id_stu_subject");
            var id_status            = html_node.find("#id_stu_status");
            var id_seller_student_sub_status = html_node.find("#id_seller_student_sub_status");
            var id_user_desc         = html_node.find("#id_stu_user_desc");
           // var id_revisite_info     = html_node.find("#id_stu_revisite_info");
            var id_has_pad           = html_node.find("#id_stu_has_pad");
            var id_editionid         = html_node.find("#id_stu_editionid");
            var id_school            = html_node.find("#id_stu_school");
            var id_intention_level            = html_node.find("#id_intention_level");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time");
           var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand");
          //  var id_stu_score_info = html_node.find("#id_stu_score_info");
           // var id_stu_character_info = html_node.find("#id_stu_character_info");
           // var id_stu_test_lesson_level = html_node.find("#id_stu_test_lesson_level");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag");
           // var id_stu_request_test_lesson_time_info = html_node.find("#id_stu_request_test_lesson_time_info");
           // var id_stu_request_lesson_time_info = html_node.find("#id_stu_request_lesson_time_info");
            var id_advice_flag = html_node.find("#id_advice_flag");
            var id_academic_goal = html_node.find("#id_academic_goal");
            var id_test_stress = html_node.find("#id_test_stress");
            var id_entrance_school_type = html_node.find("#id_entrance_school_type");
            var id_extra_improvement = html_node.find("#id_extra_improvement");
            var id_habit_remodel = html_node.find("#id_habit_remodel");
            var id_interest_cultivation = html_node.find("#id_interest_cultivation");
            var id_study_habit = html_node.find("#id_study_habit");
            var id_interests_hobbies = html_node.find("#id_interests_hobbies");
            var id_character_type = html_node.find("#id_character_type");
            var id_need_teacher_style = html_node.find("#id_need_teacher_style");
            var id_intention_level = html_node.find("#id_intention_level");
            var id_test_paper = html_node.find("#id_test_paper");
            var id_demand_urgency = html_node.find("#id_demand_urgency");
            var id_quotation_reaction = html_node.find("#id_quotation_reaction");
            var id_revisit_info_new = html_node.find("#id_revisit_info_new");
            if(click_type==1){
                //id_revisit_info_new.hide();
            }

            html_node.find(".upload_test_paper").attr("id","id_upload_test_paper");

            html_node.find("#id_stu_reset_next_revisit_time").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("subject", id_subject, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("boolean", id_advice_flag, true);
          //  Enum_map.append_option_list("test_lesson_level", id_stu_test_lesson_level, true);
            Enum_map.append_option_list("academic_goal", id_academic_goal, true);
            Enum_map.append_option_list("test_stress", id_test_stress, true);
            Enum_map.append_option_list("habit_remodel", id_habit_remodel, true);
            Enum_map.append_option_list("extra_improvement", id_extra_improvement, true);
            Enum_map.append_option_list("entrance_school_type", id_entrance_school_type, true);
            Enum_map.append_option_list("interest_cultivation", id_interest_cultivation, true);
            Enum_map.append_option_list("intention_level", id_intention_level, true);
            Enum_map.append_option_list("demand_urgency", id_demand_urgency, true);
            Enum_map.append_option_list("quotation_reaction", id_quotation_reaction, true);




            id_stu_request_test_lesson_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });

            html_node.find("#id_stu_reset_stu_request_test_lesson_time").on("click",function(){
                id_stu_request_test_lesson_time.val("");
            });

            id_study_habit.data("v",data.study_habit);
            id_study_habit.on("click",function(){
                // var study_habit= data.study_habit;
                var study_habit  = id_study_habit.data("v");
                $.do_ajax("/ss_deal2/get_stu_study_habit_list",{
                    "study_habit" : study_habit
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["study_habit"]  ]);

                        if (this["has_study_habit"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","学习习惯" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_study_habit_name",{
                                "study_habit" : JSON.stringify(select_list)
                            },function(res){
                                id_study_habit.val(res.data);
                                id_study_habit.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_interests_hobbies.data("v",data.interests_and_hobbies);
            id_interests_hobbies.on("click",function(){
                // var interests_hobbies= data.interests_hobbies;
                var interests_hobbies  = id_interests_hobbies.data("v");
                $.do_ajax("/ss_deal2/get_stu_interests_hobbies_list",{
                    "interests_hobbies" : interests_hobbies
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["interests_hobbies"]  ]);

                        if (this["has_interests_hobbies"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","兴趣爱好" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_interests_hobbies_name",{
                                "interests_hobbies" : JSON.stringify(select_list)
                            },function(res){
                                id_interests_hobbies.val(res.data);
                                id_interests_hobbies.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_character_type.data("v",data.character_type);
            id_character_type.on("click",function(){
                // var character_type= data.character_type;
                var character_type  = id_character_type.data("v");
                $.do_ajax("/ss_deal2/get_stu_character_type_list",{
                    "character_type" : character_type
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["character_type"]  ]);

                        if (this["has_character_type"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","性格特点" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_character_type_name",{
                                "character_type" : JSON.stringify(select_list)
                            },function(res){
                                id_character_type.val(res.data);
                                id_character_type.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });

            id_need_teacher_style.data("v",data.need_teacher_style);
            id_need_teacher_style.on("click",function(){
                // var need_teacher_style= data.need_teacher_style;
                var need_teacher_style  = id_need_teacher_style.data("v");
                $.do_ajax("/ss_deal2/get_stu_need_teacher_style_list",{
                    "need_teacher_style" : need_teacher_style
                },function(response){
                    var data_list   = [];
                    var select_list = [];
                    $.each( response.data,function(){
                        data_list.push([this["num"], this["need_teacher_style"]  ]);

                        if (this["has_need_teacher_style"]) {
                            select_list.push (this["num"]) ;
                        }

                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","老师要求" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : select_list,
                        onChange        : function( select_list,dlg) {

                            $.do_ajax("/ss_deal2/get_stu_need_teacher_style_name",{
                                "need_teacher_style" : JSON.stringify(select_list)
                            },function(res){
                                id_need_teacher_style.val(res.data);
                                id_need_teacher_style.data("v",res.data);
                            });

                            dlg.close();
                        }
                    });

                });

            });



            var old_province = data.region;
            if(old_province == ''){
                old_province="选择省（市）";
            }

            var old_city = data.city;
            if(old_city == ''){
                old_city="选择市（区）";
            }
            var old_area = data.area;
            if(old_area == ''){
                old_city="选择区（县）";
            }



            var province = html_node.find("#province");
            var city = html_node.find("#city");
            var area = html_node.find("#area");
            var preProvince = "<option value=\"\">"+old_province+"</option>";
            var preCity = "<option value=\"\">"+old_city+"</option>";
            var preArea = "<option value=\"\">"+old_area+"</option>";

            //初始化
            province.html(preProvince);
            city.html(preCity);
            area.html(preArea);

            //文档加载完毕:即从province_city_select_Info.xml获取数据,成功之后采用
            //func_suc_getXmlProvice进行 省的 解析
            $.ajax({
                type : "GET",
                url : "/province_city_select_Info.xml",
                success : func_suc_getXmlProvice
            });

            //省 下拉选择发生变化触发的事件
            province.change(function() {
                //province.val()  : 返回是每个省对应的下标,序号从0开始
                if (province.val() != "") {
                    if(data.region != html_node.find("#province").find("option:selected").text()){
                        var preCity = "<option value=\"\">选择市（区）</option>";
                        var preArea = "<option value=\"\">选择区（县）</option>";
                    }
                    city.html(preCity);
                    area.html(preArea);

                    //根据下拉得到的省对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlProvice进行省对应的市的解析
                    $.ajax({
                        type : "GET",
                        url : "/province_city_select_Info.xml",
                        success : func_suc_getXmlCity
                    });

                }
            });

            //市 下拉选择发生变化触发的事件
            city.change(function() {
                if(data.city != html_node.find("#city").find("option:selected").text()){
                    var preArea = "<option value=\"\">选择区（县）</option>";
                }

                area.html(preArea);
                $.ajax({
                    type : "GET",
                    url : "/province_city_select_Info.xml",

                    //根据下拉得到的省、市对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlArea进行省对应的市对于的区的解析
                    success : func_suc_getXmlArea
                });
            });

            //区 下拉选择发生变化触发的事件
            area.change(function() {
                var value = province.find("option:selected").text()
                    + city.find("option:selected").text()
                    + area.find("option:selected").text();
                id_address.val(value);
                $("#txtProCity").val(value);
            });

            //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中
            function func_suc_getXmlProvice(xml) {
                //jquery的查找功能
                var sheng = $(xml).find("prov");

                //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中
                sheng.each(function(i) {
                    province.append("<option value=" + i + ">"
                                    + sheng.eq(i).attr("text") + "</option>");
                });
            }

            function func_suc_getXmlCity(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                xml_shi.each(function(j) {
                    city.append("<option  value=" + j + ">"
                                + xml_shi.eq(j).attr("text") + "</option>");
                });
            }

            function func_suc_getXmlArea(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                var city_num = parseInt(city.val());
                var xml_xianqu = xml_shi.eq(city_num).find("county");
                xml_xianqu.each(function(k) {
                    area.append("<option  value=" + k + ">"
                                + xml_xianqu.eq(k).attr("text") + "</option>");
                });
            }


            /*
              array(0,"","未回访" ),
              array(1,"","无效资源" ),
              array(2,"","未接通" ),
              array(3,"","有效-意向A档" ),
              array(4,"","有效-意向B档" ),
              array(5,"","有效-意向C档" ),
              array(6,"","已试听-待跟进" ),
              array(7,"","已试听-未签A档" ),
              array(20,"","已试听-未签B档" ),
              array(21,"","已试听-未签C档" ),
              array(8,"","已试听-已签" ),
              array(9,"test_lesson_report","试听-预约" ),
              array(10,"test_lesson_set_lesson","试听-已排课" ),
              array(11,"","试听-时间待定" ), //,有预约意向，但时间没有确定
              array(12,"","试听-时间确定" ), //
              array(13,"","试听-无法排课" ),
              array(14,"","试听-驳回" ),
              array(15,"","试听-课程取消" ),

            */


            var now=(new Date()).getTime()/1000;

            var status=data.status*1;
            var show_status_list=[];

            var cur_page= g_args.cur_page;

            show_status_list=[];

            /*
              return $this->seller_student_list_ex(0,"0,2");
              return $this->seller_student_list_ex(103,"100,103");
              return $this->seller_student_list_ex(101,"101,102");
              return $this->seller_student_list_ex(110,"110,120");
              return $this->seller_student_list_ex(200);
              return $this->seller_student_list_ex(1);
              return $this->seller_student_list_ex(210);
              return $this->seller_student_list_ex(220);
              return $this->seller_student_list_ex(290);
              return $this->seller_student_list_ex(301, "300,301,302,420");
            */

            if(opt_data.stu_type==1){
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case  60:
                    show_status_list=[ 1,2,61, 100,101,102,103 ];
                    break;
                case 61:
                    show_status_list=[1,2,60,  100, 101,102,103 ];
                    break;

                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[  60,61 ];
                    break;
                }

            }else{
                switch ( opt_data.seller_student_status) {
                case 0:
                case  2:
                    show_status_list=[ 1,2, 100,101,102,103 ];
                    break;
                case 1:
                    show_status_list=[  100, 101,102,103 ];
                    break;
                case 101:
                case  102:
                    show_status_list=[ 100, 101,102,103, 1  ];
                    break;

                case 100:case 103:
                    show_status_list=[ 1, 100, 101,102,103 ];
                    break;

                case 110:case 120:
                    show_status_list=[ 1,100, 101,102,103 ];
                    break;

                case 200:
                    show_status_list=[ ];
                    break;

                case 210:
                    show_status_list=[ 220, 290  ];
                    break;
                case 220: //待开课
                    show_status_list=[ 290,300,301,302 ];
                    break;

                case 290: //待跟进
                    show_status_list=[ 300,301,302 ];
                    break;

                case 300:case 301:case 302:case 420 :   //未签
                    show_status_list=[ 300,301,302,420  ];
                    break;
                case 400:case 410:case 420: //
                    show_status_list=[   ];
                    break;
                }

            }

            show_status_list.push(status);

            Enum_map.append_option_list("seller_student_status", id_status ,true , show_status_list );
            Enum_map.append_option_list("gender", id_gender, true);
            Enum_map.append_option_list("region_version", id_editionid, true);

            id_stu_nick.val(data.stu_nick);
            id_par_nick.val(data.par_nick);
            id_grade.val(data.grade);
            id_gender.val(data.gender);
            id_address.val(data.address);
            id_subject.val(data.subject);
            id_status.val(data.status);
            id_user_desc.val(data.user_desc);
           // id_revisite_info.val(data.revisite_info);
            id_has_pad.val(data.has_pad);
            id_school.val(data.school);
            id_editionid.val(data.editionid);
            id_next_revisit_time.val(data.next_revisit_time);
            html_node.find("#id_class_rank").val(data.class_rank);
            html_node.find("#id_grade_rank").val(data.grade_rank);
            html_node.find("#id_academic_goal").val(data.academic_goal);
            html_node.find("#id_test_stress").val(data.test_stress);
            html_node.find("#id_entrance_school_type").val(data.entrance_school_type);
            html_node.find("#id_interest_cultivation").val(data.interest_cultivation);
            html_node.find("#id_extra_improvement").val(data.extra_improvement);
            html_node.find("#id_habit_remodel").val(data.habit_remodel);
            html_node.find("#id_study_habit").val(data.study_habit);
            html_node.find("#id_interests_hobbies").val(data.interests_and_hobbies);
            html_node.find("#id_character_type").val(data.character_type);
            html_node.find("#id_need_teacher_style").val(data.need_teacher_style);
            html_node.find("#id_intention_level").val(data.intention_level);
            html_node.find("#id_demand_urgency").val(data.demand_urgency);
            html_node.find("#id_quotation_reaction").val(data.quotation_reaction);
            html_node.find("#id_recent_results").val(data.recent_results);
            html_node.find("#id_advice_flag").val(data.advice_flag);
            html_node.find("#id_test_paper").val(data.stu_test_paper);
            if(!data.knowledge_point_location ){
                html_node.find("#id_knowledge_point_location").val(data.stu_request_test_lesson_demand);
            }else{
                html_node.find("#id_knowledge_point_location").val(data.knowledge_point_location);
            }



            var reset_seller_student_status_options=function()  {
                var opt_list=[0];
                var desc_map=g_enum_map["seller_student_sub_status"]["desc_map"];
                var seller_student_status=  parseInt( id_status.val());
                $.each(desc_map, function(k,v){
                    if(k>0 ) {
                        if (  Math.floor(k/1000) == seller_student_status ){
                            opt_list.push(parseInt(k));
                        }
                    }
                });
                id_seller_student_sub_status.html("");
                Enum_map.append_option_list("seller_student_sub_status", id_seller_student_sub_status,true, opt_list );
            };

            reset_seller_student_status_options();
            id_seller_student_sub_status.val(data.seller_student_sub_status);
            id_status.on("change",function(){
                reset_seller_student_status_options();
            });


            id_stu_request_test_lesson_time.val(data.stu_request_test_lesson_time);
            id_stu_request_test_lesson_demand.val(data.stu_request_test_lesson_demand );
           // id_stu_score_info.val(data.stu_score_info);
           // id_stu_test_lesson_level.val(data.stu_test_lesson_level);
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);
           // id_stu_character_info.val(data.stu_character_info);

            id_next_revisit_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });
            var origin=data.origin;
            if (  /bm_/.test(origin) ||
                  /bw_/.test(origin) ||
                  /baidu/.test(origin)
               ) {
                //origin="百度:"+ origin;
                origin="百度";
            }

            var title= '用户信息['+opt_data.phone+':'+opt_data.phone_location+']';
            // if( g_args.account_seller_level >=100  && g_args.account_seller_level<400 ) { //S,A, B
            //     title= title+"-渠道:["+origin+"]";
            // }

            var dlg=BootstrapDialog.show({
                title:  title,
                size: "size-wide",
                message : html_node,
                closable: false,
                buttons: [{
                    label: '返回',
                    action: function(dialog) {
                        dialog.close();
                    }
                },{
                    label: '提交',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        if (  id_seller_student_sub_status.find("option").length>1  && id_seller_student_sub_status.val()=="0" ) {
                            alert("请选择回访状态的子分类");
                            return;
                        }
                        var region = html_node.find("#province").find("option:selected").text();
                        var province = html_node.find("#province").val();
                        var city = html_node.find("#city").find("option:selected").text();
                        var area = html_node.find("#area").find("option:selected").text();
                        // alert(province);
                        if(province==""){
                            region="";
                            city="";
                            area="";
                        }
                        if(html_node.find("#city").val()==""){
                             city="";
                        }
                        if(html_node.find("#area").val()==""){
                            area="";
                        }

                        $.do_ajax("/ss_deal/save_user_info_new",{
                            new_demand_flag   : 1,
                            click_type        : click_type,
                            userid            : opt_data.userid,
                            test_lesson_subject_id : opt_data.test_lesson_subject_id,
                            phone: opt_data.phone,
                            stu_nick      : id_stu_nick.val(),
                            par_nick      : id_par_nick.val(),
                            grade         : id_grade.val(),
                            gender        : id_gender.val(),
                            address       : id_address.val(),
                            subject       : id_subject.val(),
                            seller_student_status : id_status.val(),
                            seller_student_sub_status : id_seller_student_sub_status.val(),
                            user_desc     : id_user_desc.val(),
                           // revisite_info : id_revisite_info.val(),
                            next_revisit_time : id_next_revisit_time.val(),
                            editionid : id_editionid.val(),
                            school: id_school.val(),
                            stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                            stu_request_test_lesson_demand:id_stu_request_test_lesson_demand.val(),
                           // stu_score_info:id_stu_score_info.val(),
                           // stu_test_lesson_level:id_stu_test_lesson_level.val(),
                            stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                          //  stu_character_info:id_stu_character_info.val(),
                          //  stu_request_test_lesson_time_info:id_stu_request_test_lesson_time_info.data("v"),
                          //  stu_request_lesson_time_info:id_stu_request_lesson_time_info.data("v"),
                            has_pad       : id_has_pad.val(),
                            intention_level       : id_intention_level.val(),
                            class_rank: html_node.find("#id_class_rank").val(),
                            grade_rank: html_node.find("#id_grade_rank").val(),
                            academic_goal: html_node.find("#id_academic_goal").val(),
                            test_stress: html_node.find("#id_test_stress").val(),
                            entrance_school_type: html_node.find("#id_entrance_school_type").val(),
                            interest_cultivation: html_node.find("#id_interest_cultivation").val(),
                            extra_improvement : html_node.find("#id_extra_improvement").val(),
                            habit_remodel: html_node.find("#id_habit_remodel").val(),
                            study_habit : html_node.find("#id_study_habit").val(),
                            interests_and_hobbies: html_node.find("#id_interests_hobbies").val(),
                            character_type: html_node.find("#id_character_type").val(),
                            need_teacher_style: html_node.find("#id_need_teacher_style").val(),
                            demand_urgency: html_node.find("#id_demand_urgency").val(),
                            quotation_reaction: html_node.find("#id_quotation_reaction").val(),
                           // knowledge_point_location: html_node.find("#id_knowledge_point_location").val(),
                            recent_results: html_node.find("#id_recent_results").val(),
                            advice_flag: html_node.find("#id_advice_flag").val(),
                            province: province,
                            city: city,
                            area: area,
                            region: region,
                            test_paper: html_node.find("#id_test_paper").val(),
                        });

                    }
                }]
            });


            dlg.getModalDialog().css("width","78%");


            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );
            var th = setTimeout(function(){
                $.custom_upload_file('id_upload_test_paper', false,function (up, info, file) {
                    var res = $.parseJSON(info);
                    console.log(res);
                    id_test_paper.val(res.key);

                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                clearTimeout(th);
            }, 1000);
        });
    }


});
