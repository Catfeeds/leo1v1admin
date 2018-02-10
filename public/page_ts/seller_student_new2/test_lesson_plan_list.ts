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
                                [110,120,200,210,220]);
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
        $(".limit-require-info,.show_seller").show();
    }else{
        $(".show_seller").hide();
        $(".limit-require-info,.show_seller").hide();
        console.log(1111);
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
        if(g_args.account_role_self==1){
            if(g_args.ass_master_flag==1){
                $.wopen('/user_manage/ass_archive?userid=' + opt_data.userid);
            }else{
                $.wopen('/user_manage/ass_archive_ass?userid=' + opt_data.userid);
            }
        }else{
            $.wopen('/stu_manage?sid=' + opt_data.userid);
        }
    });

    $(".opt-set-lesson-new ").on("click",function(){
        BootstrapDialog.alert("请使用新版排课功能!");
        return false;
        //  var opt_data = $(this).get_opt_data();
       //  if(opt_data.jw_test_lesson_status == 2){
       //      alert("请先解除挂载!");
       //      return;
       //  }

       //  if(opt_data.test_lesson_student_status != 200 && opt_data.test_lesson_student_status != 120 ){
       //      alert("非待排课状态，若更换试听课，请取消课程，重新排课!");
       //      return;
       //  }

       // if(opt_data.accept_status == 1){
       //      alert('已确认课程，若更换试听课，请取消课程，重新排课；');
       //      return;
       //  }

       //  var id_teacherid       = $("<input/>");
       //  var id_start_time      = $("<input/>");
       //  var id_top_seller_flag = $("<select />");
       //  Enum_map.append_option_list("boolean",id_top_seller_flag,true);

       //  if(opt_data.teacherid > 0){
       //      id_teacherid.val(opt_data.teacherid);
       //  }else if(opt_data.green_channel_teacherid > 0){
       //      id_teacherid.val(opt_data.green_channel_teacherid);
       //  }

       //  id_start_time.datetimepicker({
       //      lang             : 'ch',
       //      datepicker       : true,
       //      timepicker       : true,
       //      format:'Y-m-d H:i',
       //      step             : 30,
       //      onChangeDateTime : function(){
       //      }
       //  });

       //  var arr = [
       //      ["学生",opt_data.nick]  ,
       //      ["期待时间",opt_data.stu_request_test_lesson_time]  ,
       //      ["老师",id_teacherid ]  ,
       //      ["开始时间",id_start_time ]  ,
       //      ["年级",opt_data.grade_str]  ,
       //      ["科目",opt_data.subject_str]  ,
       //  ];
       //  if(opt_data.is_test_user > 0){ //1 测试用户
       //      $.show_key_value_table("排课", arr ,[
       //      {
       //          label    : '驳回',
       //          cssClass : 'btn-danger',
       //          action   : function(dialog) {
       //              var $input = $("<input style=\"width:180px\"  placeholder=\"驳回理由\"/>");
       //              $.show_input(
       //                  opt_data.nick+" : "+ opt_data.subject_str+ ",要驳回, 不计算排课数?! ",
       //                  "",function(val){
       //                      $.do_ajax("/ss_deal/set_no_accpect",{
       //                          'require_id'       : opt_data.require_id,
       //                          'fail_reason' : val
       //                      });
       //                  }, $input  );
       //              $input.val("未排课,期待时间已到");
       //          }
       //      },{
       //          label    : '------',
       //      },{
       //          label    : '确认',
       //          cssClass : 'btn-warning',
       //          action   : function(dialog) {
       //              var do_post = function() {
       //                  $.do_ajax("/ss_deal/course_set_new",{
       //                      'require_id'   : opt_data.require_id,
       //                      "grade"        : opt_data.grade,
       //                      'teacherid'    : id_teacherid.val(),
       //                      'lesson_start' : id_start_time.val(),
       //                      'top_seller_flag' : opt_data.seller_top_flag
       //                  });
       //              };

       //              var now        = (new Date()).getTime()/1000;
       //              var start_time = $.strtotime(id_start_time.val());
       //              if ( now > start_time ) {
       //                  alert("上课时间比现在还小.");
       //                  return ;
       //              } else if ( now + 5*3600  > start_time ) {
       //                  BootstrapDialog.confirm("上课时间离现在很近了,要提交吗?!",function(val){
       //                      if(val) {
       //                          do_post();
       //                      }
       //                  });
       //              }else{
       //                  do_post();
       //              }
       //          }
       //      }],function(){
       //          $.admin_select_user(id_teacherid,"train_through_teacher");
       //      });
       //  }else{
       //      $.show_key_value_table("排课", arr ,[
       //      {
       //          label    : '驳回',
       //          cssClass : 'btn-danger',
       //          action   : function(dialog) {
       //              var $input = $("<input style=\"width:180px\"  placeholder=\"驳回理由\"/>");
       //              $.show_input(
       //                  opt_data.nick+" : "+ opt_data.subject_str+ ",要驳回, 不计算排课数?! ",
       //                  "",function(val){
       //                      $.do_ajax("/ss_deal/set_no_accpect",{
       //                          'require_id'       : opt_data.require_id,
       //                          'fail_reason' : val
       //                      });
       //                  }, $input  );
       //              $input.val("未排课,期待时间已到");
       //          }
       //      },{
       //          label    : '------',
       //      },{
       //          label    : '确认',
       //          cssClass : 'btn-warning',
       //          action   : function(dialog) {
       //              var do_post = function() {
       //                  $.do_ajax("/ss_deal/course_set_new",{
       //                      'require_id'   : opt_data.require_id,
       //                      "grade"        : opt_data.grade,
       //                      'teacherid'    : id_teacherid.val(),
       //                      'lesson_start' : id_start_time.val(),
       //                      'top_seller_flag' : opt_data.seller_top_flag
       //                  });
       //              };

       //              var now        = (new Date()).getTime()/1000;
       //              var start_time = $.strtotime(id_start_time.val());
       //              if ( now > start_time ) {
       //                  alert("上课时间比现在还小.");
       //                  return ;
       //              } else if ( now + 5*3600  > start_time ) {
       //                  BootstrapDialog.confirm("上课时间离现在很近了,要提交吗?!",function(val){
       //                      if(val) {
       //                          do_post();
       //                      }
       //                  });
       //              }else{
       //                  do_post();
       //              }
       //          }
       //      }],function(){
       //          $.admin_select_user(id_teacherid,"train_through_teacher");
       //      });
       //  }
    });

    $(".opt-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
        console.log(opt_data.require_id);
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
                    "require_id"               : opt_data.require_id ,
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

                if(!require_time){
                    alert("请选择试听时间!");
                    return;
                }

                if (require_time < need_start_time ) {
                    alert("试听时间不能早于 "+ min_date_time );
                    return;
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
            if(g_args.account_role_self==12){
                $.admin_select_user($id_userid,"student");
            }else{
                $.admin_select_user($id_userid,"student_ass");
            }
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
        var opt_data                       = $(this).get_opt_data();
        var $teacherid                     = $("<input/>") ;
        var $lesson_start                  = $("<input/>") ;
        var $id_change_teacher_reason_type = $("<select />");
        var $id_change_reason              = $("<textarea />");
        var $id_change_reason_url          = $("<div><input class=\"change_reason_url\" id=\"change_reason_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_change_reason\" href=\"javascript:;\">上传</a></span></div>");

        if(opt_data.accept_status == 1){
            BootstrapDialog.alert('已确认课程，若更换试听课，请取消课程，重新排课');
            return;
        }

        var arr=[
            ["学生", opt_data.nick  ],
            ["电话", opt_data.phone ],
            ["老师", $teacherid],
            ["上课时间", $lesson_start],
            // ["换老师类型",$id_change_teacher_reason_type],
            // ["换老师原因",$id_change_reason],
            // ["换老师原因图片",$id_change_reason_url],
        ];
        $teacherid.val(opt_data.teacherid);
        $lesson_start.val(opt_data.lesson_start);

        Enum_map.append_option_list("change_teacher_reason_type", $id_change_teacher_reason_type, true);
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
                        "require_id"       : opt_data.require_id,
                        "teacherid"        : $teacherid.val(),
                        "grade"            : opt_data.grade,
                        "lesson_start"     : $lesson_start.val(),
                        "old_teacherid"    : opt_data.teacherid,
                        "old_lesson_start" : opt_data.lesson_time,
                        // "change_teacher_reason_type" : $id_change_teacher_reason_type.val(),
                        // "change_reason" : $id_change_reason.val(),
                        // "change_reason_url" : $id_change_reason_url.find("#change_reason_url").val()
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
            $.custom_upload_file('id_upload_change_reason',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#change_reason_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
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
        BootstrapDialog.alert("功能暂停使用!");
        return false;
        // var opt_data=$(this).get_opt_data();
        // var lessonid=opt_data.lessonid;
        // var lesson_time = opt_data.lesson_time;
        // if(lessonid <=0){
        //     alert("尚未排课");
        //     return;
        // }
        // if(opt_data.cancel_time >0){
        //     alert("已存在该课程记录");
        //     return;
        // }

        // BootstrapDialog.show({
        //     title: '增加老师4小时内取消课程记录',
        //     message : "确认要增加吗？" ,
        //     closable: false,
        //     buttons: [{
        //         label: '返回',
        //         action: function(dialog) {
        //             dialog.close();
        //         }
        //     }, {
        //         label: '确认',
        //         cssClass: 'btn-warning',
        //         action: function(dialog) {
        //             $.do_ajax("/ss_deal/add_cancel_lesson_four_hour_list", {
        //                 "lessonid":lessonid,
        //                 "teacherid" : opt_data.teacherid,
        //                 "lesson_time":lesson_time
        //             });
        //         }
        //     }]
        // });
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
                        if(result.ret==-1){
                            BootstrapDialog.alert(result.info);
                        }else{
                            select_time_limit(result,lesson_info);
                        }
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
        BootstrapDialog.alert("功能关闭!");
        return false;
        // var data = $(this).get_opt_data();
        // var dom  = $(this);
        // var info = "";
        // var succ_info="";
        // var grab_status=0;
        // if(data.grab_status==0){
        //     info        = "确认添加此申请至抢单库么？";
        //     succ_info   = "已入库";
        //     grab_status = 1;
        // }else if(data.grab_status==1){
        //     info      = "确认把此申请从抢单库里去除么？";
        //     succ_info = "未入库";
        // }

        // BootstrapDialog.show({
        //     title   : "操作确认",
        //     message : info,
        //     buttons : [{
        //         label  : "返回",
        //         action : function(dialog) {
        //             dialog.close();
        //         }
        //     }, {
        //         label: "确认",
        //         cssClass: "btn-warning",
        //         action: function(dialog) {
        //             $.do_ajax("/seller_student_new2/grab_test_lesson_plan",{
        //                 "requireid"   : data.require_id,
        //                 "grab_status" : grab_status
        //             },function(result){
        //                 dialog.close();
        //                 if(result.ret==0){
        //                     BootstrapDialog.alert(result.info);
        //                     dom.parents("td").siblings(".grab_status").html(succ_info);
        //                     dom.parent().data("grab_status",grab_status);
        //                 }else{
        //                     BootstrapDialog.alert(result.info);
        //                 }
        //             })
        //         }
        //     }]
        // });
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
        if(g_adminid != opt_data.limit_require_send_adminid && g_adminid !=478){
            alert("您无权处置!");
            return;
        }

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
        if(g_adminid != opt_data.limit_require_send_adminid && g_adminid !=478){
            alert("您无权处置!");
            return;
        }


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
        alert("暂停使用,请用其他方式查找老师,谢谢!");
        return;
        // var opt_data = $(this).get_opt_data();
        // var $teacherid    = $("<input/>") ;
        // var $lesson_start = $("<input/>") ;
        // // alert(opt_data.require_id);
        // // alert(opt_data.except_lesson_time );

        // $("<div></div>").admin_select_dlg_ajax({
        //     "width"    : 1200,
        //     "opt_type" : "list", // or "list"
        //     "url"      : "/ss_deal/get_teacher_list",
        //     //其他参数
        //     "args_ex" : {
        //         "lesson_time" : opt_data.except_lesson_time,
        //         "subject"     : opt_data.subject,
        //         "grade"       : opt_data.grade
        //     },

        //     select_primary_field   : "teacherid",
        //     select_display         : "package_name",
        //     select_no_select_value : 0,
        //     select_no_select_title : "[未设置]",

        //     //字段列表
        //     'field_list' : [
        //         {
        //             title      : "teacherid",
        //             width      : 50,
        //             field_name : "teacherid"
        //         },{
        //             title      : "姓名",
        //             width      : 50,
        //             field_name : "realname"
        //         },{
        //             title      : "电话",
        //             width      : 50,
        //             field_name : "phone"
        //         },{
        //             title      : "邮箱",
        //             width      : 50,
        //             field_name : "email"
        //         },{
        //             title:"科目",
        //             width      : 50,
        //             field_name : "subject"
        //         },{
        //             title      : "年级",
        //             width      : 50,
        //             field_name : "grade"
        //         },{
        //             title      : "剩余试听课数",
        //             width      : 50,
        //             field_name : "week_left_num"
        //         },{
        //             title      : "教材",
        //             width      : 200,
        //             field_name : "textbook"
        //         },{
        //             title      : "精选维度",
        //             width      : 50,
        //             field_name : "fine_dimension"
        //         },{
        //             title      : "地区",
        //             width      : 50,
        //             field_name : "address"
        //         }

        //     ] ,
        //     //查询列表
        //     filter_list : [
        //     ],
        //     "auto_close" : true,
        //     "onChange"   : false,
        //     "onLoadData" : null
        // });
    });




    $("#id_add_new").on("click",function(){
        edit_user_info_new_tow();
    });

    var edit_user_info_new_tow=function(){
        var html_node           = $.dlg_need_html_by_id( "id_dlg_post_user_info_new_two");
        var show_noti_info_flag = false;
        var id_userid           = html_node.find("#id_stu_nick_new");//学生姓名
        $.admin_select_user(id_userid,"student_ass");
        var id_par_nick         = html_node.find("#id_par_nick_new");//家长姓名
        var id_gender           = html_node.find("#id_stu_gender_new");//学生性别
        Enum_map.append_option_list("gender", id_gender, true);
        var id_grade            = html_node.find("#id_stu_grade_new");//学生年级
        Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
        var id_subject          = html_node.find("#id_stu_subject_new");//学生科目
        Enum_map.append_option_list("subject", id_subject, true);
        var id_school           = html_node.find("#id_stu_school_new");//在读学校
        var id_editionid        = html_node.find("#id_stu_editionid_new");//学生教材
        var province            = html_node.find("#province");//学生省
        var city                = html_node.find("#city");//学生市
        var area                = html_node.find("#area");//学生区
        var preProvince         = "<option value=\"\">"+"选择省（市）"+"</option>";
        var preCity             = "<option value=\"\">"+"选择市（区）"+"</option>";
        var preArea             = "<option value=\"\">"+"选择区（县）"+"</option>";
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
            if (province.val() != "") {
                var preCity = "<option value=\"\">选择市（区）</option>";
                var preArea = "<option value=\"\">选择区（县）</option>";
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
            var preArea = "<option value=\"\">选择区（县）</option>";
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
        var id_recent_results = html_node.find("#id_recent_results_new");//近期成绩
        var id_advice_flag = html_node.find("#id_advice_flag_new");//是否进步
        var id_class_rank = html_node.find("#id_class_rank_new");//班级排名
        var id_grade_rank = html_node.find("#id_grade_rank_new");//年级排名
        var id_academic_goal = html_node.find("#id_academic_goal_new");//升学目标
        var id_study_habit = html_node.find("#id_study_habit_new");//学习习惯
        id_study_habit.on("click",function(){
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
        var id_interests_hobbies = html_node.find("#id_interests_hobbies_new");//兴趣爱好
        id_interests_hobbies.on("click",function(){
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
        var id_character_type = html_node.find("#id_character_type_new");//性格特点
        id_character_type.on("click",function(){
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
        var id_need_teacher_style = html_node.find("#id_need_teacher_style_new");//老师要求
        id_need_teacher_style.on("click",function(){
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
        var tea_province = html_node.find("#tea_province");//老师所在省
        var tea_city = html_node.find("#tea_city");//老师所在市
        var tea_area = html_node.find("#tea_area");//老师所在区
        var tea_preProvince = "<option value=\"\">"+"选择省（市）"+"</option>";
        var tea_preCity = "<option value=\"\">"+"选择市（区）"+"</option>";
        var tea_preArea = "<option value=\"\">"+"选择区（县）"+"</option>";
        //初始化
        tea_province.html(tea_preProvince);
        tea_city.html(tea_preCity);
        tea_area.html(tea_preArea);
        //文档加载完毕:即从province_city_select_Info.xml获取数据,成功之后采用
        //func_suc_getXmlProvice进行 省的 解析
        $.ajax({
            type : "GET",
            url : "/province_city_select_Info.xml",
            success : tea_func_suc_getXmlProvice
        });
        //省 下拉选择发生变化触发的事件
        tea_province.change(function() {
            if (tea_province.val() != "") {
                var tea_preCity = "<option value=\"\">选择市（区）</option>";
                var tea_preArea = "<option value=\"\">选择区（县）</option>";
                tea_city.html(tea_preCity);
                tea_area.html(tea_preArea);
                //根据下拉得到的省对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                //func_suc_getXmlProvice进行省对应的市的解析
                $.ajax({
                    type : "GET",
                    url : "/province_city_select_Info.xml",
                    success : tea_func_suc_getXmlCity
                });

            }
        });
        //市 下拉选择发生变化触发的事件
        tea_city.change(function() {
            var tea_preArea = "<option value=\"\">选择区（县）</option>";
            tea_area.html(tea_preArea);
            $.ajax({
                type : "GET",
                url : "/province_city_select_Info.xml",
                //根据下拉得到的省、市对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                //func_suc_getXmlArea进行省对应的市对于的区的解析
                success : tea_func_suc_getXmlArea
            });
        });
        //区 下拉选择发生变化触发的事件
        tea_area.change(function() {
            var tea_value = tea_province.find("option:selected").text()
                + tea_city.find("option:selected").text()
                + tea_area.find("option:selected").text();
            $("#txtProCity").val(tea_value);
        });
        //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中
        function tea_func_suc_getXmlProvice(xml) {
            //jquery的查找功能
            var sheng = $(xml).find("prov");
            //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中
            sheng.each(function(i) {
                tea_province.append("<option value=" + i + ">"
                                    + sheng.eq(i).attr("text") + "</option>");
            });
        }
        function tea_func_suc_getXmlCity(xml) {
            var xml_sheng = $(xml).find("prov");
            var pro_num = parseInt(tea_province.val());
            var xml_shi = xml_sheng.eq(pro_num).find("city");
            xml_shi.each(function(j) {
                tea_city.append("<option  value=" + j + ">"
                                + xml_shi.eq(j).attr("text") + "</option>");
            });
        }
        function tea_func_suc_getXmlArea(xml) {
            var xml_sheng = $(xml).find("prov");
            var pro_num = parseInt(tea_province.val());
            var xml_shi = xml_sheng.eq(pro_num).find("city");
            var city_num = parseInt(tea_city.val());
            var xml_xianqu = xml_shi.eq(city_num).find("county");
            xml_xianqu.each(function(k) {
                tea_area.append("<option  value=" + k + ">"
                                + xml_xianqu.eq(k).attr("text") + "</option>");
            });
        }
        var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand_new");//试听需求
        var id_intention_level            = html_node.find("#id_intention_level_new");//上课意向
        var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time_new");//试听时间
        html_node.find("#id_stu_reset_stu_request_test_lesson_time_new").on("click",function(){
            id_stu_request_test_lesson_time.val("");
        });
        id_stu_request_test_lesson_time.datetimepicker( {
            lang:'ch',
            timepicker:true,
            format: "Y-m-d H:i",
            onChangeDateTime :function(){
            }
        });
        var id_test_paper = html_node.find("#id_test_paper_new");//上传试卷
        html_node.find(".upload_test_paper").attr("id","id_upload_test_paper");
        var id_test_stress = html_node.find("#id_test_stress_new");//应试压力
        var id_entrance_school_type = html_node.find("#id_entrance_school_type_new");//升学学校要求
        var id_interest_cultivation = html_node.find("#id_interest_cultivation_new");//趣味培养
        var id_extra_improvement = html_node.find("#id_extra_improvement_new");//课外提高
        var id_habit_remodel = html_node.find("#id_habit_remodel_new");//习惯重塑
        var id_ass_test_lesson_type = html_node.find("#id_ass_test_lesson_type_new");//分类
        Enum_map.append_option_list("ass_test_lesson_type",id_ass_test_lesson_type,true);
        var id_change_teacher_reason_type = html_node.find("#id_change_teacher_reason_type_new");//换老师类型
        Enum_map.append_option_list("change_teacher_reason_type",id_change_teacher_reason_type,true);
        var id_change_reason_url = html_node.find("#id_change_reason_url_new");//换老师原因图片
        html_node.find(".upload_change_reason_url").attr("id","id_upload_change_reason_url");
        var id_green_channel_teacherid = html_node.find("#id_green_channel_teacherid_new");//绿色通道
        $.admin_select_user(id_green_channel_teacherid,"teacher");
        var id_change_reason = html_node.find("#id_change_reason_new");//换老师原因
        var id_learning_situation = html_node.find("#id_learning_situation_new");//学情反馈

        Enum_map.append_option_list("boolean", id_advice_flag, true);
        Enum_map.append_option_list("academic_goal", id_academic_goal, true);
        Enum_map.append_option_list("test_stress", id_test_stress, true);
        Enum_map.append_option_list("habit_remodel", id_habit_remodel, true);
        Enum_map.append_option_list("extra_improvement", id_extra_improvement, true);
        Enum_map.append_option_list("entrance_school_type", id_entrance_school_type, true);
        Enum_map.append_option_list("interest_cultivation", id_interest_cultivation, true);
        Enum_map.append_option_list("intention_level", id_intention_level, true);
        Enum_map.append_option_list("region_version", id_editionid, true);

        id_ass_test_lesson_type.on("change",function(){
            if(id_ass_test_lesson_type.val() == 2){
                id_change_teacher_reason_type.parent().parent().css('display','table-row');
                id_change_reason.parent().parent().css('display','table-row');
                id_change_reason_url.parent().parent().css('display','table-row');
            }else{
                id_change_teacher_reason_type.parent().parent().css('display','none');
                id_change_reason.parent().parent().css('display','none');
                id_change_reason_url.parent().parent().css('display','none');

                id_change_teacher_reason_type.val(0);
                id_change_reason.val('');
                id_change_reason_url.val('');
            }
        });

        var title= '添加试听申请';
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
                    var region = html_node.find("#province").find("option:selected").text();
                    var province = html_node.find("#province").val();
                    var city = html_node.find("#city").find("option:selected").text();
                    var area = html_node.find("#area").find("option:selected").text();
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
                    var tea_province = html_node.find("#tea_province").find("option:selected").text();
                    var tea_city = html_node.find("#tea_city").find("option:selected").text();
                    var tea_area = html_node.find("#tea_area").find("option:selected").text();
                    if(tea_province==""){
                        tea_city="";
                        tea_area="";
                    }
                    if(html_node.find("#tea_city").val()==""){
                        tea_city="";
                    }
                    if(html_node.find("#tea_area").val()==""){
                        tea_area="";
                    }
                    var require_time= $.strtotime(id_stu_request_test_lesson_time.val());
                    var need_start_time=0;
                    var now=(new Date()).getTime()/1000;
                    var min_date_time="";
                    var nowDayOfWeek = (new Date()).getDay();
                    if ((new Date()).getHours() <18 ) {
                        min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                    }else{
                        if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                        }else{
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                        }
                    }
                    need_start_time=$.strtotime(min_date_time );
                    if(!require_time){
                        alert("请选择试听时间!");
                        return;
                    }
                    if (require_time < need_start_time ) {
                        alert("试听时间不能早于 "+ min_date_time );
                        return;
                    }
                    $.do_ajax("/ss_deal2/ass_add_require_test_lesson",{
                        'userid':id_userid.val(),
                        'parent_name':id_par_nick.val(),
                        'gender':id_gender.val(),
                        'grade':id_grade.val(),
                        'subject':id_subject.val(),
                        'school':id_school.val(),
                        'editionid':id_editionid.val(),
                        'province':province,
                        'city':city,
                        'area':area,
                        'region':region,
                        'recent_results':id_recent_results.val(),
                        'advice_flag':id_advice_flag.val(),
                        'class_rank':id_class_rank.val(),
                        'grade_rank':id_grade_rank.val(),
                        'academic_goal':id_academic_goal.val(),
                        'study_habit':id_study_habit.val(),
                        'interests_and_hobbies':id_interests_hobbies.val(),
                        'character_type':id_character_type.val(),
                        'need_teacher_style':id_need_teacher_style.val(),
                        'tea_province':tea_province,
                        'tea_city':tea_city,
                        'tea_area':tea_area,
                        'stu_request_test_lesson_demand':id_stu_request_test_lesson_demand.val(),
                        'intention_level':id_intention_level.val(),
                        'stu_request_test_lesson_time':id_stu_request_test_lesson_time.val(),
                        'test_paper':id_test_paper.val(),
                        'test_stress':id_test_stress.val(),
                        'entrance_school_type':id_entrance_school_type.val(),
                        'interest_cultivation':id_interest_cultivation.val(),
                        'extra_improvement':id_extra_improvement.val(),
                        'habit_remodel':id_habit_remodel.val(),
                        'ass_test_lesson_type':id_ass_test_lesson_type.val(),
                        'change_teacher_reason_type':id_change_teacher_reason_type.val(),
                        'change_reason_url':id_change_reason_url.val(),
                        'green_channel_teacherid':id_green_channel_teacherid.val(),
                        'change_reason':id_change_reason.val(),
                        'learning_situation':id_learning_situation.val(),
                        'new_demand_flag':1,
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
            $.custom_upload_file('id_upload_change_reason_url',true,function (up, info, file) {
                var res = $.parseJSON(info);
                console.log(res);
                id_change_reason_url.val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
            $.custom_upload_file('id_upload_test_paper', false,function (up, info, file) {
                var res = $.parseJSON(info);
                console.log(res);
                id_test_paper.val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
            clearTimeout(th);
        }, 1000);
    }



    $(".opt-edit-new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var opt_obj=this;
        var click_type=1;

        edit_user_info_new(opt_data,opt_obj,click_type);
    });

    var edit_user_info_new=function(opt_data,opt_obj,click_type){
        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : opt_data.userid ,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
            "require_id" : opt_data.require_id,
        } ,function(ret){
            var data=ret.data;
            var html_node           = $.dlg_need_html_by_id( "id_dlg_post_user_info_new");
            var show_noti_info_flag = false;
            var id_userid           = html_node.find("#id_stu_nick");//学生姓名
            id_userid.val(opt_data.userid);
            $.admin_select_user(id_userid,"student");
            var id_par_nick         = html_node.find("#id_par_nick");//家长姓名
            id_par_nick.val(data.par_nick);
            var id_gender           = html_node.find("#id_stu_gender");//学生性别
            Enum_map.append_option_list("gender", id_gender, true);
            id_gender.val(data.gender);
            var id_grade            = html_node.find("#id_stu_grade");//学生年级
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            id_grade.val(data.grade);
            var id_subject          = html_node.find("#id_stu_subject");//学生科目
            Enum_map.append_option_list("subject", id_subject, true);
            id_subject.val(data.subject);
            var id_school           = html_node.find("#id_stu_school");//在读学校
            id_school.val(data.school);
            var id_editionid        = html_node.find("#id_stu_editionid");//学生教材
            Enum_map.append_option_list("region_version", id_editionid, true);
            id_editionid.val(data.editionid);
            var id_learning_situation        = html_node.find("#id_learning_situation");//学生教材
            id_learning_situation.val(data.learning_situation);

            var old_province = data.region;
            var province_val = data.province;
            var city_val = data.city;
            var area_val = data.area;
            var region_val = data.region;
            if(old_province == ''){
                old_province="选择省（市）";
                province_val = 0;
                city_val = '';
                area_val = '';
                region_val = '';
            }
            var old_city = data.city;
            if(old_city == ''){
                old_city="选择市（区）";
                city_val = '';
                area_val = '';
            }
            var old_area = data.area;
            if(old_area == ''){
                old_area="选择区（县）";
                area_val = '';
            }
            var province            = html_node.find("#province_new");//学生省
            var city                = html_node.find("#city_new");//学生市
            var area                = html_node.find("#area_new");//学生区
            var preProvince         = "<option value=\"\">"+old_province+"</option>";
            var preCity             = "<option value=\"\">"+old_city+"</option>";
            var preArea             = "<option value=\"\">"+old_area+"</option>";
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
                if (province.val() != "") {
                    var preCity = "<option value=\"\">选择市（区）</option>";
                    var preArea = "<option value=\"\">选择区（县）</option>";
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
                province_val = html_node.find("#province_new").val();
                region_val = html_node.find("#province_new").find("option:selected").text();
                city_val = '';
                area_val = '';
            });
            //市 下拉选择发生变化触发的事件
            city.change(function() {
                var preArea = "<option value=\"\">选择区（县）</option>";
                area.html(preArea);
                $.ajax({
                    type : "GET",
                    url : "/province_city_select_Info.xml",
                    //根据下拉得到的省、市对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlArea进行省对应的市对于的区的解析
                    success : func_suc_getXmlArea
                });
                city_val = html_node.find("#city_new").find("option:selected").text();
                area_val = '';
            });
            //区 下拉选择发生变化触发的事件
            area.change(function() {
                var value = province.find("option:selected").text()
                    + city.find("option:selected").text()
                    + area.find("option:selected").text();
                $("#txtProCity").val(value);
                area_val = html_node.find("#area_new").find("option:selected").text();
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
            var id_recent_results = html_node.find("#id_recent_results");//近期成绩
            id_recent_results.val(data.recent_results);
            var id_advice_flag = html_node.find("#id_advice_flag");//是否进步
            Enum_map.append_option_list("boolean", id_advice_flag, true);
            id_advice_flag.val(data.advice_flag);
            var id_class_rank = html_node.find("#id_class_rank");//班级排名
            id_class_rank.val(data.class_rank);
            var id_grade_rank = html_node.find("#id_grade_rank");//年级排名
            id_grade_rank.val(data.grade_rank);
            var id_academic_goal = html_node.find("#id_academic_goal");//升学目标
            Enum_map.append_option_list("academic_goal", id_academic_goal, true);
            id_academic_goal.val(data.academic_goal);
            var id_study_habit = html_node.find("#id_study_habit");//学习习惯
            id_study_habit.val(data.study_habit);
            id_study_habit.on("click",function(){
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
            var id_interests_hobbies = html_node.find("#id_interests_hobbies");//兴趣爱好
            id_interests_hobbies.val(data.interests_and_hobbies);
            id_interests_hobbies.on("click",function(){
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
            var id_character_type = html_node.find("#id_character_type");//性格特点
            id_character_type.val(data.character_type);
            id_character_type.on("click",function(){
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
            var id_need_teacher_style = html_node.find("#id_need_teacher_style");//老师要求
            id_need_teacher_style.val(data.need_teacher_style);
            id_need_teacher_style.on("click",function(){
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

            var tea_old_province = data.tea_province;
            var tea_province_val = data.tea_province;
            var tea_city_val = data.tea_city;
            var tea_area_val = data.tea_area;
            if(tea_old_province == ''){
                tea_old_province="选择省（市）";
                tea_province_val = 0;
            }
            var tea_old_city = data.tea_city;
            if(tea_old_city == ''){
                tea_old_city="选择市（区）";
                tea_city_val = '';
            }
            var tea_old_area = data.tea_area;
            if(tea_old_area == ''){
                tea_old_area="选择区（县）";
                tea_area_val = '';
            }
            var tea_province = html_node.find("#tea_province_new");//老师所在省
            var tea_city = html_node.find("#tea_city_new");//老师所在市
            var tea_area = html_node.find("#tea_area_new");//老师所在区
            var tea_preProvince = "<option value=\"\">"+tea_old_province+"</option>";
            var tea_preCity = "<option value=\"\">"+tea_old_city+"</option>";
            var tea_preArea = "<option value=\"\">"+tea_old_area+"</option>";
            //初始化
            tea_province.html(tea_preProvince);
            tea_city.html(tea_preCity);
            tea_area.html(tea_preArea);
            //文档加载完毕:即从province_city_select_Info.xml获取数据,成功之后采用
            //func_suc_getXmlProvice进行 省的 解析
            $.ajax({
                type : "GET",
                url : "/province_city_select_Info.xml",
                success : tea_func_suc_getXmlProvice
            });
            //省 下拉选择发生变化触发的事件
            tea_province.change(function() {
                if(tea_province.val() != ""){
                    var tea_preCity = "<option value=\"\">选择市（区）</option>";
                    var tea_preArea = "<option value=\"\">选择区（县）</option>";
                    tea_city.html(tea_preCity);
                    tea_area.html(tea_preArea);
                    //根据下拉得到的省对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlProvice进行省对应的市的解析
                    $.ajax({
                        type : "GET",
                        url : "/province_city_select_Info.xml",
                        success : tea_func_suc_getXmlCity
                    });
                }
                tea_province_val = html_node.find("#tea_province_new").find("option:selected").text();
                tea_city_val = '';
                tea_area_val = '';
            });
            //市 下拉选择发生变化触发的事件
            tea_city.change(function() {
                var tea_preArea = "<option value=\"\">选择区（县）</option>";
                tea_area.html(tea_preArea);
                $.ajax({
                    type : "GET",
                    url : "/province_city_select_Info.xml",
                    //根据下拉得到的省、市对于的下标序号,动态从从province_city_select_Info.xml获取数据,成功之后采用
                    //func_suc_getXmlArea进行省对应的市对于的区的解析
                    success : tea_func_suc_getXmlArea
                });
                tea_city_val = html_node.find("#tea_city_new").find("option:selected").text();
                tea_area_val = '';
            });
            //区 下拉选择发生变化触发的事件
            tea_area.change(function() {
                var tea_value = tea_province.find("option:selected").text()
                    + tea_city.find("option:selected").text()
                    + tea_area.find("option:selected").text();
                $("#txtProCity").val(tea_value);
                tea_area_val = html_node.find("#tea_area_new").find("option:selected").text();
            });
            //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中
            function tea_func_suc_getXmlProvice(xml) {
                //jquery的查找功能
                var sheng = $(xml).find("prov");
                //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中
                sheng.each(function(i) {
                    tea_province.append("<option value=" + i + ">"
                                        + sheng.eq(i).attr("text") + "</option>");
                });
            }
            function tea_func_suc_getXmlCity(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(tea_province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                xml_shi.each(function(j) {
                    tea_city.append("<option  value=" + j + ">"
                                    + xml_shi.eq(j).attr("text") + "</option>");
                });
            }
            function tea_func_suc_getXmlArea(xml) {
                var xml_sheng = $(xml).find("prov");
                var pro_num = parseInt(tea_province.val());
                var xml_shi = xml_sheng.eq(pro_num).find("city");
                var city_num = parseInt(tea_city.val());
                var xml_xianqu = xml_shi.eq(city_num).find("county");
                xml_xianqu.each(function(k) {
                    tea_area.append("<option  value=" + k + ">"
                                    + xml_xianqu.eq(k).attr("text") + "</option>");
                });
            }
            var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand");//试听需求
            id_stu_request_test_lesson_demand.val(data.stu_request_test_lesson_demand);
            var id_intention_level            = html_node.find("#id_intention_level");//上课意向
            Enum_map.append_option_list("intention_level", id_intention_level, true);
            id_intention_level.val(data.intention_level);
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time");//试听时间
            id_stu_request_test_lesson_time.val(data.stu_request_test_lesson_time);
            html_node.find("#id_stu_reset_stu_request_test_lesson_time").on("click",function(){
                id_stu_request_test_lesson_time.val("");
            });
            id_stu_request_test_lesson_time.datetimepicker( {
                lang:'ch',
                timepicker:true,
                format: "Y-m-d H:i",
                onChangeDateTime :function(){
                }
            });
            var id_test_paper = html_node.find("#id_test_paper");//上传试卷
            id_test_paper.val(data.stu_test_paper);
            html_node.find(".upload_test_paper").attr("id","id_upload_test_paper");
            var id_test_stress = html_node.find("#id_test_stress");//应试压力
            Enum_map.append_option_list("test_stress", id_test_stress, true);
            id_test_stress.val(data.test_stress);
            var id_entrance_school_type = html_node.find("#id_entrance_school_type");//升学学校要求
            Enum_map.append_option_list("entrance_school_type", id_entrance_school_type, true);
            id_entrance_school_type.val(data.entrance_school_type);
            var id_interest_cultivation = html_node.find("#id_interest_cultivation");//趣味培养
            Enum_map.append_option_list("interest_cultivation", id_interest_cultivation, true);
            id_interest_cultivation.val(data.interest_cultivation);
            var id_extra_improvement = html_node.find("#id_extra_improvement");//课外提高
            Enum_map.append_option_list("extra_improvement", id_extra_improvement, true);
            id_extra_improvement.val(data.extra_improvement);
            var id_habit_remodel = html_node.find("#id_habit_remodel");//习惯重塑
            Enum_map.append_option_list("habit_remodel", id_habit_remodel, true);
            id_habit_remodel.val(data.habit_remodel);
            var id_ass_test_lesson_type = html_node.find("#id_ass_test_lesson_type");//分类
            Enum_map.append_option_list("ass_test_lesson_type",id_ass_test_lesson_type,true);
            id_ass_test_lesson_type.val(data.ass_test_lesson_type);
            var id_change_teacher_reason_type = html_node.find("#id_change_teacher_reason_type");//换老师类型
            Enum_map.append_option_list("change_teacher_reason_type",id_change_teacher_reason_type,true);
            id_change_teacher_reason_type.val(data.change_teacher_reason_type);
            var id_change_reason_url = html_node.find("#id_change_reason_url");//换老师原因图片
            id_change_reason_url.val(data.change_teacher_reason_img_url);
            html_node.find(".upload_change_reason_url").attr("id","id_upload_change_reason_url");
            var id_change_reason = html_node.find("#id_change_reason");//换老师原因
            id_change_reason.val(data.change_teacher_reason);
            var id_green_channel_teacherid = html_node.find("#id_green_channel_teacherid");//绿色通道
            id_green_channel_teacherid.val(data.green_channel_teacherid);
            $.admin_select_user(id_green_channel_teacherid,"teacher");
            id_ass_test_lesson_type.on("change",function(){
                if(id_ass_test_lesson_type.val() == 2){
                    id_change_teacher_reason_type.parent().parent().css('display','table-row');
                    id_change_reason.parent().parent().css('display','table-row');
                    id_change_reason_url.parent().parent().css('display','table-row');
                }else{
                    id_change_teacher_reason_type.parent().parent().css('display','none');
                    id_change_reason.parent().parent().css('display','none');
                    id_change_reason_url.parent().parent().css('display','none');

                    id_change_teacher_reason_type.val(0);
                    id_change_reason.val('');
                    id_change_reason_url.val('');
                }
            });
            if(id_ass_test_lesson_type.val() == 2){
                    id_change_teacher_reason_type.parent().parent().css('display','table-row');
                    id_change_reason.parent().parent().css('display','table-row');
                    id_change_reason_url.parent().parent().css('display','table-row');
            }
            var id_learning_situation  = html_node.find("#id_learning_situation");//学情反馈

            var title= '添加试听申请';
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
                    action: function(dialog){
                        var province = province_val;
                        var city = city_val;
                        var area = area_val;
                        var region = region_val;
                        var tea_province = tea_province_val;
                        var tea_city = tea_city_val;
                        var tea_area = tea_area_val;

                        var require_time= $.strtotime(id_stu_request_test_lesson_time.val());
                        var need_start_time=0;
                        var now=(new Date()).getTime()/1000;
                        var min_date_time="";
                        var nowDayOfWeek = (new Date()).getDay();
                        if ((new Date()).getHours() <18 ) {
                            min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 08:00:00"  );
                        }else{
                            if( nowDayOfWeek==5 ||  nowDayOfWeek==6){
                                min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 16:00:00"  );
                            }else{
                                min_date_time= $.DateFormat(now+86400 , "yyyy-MM-dd 14:00:00"  );
                            }
                        }
                        need_start_time=$.strtotime(min_date_time );
                        if(!require_time){
                            alert("请选择试听时间!");
                            return;
                        }
                        if (require_time < need_start_time ) {
                            alert("试听时间不能早于 "+ min_date_time );
                            return;
                        }
                        $.do_ajax("/ss_deal2/ass_save_user_info",{
                            "userid" : opt_data.userid ,
                            "test_lesson_subject_id":opt_data.test_lesson_subject_id ,
                            "require_id":opt_data.require_id,
                            'parent_name':id_par_nick.val(),
                            'gender':id_gender.val(),
                            'grade':id_grade.val(),
                            'subject':id_subject.val(),
                            'school':id_school.val(),
                            'editionid':id_editionid.val(),
                            'province':province,
                            'city':city,
                            'area':area,
                            'region':region,
                            'recent_results':id_recent_results.val(),
                            'advice_flag':id_advice_flag.val(),
                            'class_rank':id_class_rank.val(),
                            'grade_rank':id_grade_rank.val(),
                            'academic_goal':id_academic_goal.val(),
                            'study_habit':id_study_habit.val(),
                            'interests_and_hobbies':id_interests_hobbies.val(),
                            'character_type':id_character_type.val(),
                            'need_teacher_style':id_need_teacher_style.val(),
                            'tea_province':tea_province,
                            'tea_city':tea_city,
                            'tea_area':tea_area,
                            'stu_request_test_lesson_demand':id_stu_request_test_lesson_demand.val(),
                            'intention_level':id_intention_level.val(),
                            'stu_request_test_lesson_time':id_stu_request_test_lesson_time.val(),
                            'test_paper':id_test_paper.val(),
                            'test_stress':id_test_stress.val(),
                            'entrance_school_type':id_entrance_school_type.val(),
                            'interest_cultivation':id_interest_cultivation.val(),
                            'extra_improvement':id_extra_improvement.val(),
                            'habit_remodel':id_habit_remodel.val(),
                            'ass_test_lesson_type':id_ass_test_lesson_type.val(),
                            'change_teacher_reason_type':id_change_teacher_reason_type.val(),
                            'change_reason_url':id_change_reason_url.val(),
                            'green_channel_teacherid':id_green_channel_teacherid.val(),
                            'change_reason':id_change_reason.val(),
                            'learning_situation':id_learning_situation.val(),
                            'new_demand_flag':1,
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
                $.custom_upload_file('id_upload_change_reason_url',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    console.log(res);
                    id_change_reason_url.val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                $.custom_upload_file('id_upload_test_paper', false,function (up, info, file) {
                    var res = $.parseJSON(info);
                    console.log(res);
                    id_test_paper.val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);
                clearTimeout(th);
            }, 1000);
        });
    }

    $(".select-teacher-for-test-lesson").on("click",function(){
        var data = $(this).get_opt_data();
        var userid = data.userid;
        console.log(userid);

        if(data.jw_test_lesson_status == 2){
            BootstrapDialog.alert("请先解除挂载!");
            return;
        }
        if(data.accept_status == 1){
            BootstrapDialog.alert('已确认课程，若更换试听课，请取消课程，重新排课；');
            return;
        }
        var url = "/seller_student_new2/select_teacher_for_test_lesson?require_id="+data.require_id+"&userid="+data.userid;
        window.open(url);
    });


    $(".opt-download-test-paper").show();
});
