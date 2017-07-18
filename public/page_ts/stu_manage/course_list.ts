/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-course_list.d.ts" />

$(function(){
    $("#id_competition_flag").val(g_args.competition_flag);
    $("#id_competition_flag").on("change",function(){
        load_data();
    });

    function load_data(){
        $.reload_self_page ( {
      sid              : g_sid,
            competition_flag : $("#id_competition_flag").val()
        });
    }

    $("#id_add_course").on("click",function(){
        var id_subject=$("<select/>");
        var arr=[
            ["科目",id_subject],
        ];
        Enum_map.append_option_list("subject",id_subject,true);

        $.show_key_value_table("增加", arr , {
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( "/user_deal/course_add", {
                    "userid"           : g_sid,
                    "competition_flag" : $("#id_competition_flag").val(),
                    "subject"          : id_subject.val()
                });
            }
        },function(){
        });
    });

    $("#id_add_course_new").on("click",function(){
        var id_course_status        = $("<select/>");
        var id_subject              = $("<select/>");
        var id_teacherid            = $("<input/>");
        var id_lesson_grade_type    = $("<select/>");
        var id_default_lesson_count = $("<input/>");
        var id_is_kk = $("<select/>");

        Enum_map.append_option_list("course_status", id_course_status,true );
        Enum_map.append_option_list("subject", id_subject,true );
        Enum_map.append_option_list("lesson_grade_type", id_lesson_grade_type,true );
        Enum_map.append_option_list("boolean", id_is_kk,true );
        var arr = [
            ["状态",id_course_status ]  ,
            ["老师",id_teacherid ]  ,
            ["科目",id_subject ]  ,
            ["课程年级来源",id_lesson_grade_type ]  ,
            ["默认课时数",id_default_lesson_count ]  ,
            ["是否扩课",id_is_kk ]  ,
        ];

        id_default_lesson_count.val(3);
        $.show_key_value_table("增加课程包", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                if(id_subject.val() <=0 || id_teacherid.val() <=0){
                    alert("请填写完整!");
                    return;
                }
                var is_kk = id_is_kk.val();
                if(is_kk==1){
                    $("<div></div>").admin_select_dlg_ajax({
                        "opt_type" : "select", // or "list"
                        "url"      : "/ss_deal/get_kk_require_list",
                        //其他参数
                        "args_ex" : {
                            "userid"  :  g_sid,
                            "teacherid":id_teacherid.val(),
                            "subject": id_subject.val()
                        },

                        select_primary_field   : "require_id",
                        select_display         : "package_name",
                        select_no_select_value : 0,
                        select_no_select_title : "[未设置]",

                        //字段列表
                        'field_list' :[
                            {
                                title:"require_id",
                                width :50,
                                field_name:"require_id"
                            },{
                                title:"扩课申请时间",
                                field_name:"require_time"
                            },{
                                title:"扩课申请人",
                                field_name:"account"
                            },{
                                title:"上课时间",
                                field_name:"lesson_start"
                            }

                        ] ,
                        //查询列表
                        filter_list:[
                        ],
                        "auto_close" : true,
                        "onChange"   : function( val) {
                            var require_id = val ;
                            var me=this;
                            $.do_ajax("/user_deal/course_add_new",{
                                "userid"               : g_sid,
                                'teacherid'            : id_teacherid.val(),
                                "course_status"        : id_course_status.val(),
                                "subject"              : id_subject.val(),
                                "lesson_grade_type"    : id_lesson_grade_type.val(),
                                "default_lesson_count" : id_default_lesson_count.val()*100,
                                "competition_flag"     : $("#id_competition_flag").val(),
                                "require_id"           : require_id,
                                "is_kk_flag"           : is_kk
                            });
                        },
                        "onLoadData" : null
                    });
                }else{
                    $.do_ajax("/user_deal/course_add_new",{
                        "userid"               : g_sid,
                        'teacherid'            : id_teacherid.val(),
                        "course_status"        : id_course_status.val(),
                        "subject"              : id_subject.val(),
                        "lesson_grade_type"    : id_lesson_grade_type.val(),
                        "competition_flag"     : $("#id_competition_flag").val(),
                        "default_lesson_count" : id_default_lesson_count.val()*100,
                        "is_kk_flag"           : is_kk
                    });
                }
            }
        },function(){
            $.admin_select_user(id_teacherid,"teacher");
        });
    });


    $(".opt-set-course-status").on("click",function(){
        var opt_data                = $(this).get_opt_data();
        console.log(opt_data.courseid);
        var id_course_status        = $("<select/>");
        var id_subject              = $("<select/>");
         var id_grade                = $("<select/>");
        var id_teacherid            = $("<input/>");
        var id_lesson_grade_type    = $("<select/>");
        var id_default_lesson_count = $("<input/>");

        Enum_map.append_option_list("course_status", id_course_status,true );
        Enum_map.append_option_list("subject", id_subject,true );
         Enum_map.append_option_list("grade", id_grade,true );
        Enum_map.append_option_list("lesson_grade_type", id_lesson_grade_type,true );

        id_course_status.val(opt_data.course_status );
        id_teacherid.val(opt_data.teacherid);
        id_subject.val(opt_data.subject);
         id_grade.val(opt_data.grade);
        id_default_lesson_count.val(opt_data.default_lesson_count);
        id_lesson_grade_type.val(opt_data.lesson_grade_type);
        var arr = [
            ["状态",id_course_status ]  ,
            ["老师",id_teacherid ]  ,
            ["科目",id_subject ]  ,
             ["年级",id_grade ]  ,
            ["课程年级来源",id_lesson_grade_type ]  ,
            ["默认课时数",id_default_lesson_count ]  ,
        ];

        $.show_key_value_table("课程状态", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/course_set_status_ex",{
                    'courseid'             : opt_data.courseid,
                    'teacherid'            : id_teacherid.val(),
                    "course_status"        : id_course_status.val(),
                    "subject"              : id_subject.val(),
                     "grade"                : id_grade.val(),
                    "lesson_grade_type"    : id_lesson_grade_type.val(),
                    "default_lesson_count" : id_default_lesson_count.val()*100
                });
            }
        },function(){
            $.admin_select_user(id_teacherid,"teacher");
        });
    });

    $(".opt-lesson-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var subject = opt_data.subject;
        if(subject <=0){
            alert("请先设置科目!");
            return;
        }
        $.wopen("/stu_manage/course_lesson_list?sid="+g_sid+"&courseid="+opt_data.courseid,true);
    });


    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("要删除老师是["+ opt_data.teacher_nick + "]的课程吗",function(val){
            if (val){
                $.do_ajax( "/user_deal/course_del", {
                    "courseid":opt_data.courseid
                }) ;
            }

        });

    });


    $(".opt-assigned_lesson_count").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_change_lesson_count=$("<input/>");
        var id_opt_type=$("<select  > <option value=0>取出课时</option>  <option value=1>加进课时</option> </select>");
        var unassigned_lesson_count= $("#id_unassigned_lesson_count").val();
        var left_lesson_count = parseFloat( opt_data.left_lesson_count) - parseFloat( opt_data.no_finish_lesson_count );
        var  id_range_txt=$( "<div> </div>");
        id_opt_type.on("change",function(){
            if (id_opt_type.val()==1) {
                id_range_txt.text("0 ~ " +  unassigned_lesson_count);
            }else{
                id_range_txt.text("0 ~ " + left_lesson_count);
            }
        });
        id_opt_type.val(1);
        if (id_opt_type.val()==1) {
            id_range_txt.text("0 ~ " +  unassigned_lesson_count);
        }else{
            id_range_txt.text("0 ~ " + left_lesson_count);
        }


        var arr=[
            ["课程未排课时", left_lesson_count  ],
            ["公共区待分配课时", unassigned_lesson_count ],
            ["操作类型",   id_opt_type ],
            ["输入课时数有效范围",   id_range_txt ],
            ["输入课时数", id_change_lesson_count ]  ,
        ];

        $.show_key_value_table("分配课时", arr ,{
                label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var assigned_lesson_count= parseFloat( opt_data.assigned_lesson_count);
                var change_lesson_count=  parseFloat( id_change_lesson_count.val()) ;
                if (id_opt_type.val()==1) {
                    assigned_lesson_count+= change_lesson_count;
                }else{
                    assigned_lesson_count-= change_lesson_count;
                }

                $.do_ajax( "/user_deal/course_set_assigned_lesson_count", {
                    'courseid'              : opt_data.courseid,
                    "assigned_lesson_count" : assigned_lesson_count*100,
                    "competition_flag"      : $("#id_competition_flag").val()
                });
            }
        },function(){});


    });

    $(".opt-set-orderid").on("click",function(){

        var opt_data=$(this).get_opt_data();
        var id_orderid=$("<input/>");
        var arr=[
            ["orderid", id_orderid]  ,
        ];

        $.show_key_value_table("课程状态", arr ,{
                label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax( "/user_deal/course_set_orderid", {
                    'courseid':   opt_data.courseid,
                    "orderid" : id_orderid.val()
                });
            }
        },function(){

        });

    });


    $(".opt-div").each(function(){
        var $item=$(this);
        var course_type= $item.data("course_type");
        if (course_type==2) {
            $item.find(".opt-assigned_lesson_count").hide();
        }
    });


    $(".opt-set_subject").on("click",function(){
        var courseid=$(this).get_opt_data("courseid");
        var id_subject=$("<select/>");

        var arr=[
            ["科目",id_subject],
        ];
        Enum_map.append_option_list("subject",id_subject,true);

        $.do_ajax( "/stu_manage/set_subject", {
            "courseid" : courseid
        },function(data){
            id_subject.val(data.subject);

            $.show_key_value_table("增加", arr , {
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    $.do_ajax( "/stu_manage/set_subject", {
                        "type"     : 1,
                        "courseid" : courseid,
                        "subject"  : id_subject.val()
                    },function(result){
                        if(result.ret==-1){
                            BootstrapDialog.alert(result.info);
                        }else{
                            //window.location.reload();
                            load_data();
                        }
                    });

                }
            });
        });
    });

    $(".opt-set_week_comment_num").on("click",function(){
        var courseid         = $(this).get_opt_data("courseid");
        var week_comment_num = $(this).get_opt_data("week_comment_num");
        var info="";
        if(week_comment_num>0){
            week_comment_num=0;
            info="是否取消老师每周只用评价一次的标志？";
        }else{
            week_comment_num=1;
            info="是否设置为老师每周只用评价一次的标志？";
        }

        BootstrapDialog.show({
            title    : "更改老师多次评价标志",
            message  : info,
            closable : true,
            buttons  : [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            },{
                label    : '确定',
                cssClass : 'btn-primary',
                action   : function(dialog) {
                    $.do_ajax("/stu_manage/set_week_comment_num",{
                        "courseid"         : courseid,
                        "week_comment_num" : week_comment_num,
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    });
                }
            }]
        });
    });

    $(".opt-set_enable_video").on("click",function(){
        var data         = $(this).get_opt_data();
        var enable_video = data.enable_video;
        var info         = "";
        if(enable_video==0){
            enable_video = 1;
            info="是否开启课程视频?";
        }else{
            enable_video = 0;
            info="是否关闭课程视频?";
        }

        BootstrapDialog.show({
          title   : "开启确认",
          message : info,
          buttons : [{
            label  : "返回",
            action : function(dialog) {
              dialog.close();
            }
          }, {
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                    $.do_ajax("/stu_manage/set_enable_video",{
                        "courseid"     : data.courseid,
                        "enable_video" : enable_video
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    })
            }
          }]
        });
    });

});
