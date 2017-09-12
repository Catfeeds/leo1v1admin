/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-seller_student_list.d.ts" />



var show_name_key="";

function load_data(){
    if ($.trim($("#id_phone_name").val()) != g_args.phone_name ) {
        $.do_ajax("/user_deal/set_item_list_add",{
            "item_key" :show_name_key,
            "item_name":  $.trim($("#id_phone_name").val())
        },function(){});
    }

    $.reload_self_page ( {
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        group_seller_student_status:	$('#id_group_seller_student_status').val(),
        seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
        end_time:	$('#id_end_time').val(),
        userid:	$('#id_userid').val(),
        success_flag:	$('#id_success_flag').val(),
        phone_name:	$('#id_phone_name').val(),
        seller_student_status:	$('#id_seller_student_status').val(),
        phone_location:	$('#id_phone_location').val(),
        subject:	$('#id_subject').val(),
        origin_assistant_role:	$('#id_origin_assistant_role').val(),
        has_pad:	$('#id_has_pad').val(),
        tq_called_flag:	$('#id_tq_called_flag').val(),
        origin_assistantid:	$('#id_origin_assistantid').val(),
        origin_userid:	$('#id_origin_userid').val(),
        seller_require_change_flag:	$('#id_seller_require_change_flag').val(),
        tmk_student_status:	$('#id_tmk_student_status').val(),
       // end_class_flag:$("#id_end_class_flag").val(),
        seller_resource_type:	$('#id_seller_resource_type').val()
    });
}

$(function(){
    show_name_key="stu_info_name_"+g_adminid;
    var status_opt_list=[];
    $.each( (""+g_args.status_list_str).split(",") ,function(){
        status_opt_list.push(parseInt(this) );
    }) ;
    if(g_args.cur_page==10000 ) {
        status_opt_list =null;
    }


    $('#id_seller_student_status').val(g_args.seller_student_status);
    $.enum_multi_select( $('#id_seller_student_status'), 'seller_student_status', function(){load_data();} )

    Enum_map.append_option_list("seller_require_change_flag",$("#id_seller_require_change_flag"),false,[1,2,3]);
    Enum_map.append_option_list("pad_type",$("#id_has_pad"));
    Enum_map.append_option_list("tq_called_flag",$("#id_tq_called_flag"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("group_seller_student_status",$("#id_group_seller_student_status"));
    Enum_map.append_option_list("seller_resource_type",$("#id_seller_resource_type"));
    Enum_map.append_option_list("set_boolean",$("#id_success_flag"));
    Enum_map.append_option_list("account_role",$("#id_origin_assistant_role"));
    Enum_map.append_option_list("tmk_student_status",$("#id_tmk_student_status"));

    $('#id_origin_assistant_role').val(g_args.origin_assistant_role);

    $( "#id_phone_name" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            $("#id_phone_name").val(ui.item.value);
            load_data();
        }
    });

    $(".opt-refresh-call").on("click",function(){
        var me=this;
        var opt_data=$(this).get_opt_data();
        if(opt_data.lessonid){
            $.do_ajax("/seller_student_new/refresh_call_end",{
                "lessonid" : opt_data.lessonid,
            },function(ret){
                if(ret){
                    if(ret == 3){
                        alert('该试听课已回访!');
                    }else{
                        alert('刷新成功!');
                        window.location.href = "http://admin.yb1v1.com/seller_student_new/deal_new_user";
                    }
                }else{
                    alert('还有试听课未回访!');
                    $(location).attr('href','http://admin.yb1v1.com/seller_student_new/no_lesson_call_end_time_list?adminid='+opt_data.admin_revisiterid);
                }
            });
        }else{
            alert('请先排试听课!');
        }
    });


    $(".opt-match-teacher").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $teacherid= $("<input/>") ;
        var $lesson_start= $("<input/>") ;
        // alert(opt_data.grade);
        if(opt_data.grade == 100 || opt_data.grade == 200 || opt_data.grade == 300){
            alert("请先填写具体年级");
            return;
        }
        $("<div></div>").admin_select_dlg_ajax({
            "width"    : 1200,
            "opt_type" : "list",
            "url"      : "/ss_deal/get_teacher_list",
            "args_ex" : {
                "lesson_time" : opt_data.except_lesson_time,
                "subject"     : opt_data.subject,
                "grade"       : opt_data.grade
            },

            select_primary_field   : "teacherid",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",
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
                    width :50,
                    field_name : "phone"
                },{
                    title      : "邮箱",
                    width      : 50,
                    field_name : "email"
                },{
                    title      : "科目",
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
    $('#id_status_list_str').val(g_args.status_list_str);
    $('#id_userid').val(g_args.userid);
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
    $("#id_seller_groupid_ex").init_seller_groupid_ex();
    $('#id_phone_location').val(g_args.phone_location);
    $('#id_subject').val(g_args.subject);
    $('#id_has_pad').val(g_args.has_pad);
    $('#id_seller_resource_type').val(g_args.seller_resource_type);
    $('#id_origin_assistantid').val(g_args.origin_assistantid);
    $('#id_origin_userid').val(g_args.origin_userid);
    $('#id_phone_name').val(g_args.phone_name);
    $('#id_success_flag').val(g_args.success_flag);
    $('#id_group_seller_student_status').val(g_args.group_seller_student_status);
    $('#id_tq_called_flag').val(g_args.tq_called_flag);
    $('#id_seller_require_change_flag').val(g_args.seller_require_change_flag);
    $('#id_tmk_student_status').val(g_args.tmk_student_status);
   // $('#id_end_class_flag').val(g_args.end_class_flag);

    $.admin_select_user(
        $('#id_origin_assistantid'),
        "admin", load_data ,false, {
            "main_type": 1,
            select_btn_config: [
                {
                    "label": "[是]",
                    "value": -2
                }, {
                    "label": "[不是]",
                    "value": 0
                }]
        }
    );

    $.admin_select_user(
        $('#id_origin_userid'),
        "student", load_data );

    $('.opt-change').set_input_change_event(load_data);
    /*
      $.admin_select_user(
      $('#id_userid'),
      "seller_student", load_data ,false, {
      "adminid": g_args.admin_revisiterid,
      select_btn_config: [{
      "label": "[未分配]",
      "value": 0
      }]
      }
      );
    */



    $(".opt-post-test-lesson").on("click",function(){
        var me=this;

        var opt_data=$(this).get_opt_data();
        $.do_ajax("/seller_student_new/test_lesson_order_fail_list_new",{
        } ,function(ret){
            if(ret){
                alert('您有签单失败原因未填写,请先填写完哦!');
                window.location.href = 'http://admin.yb1v1.com/seller_student_new/test_lesson_order_fail_list_seller?order_flag=0';
            }
        });

        // $.do_ajax("/seller_student_new/test_lesson_cancle_rate",{
        // } ,function(ret){
        //     if(ret.ret==1){
        //         alert("由于上周您转化率已超过25%,为'+ret.rate+'%,本周将被限制排课,每天可排1节,可点击'排课申请'继续排课");
        //     }else if(ret.ret==2){
        //         alert('您已被限制排课,今天可排课程为1节试听课');
        //     }else if(ret.ret==3){
        //         alert('您的取消率已达20%,大于25%将被限制排课,每天只能排一节试听课,请谨慎处理');
        //     }
        // });

        /*
          if (!opt_data.stu_test_paper && opt_data.stu_test_paper_flow_status != 2 )  {//申请

          if ( !opt_data.stu_test_paper_flow_status)  {
          BootstrapDialog.confirm(
          "没有上传试卷,要特殊申请吗?!" ,
          function(val){
          $.show_input("原因:",  "", function(val){
          val=$.trim(val);
          $.do_ajax( "/ss_deal/requrie_test_lesson_without_test_paper",{
          "test_lesson_subject_id" : opt_data.test_lesson_subject_id,
          "reason" : val
          });
          });
          });
          }else{
          alert ("还在审批中,请让你的主管审批通过");
          }

          return ;
          }
        */
        var do_add_test_lesson= function() {
            $.do_ajax("/ss_deal/get_user_info",{
                "userid"                 : opt_data.userid ,
                "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
            } ,function(ret){
                ret=ret.data;

                if( ret.editionid == 0) {
                    alert("没有设置教材版本!");
                    $(me).parent().find(".opt-edit").click();
                    return;
                }


                if( ret.stu_request_test_lesson_time  =="无" ) {
                    alert("没有试听时间!");
                    $(me).parent().find(".opt-edit").click();
                    return;
                }
                if(ret.subject <=0){
                    alert("没有设置科目!");
                    $(me).parent().find(".opt-edit").click();
                    return; 
                }



            var require_time= $.strtotime( ret.stu_request_test_lesson_time);
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
            if ($.inArray( ret.grade*1, [101,102,103,104,105,106,201,202,203, 301,302,303 ]  ) == -1 ) {
                alert("年级不对,请确认准确年级!"+ ret.grade );
                $(me).parent().find(".opt-edit").click();
                return;
            }

            if (require_time < need_start_time ) {
                alert("申请时间不能早于 "+ min_date_time );
                $(me).parent().find(".opt-edit").click();
                return;
                //申请时间
            }

            var id_stu_test_ipad_flag = $("<select/>");
            var id_not_test_ipad_reason = $("<textarea>");
            var id_user_agent = $("<div />");

            var id_grade_select = $("<select />");



            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("grade", id_grade_select, true);

            if(ret.user_agent ==""){
                id_user_agent.html("您还没有设备信息!");
                id_user_agent.css("color","red");
            }else if(ret.user_agent.indexOf("ipad") <0 && ret.user_agent.indexOf("iPad")<0){
                id_user_agent.html(ret.user_agent);
                id_user_agent.css("color","red");
            }else{
                id_user_agent.html(ret.user_agent);
            }

            var arr=[
                ["姓名",  ret.stu_nick ],
                ["年级", id_grade_select ],
                ["科目", ret.subject_str ],
                ["学校", ret.school ],
                ["试听时间",  ret.stu_request_test_lesson_time ],
                ["试听需求",  ret.stu_request_test_lesson_demand ],
                ["机器版本",  id_user_agent ],
                ["是否已经连线测试 ", id_stu_test_ipad_flag],
                ["未连线测试原因", id_not_test_ipad_reason]
            ];

            id_grade_select.val(ret.grade);

            id_stu_test_ipad_flag.val(ret.stu_test_ipad_flag);
            id_not_test_ipad_reason.val(ret.not_test_ipad_reason);

            id_stu_test_ipad_flag.on("change",function(){
                if(id_stu_test_ipad_flag.val() == 1){
                    id_not_test_ipad_reason.parent().parent().hide();
                }else{
                    id_not_test_ipad_reason.parent().parent().show();
                }
            });

            $.show_key_value_table("试听申请", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    $.do_ajax("/ss_deal/require_test_lesson", {
                        "test_lesson_subject_id"  : opt_data.test_lesson_subject_id,
                        "userid" : opt_data.userid ,
                        "stu_test_ipad_flag" : id_stu_test_ipad_flag.val(),
                        "not_test_ipad_reason" : id_not_test_ipad_reason.val(),
                        "test_stu_grade" : id_grade_select.val(),
                    });
                }
            },function(){
                if(id_stu_test_ipad_flag.val() == 1){
                    id_not_test_ipad_reason.parent().parent().hide();
                }else{
                    id_not_test_ipad_reason.parent().parent().show();
                }
            });
        });
        };

        $.do_ajax("/ajax_deal2/check_add_test_lesson",{
            "userid" : opt_data.userid
        }, function(resp){
            if (resp.ret==-1) {
                alert (resp.info);
                if (resp.flag=="goto_test_lesson_list") {
                    $.wopen("/seller_student_new/seller_get_test_lesson_list");
                }
                return;
            }

            if (!opt_data.parent_wx_openid &&
                g_args.jack_flag !=349 && g_args.jack_flag !=99 && g_args.jack_flag !=68 && g_args.jack_flag!=213 && g_args.jack_flag!=75 && g_args.jack_flag!=186 && g_args.jack_flag!=944)
            {
                alert("家长未关注微信,不能提交试听课");
                $(me).parent().find(".opt-seller-qr-code").click();
                return;
            }

            do_add_test_lesson();
        } );
    });



    init_edit();

    $(" .opt-download-test-paper ").on("click", function () {
        var opt_data= $(this).get_opt_data();
        $.custom_show_pdf(opt_data.stu_test_paper );

    });

    $(".opt-telphone").on("click",function(){
        //
        var me=this;
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
        phone=phone.split("-")[0];

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone
        } );
        //
        $(me).parent().find(".opt-edit").click();

    });


    //点击进入个人主页
    $('.opt-user').on('click', function () {
        var opt_data= $(this).get_opt_data();
        $.wopen('/stu_manage?sid=' + opt_data.userid);
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

    $(".opt-get-require-list ").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ss_deal/get_require_list_js",
            //其他参数
            "args_ex" : {
                test_lesson_subject_id:opt_data.test_lesson_subject_id
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
                        return item.require_time ;
                    }
                },{
                    title:"接受处理人",
                    //width :50,
                    render:function(val,item) {
                        return item.accept_admin_nick;
                    }
                },{
                    title:"是否接受",
                    //width :50,
                    render:function(val,item) {
                        return $(item.accept_flag_str );
                    }
                },{
                    title:"不接受原因",
                    //width :50,
                    render:function(val,item) {
                        return item.no_accept_reason;
                    }

                },{
                    title:"确认人",
                    //width :50,
                    render:function(val,item) {
                        return item.confirm_admin_nick;
                    }

                },{
                    title:"课程是否成功",

                    render:function(val,item) {
                        return $(item.success_flag_str);
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



    $(".opt-undo-test-lesson").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if ( opt_data.seller_student_status==200) { //未排课
            BootstrapDialog.confirm(
                opt_data.nick+":"+ opt_data.subject_str+ "要取消,",
                function(val){
                    $.do_ajax("/ss_deal/set_no_accpect",{
                        'require_id'       : opt_data.current_require_id,
                        'fail_reason'       :""
                    });
                });
        }else{
            alert("不是未排课!");
        }
    });

    $(".opt-tmk-valid").on("click",function(){

        var opt_data=$(this).get_opt_data();
        var $tmk_desc= $("<textarea/>") ;
        var $tmk_student_status= $("<select/>") ;
        Enum_map.append_option_list("tmk_student_status",$tmk_student_status,true);
        $tmk_desc.val( opt_data.tmk_desc  );
        $tmk_student_status.val( opt_data.tmk_student_status );


        var arr=[
            ["状态", $tmk_student_status ],
            ["说明", $tmk_desc ],
        ];

        $.show_key_value_table("TMK 信息", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                var opt_func =function() {
                    $.do_ajax("/ajax_deal2/set_tmk_valid",{
                        userid : opt_data.userid,
                        tmk_student_status: $tmk_student_status.val(),
                        tmk_student_status_old: opt_data.tmk_student_status,
                        tmk_desc: $tmk_desc.val(),
                    });
                };
                if (  $tmk_student_status.val()==3 ) {
                    BootstrapDialog.confirm(
                        "设置有效, 例子将会设置未tmk例子,不在这里出现 ",
                        function(val){
                            opt_func();
                        });
                }else{
                    opt_func();
                }

            }
        });



    });

    $(".opt-set_user_free").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "设置释放到公海:" + opt_data.phone ,
            function(val){
                if (val) {
                    $.do_ajax("/ss_deal2/set_user_free",{
                        "userid" :  opt_data.userid
                    });
                }
            });
    });

    if (g_args.account_seller_level !=9000 ) {
        $(".opt-tmk-valid").hide();
    }

});

function init_edit() {
    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var opt_obj=this;

        $.do_ajax("/ss_deal/get_user_info",{
            "userid" : opt_data.userid ,
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id ,
        } ,function(ret){
            var data=ret.data;
            var html_node = $.dlg_need_html_by_id( "id_dlg_post_user_info");
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
            var id_revisite_info     = html_node.find("#id_stu_revisite_info");
            var id_has_pad           = html_node.find("#id_stu_has_pad");
            var id_editionid         = html_node.find("#id_stu_editionid");
            var id_school            = html_node.find("#id_stu_school");
            var id_intention_level            = html_node.find("#id_intention_level");
            var id_next_revisit_time = html_node.find("#id_next_revisit_time");
            var id_stu_request_test_lesson_time = html_node.find("#id_stu_request_test_lesson_time");
            var id_stu_request_test_lesson_demand= html_node.find("#id_stu_request_test_lesson_demand");
            var id_stu_score_info = html_node.find("#id_stu_score_info");
            var id_stu_character_info = html_node.find("#id_stu_character_info");
            var id_stu_test_lesson_level = html_node.find("#id_stu_test_lesson_level");
            var id_stu_test_ipad_flag = html_node.find("#id_stu_test_ipad_flag");
            var id_stu_request_test_lesson_time_info = html_node.find("#id_stu_request_test_lesson_time_info");
            var id_stu_request_lesson_time_info = html_node.find("#id_stu_request_lesson_time_info");
            id_stu_request_test_lesson_time_info.data("v" , data. stu_request_test_lesson_time_info  );
            id_stu_request_lesson_time_info.data("v" , data.stu_request_lesson_time_info);
            id_stu_request_lesson_time_info.on("click",function(){
                var v=$(this).data("v");
                if(!v) {
                    v="[]";
                }
                var data_list=JSON.parse(v);

                $(this).admin_select_dlg_edit({
                    onAdd:function( call_func ) {
                        var id_week= $("<select> "+
                                       "<option value=1>周1</option> "+
                                       "<option value=2>周2</option> "+
                                       "<option value=3>周3</option> "+
                                       "<option value=4>周4</option> "+
                                       "<option value=5>周5</option> "+
                                       "<option value=6>周6</option> "+
                                       "<option value=0>周日</option> "+
                                       "</select>");
                        var id_start_time=$("<input/>");
                        var id_end_time=$("<input/>");
                        id_start_time.datetimepicker({
                            datepicker:false,
                            timepicker:true,
                            format:'H:i',
                            step:30,
                            onChangeDateTime :function(){
                                var end_time= $.strtotime("1970-01-01 "+id_start_time.val() ) + 7200;
                                id_end_time.val(  $.DateFormat(end_time, "hh:mm"));
                            }
                        });
                        id_end_time.datetimepicker({
                            datepicker:false,
                            timepicker:true,
                            format:'H:i',
                            step:30
                        });
                        var arr=[
                            ["周", id_week],
                            ["开始时间", id_start_time],
                            ["结束时间", id_end_time],
                        ];
                        $.show_key_value_table("增加", arr, {
                            label: '确认',
                            cssClass: 'btn-warning',
                            action: function (dialog) {
                                call_func({
                                    "week" :  id_week.val() ,
                                    "start_time" : $.strtotime( "1970-01-01 "+ id_start_time.val()) ,
                                    "end_time" : $.strtotime ( "1970-01-01 "+ id_end_time.val())
                                });
                                dialog.close();
                            }
                        });





                        /*
                          var div=$("<div/>");
                          div.admin_select_date_time_range({

                          onSelect:function(start_time,end_time) {
                          call_func({
                          "start_time" : start_time ,
                          "end_time" : end_time
                          });
                          }
                          });
                          div.click();
                        */
                    },
                    sort_func : function(a,b){
                        var a_time=a["week"]*10000000+a["start_time"];
                        var b_time=b["week"]*10000000+b["start_time"];
                        if (a_time==b_time ) {
                            return 0;
                        }else{
                            if (a_time>b_time) return 1;
                            else return -1;
                        }
                    }, 'field_list' :[
                        {
                            title:"周",
                            render:function(val,item) {
                                return Enum_map.get_desc("week", item["week"]*1  );
                            }
                        },{

                            title:"时间段",
                            //width :50,
                            render:function(val,item) {
                                return  $.DateFormat(item.start_time, "hh:mm") +"~"+
                                    $.DateFormat(item.end_time, "hh:mm")  ;
                            }
                        }
                    ] ,
                    data_list: data_list,
                    onChange:function( data_list, dialog)  {
                        id_stu_request_lesson_time_info.data("v" , JSON.stringify(data_list));
                    }
                });
            }) ;

            id_stu_request_test_lesson_time_info.on("click",function(){
                var v=$(this).data("v");
                if(!v) {
                    v="[]";
                }
                var data_list=JSON.parse(v);

                $(this).admin_select_dlg_edit({
                    onAdd:function( call_func ) {
                        var div=$("<div/>");
                        div.admin_select_date_time_range({

                            onSelect:function(start_time,end_time) {
                                call_func({
                                    "start_time" : start_time ,
                                    "end_time" : end_time
                                });
                            }
                        });
                        div.click();
                    },
                    sort_func : function(a,b){
                        var a_time=a["start_time"];
                        var b_time=b["start_time"];
                        if (a_time==b_time ) {
                            return 0;
                        }else{
                            if (a_time>b_time) return 1;
                            else return -1;
                        }
                    }, 'field_list' :[
                        {
                            title:"时间段",
                            //width :50,
                            render:function(val,item) {
                                return  $.DateFormat(item.start_time, "yyyy-MM-dd hh:mm") +"~"+
                                    $.DateFormat(item.end_time, "hh:mm")  ;
                            }
                        }
                    ] ,
                    data_list: data_list,
                    onChange:function( data_list, dialog)  {
                        id_stu_request_test_lesson_time_info.data("v" , JSON.stringify(data_list));
                    }
                });


            }) ;

            html_node.find("#id_stu_reset_next_revisit_time").on("click",function(){
                id_next_revisit_time.val("");
            });
            Enum_map.append_option_list("grade", id_grade, true,[101,102,103,104,105,106,201,202,203,301,302,303]);
            Enum_map.append_option_list("pad_type", id_has_pad, true);
            Enum_map.append_option_list("subject", id_subject, true);
            Enum_map.append_option_list("boolean", id_stu_test_ipad_flag, true);
            Enum_map.append_option_list("test_lesson_level", id_stu_test_lesson_level, true);
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
            id_revisite_info.val(data.revisite_info);
            id_has_pad.val(data.has_pad);
            id_school.val(data.school);
            id_editionid.val(data.editionid);
            id_next_revisit_time.val(data.next_revisit_time);


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
            id_stu_score_info.val(data.stu_score_info);
            id_stu_test_lesson_level.val(data.stu_test_lesson_level);
            id_stu_test_ipad_flag.val(data.stu_test_ipad_flag);
            id_stu_character_info.val(data.stu_character_info);

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

                        $.do_ajax("/ss_deal/save_user_info",{
                            userid        : opt_data.userid,
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
                            revisite_info : id_revisite_info.val(),
                            next_revisit_time : id_next_revisit_time.val(),
                            editionid : id_editionid.val(),
                            school: id_school.val(),
                            stu_request_test_lesson_time:id_stu_request_test_lesson_time.val(),
                            stu_request_test_lesson_demand:id_stu_request_test_lesson_demand.val(),
                            stu_score_info:id_stu_score_info.val(),
                            stu_test_lesson_level:id_stu_test_lesson_level.val(),
                            stu_test_ipad_flag:id_stu_test_ipad_flag.val(),
                            stu_character_info:id_stu_character_info.val(),
                            stu_request_test_lesson_time_info:id_stu_request_test_lesson_time_info.data("v"),
                            stu_request_lesson_time_info:id_stu_request_lesson_time_info.data("v"),
                            has_pad       : id_has_pad.val(),
                            intention_level       : id_intention_level.val()
                        });

                    }
                }]
            });

            dlg.getModalDialog().css("width","98%");


            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );

        });

    });



    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除? 电话:" + opt_data.phone+ " 科目:"+ opt_data.subject_str  ,
            function(val){
                if (val) {
                    $.do_ajax("/ss_deal/del_seller_student", {
                        "test_lesson_subject_id"         : opt_data.test_lesson_subject_id,
                    });

                }
            });
    });


    $(".opt-jump").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var pageid=1;
        switch (opt_data.seller_student_status ){
        case 0:
        case 2:
            pageid=0; break;

        case 1:
            pageid=1; break;


        case 100:
        case 103:
            pageid=103; break;

        case 101:
        case 102:
            pageid=101; break;

        case 110:
        case 120:
            pageid=110; break;

        case 200:
            pageid=200; break;

        case 210:
            pageid=210; break;

        case 220:
            pageid=220; break;

        case 290:
            pageid=290; break;

        case 301:
        case 302:
        case 303:
            pageid=301; break;
        case 400:
            pageid=400; break;

        case 410:
            pageid=410; break;


        }

        $.wopen("/seller_student_new/seller_student_list_"+pageid+"?no_jump=1&userid=" + opt_data.userid,true);
    });


    $(".opt-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if(!( opt_data.current_lessonid )) {
            alert("还没有排课,无需确认");
            return;
        }
        var now= (new Date()).getTime()/1000;
        /*
          if ( $.strtotime(opt_data.lesson_start) >now ) {
          alert('还没上课不能确认课程!');
          return;
          }
        */

        var $fail_greater_4_hour_flag =$("<select> <option value=0>否</option> <option value=1>是</option>  </select>") ;
        var $success_flag=$("<select> <option value=0>未设置</option> <option value=1>成功</option>  <option value=2>失败</option>  </select>") ;
        var $test_lesson_fail_flag=$("<select></select>") ;
        var $fail_reason=$("<textarea></textarea>") ;
        Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true );
        $success_flag.val(opt_data.success_flag );
        $fail_reason.val(opt_data.fail_reason);
        $test_lesson_fail_flag.val(opt_data.test_lesson_fail_flag);
        //$fail_greater_4_hour_flag .val(opt_data.fail_greater_4_hour_flag);
        var fail_greater_4_hour_flag=0;
        if ( $.strtotime( opt_data.lesson_start) - (new Date()).getTime()/1000  > 4*3600 ) {
            fail_greater_4_hour_flag=1;
        }



        var arr=[
            ["学生", opt_data.nick  ],
            ["老师", opt_data.teacher_nick ],
            ["上课时间", opt_data.lesson_start   ],
            ["是否成功",  $success_flag ],
            //["是否离上课4个小时以前(不付老师工资)", $fail_greater_4_hour_flag],
            ["失败类型", $test_lesson_fail_flag],
            ["失败原因", $fail_reason],
        ];

        var update_show_status =function ()  {
            var show_flag=  $success_flag.val()==2 ;
            //alert(show_flag);
            //$fail_greater_4_hour_flag.key_value_table_show( show_flag);
            $test_lesson_fail_flag.key_value_table_show( show_flag);
            $fail_reason.key_value_table_show( show_flag);
            $test_lesson_fail_flag.html("");
            if (fail_greater_4_hour_flag ==1 ) { //不付老师工资
                Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true, [100] );
            }else{
                //已开课
                if ( $.strtotime( opt_data.lesson_start) < (new Date()).getTime()/1000  ) {
                    Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true ,
                                                 [1,2,109,110,111,112,113] );

                }else{ //课前4小时内
                    Enum_map.append_option_list("test_lesson_fail_flag", $test_lesson_fail_flag, true ,
                                                [1,2,109,110,111,112,113]   );
                }
            }
        };

        $success_flag.on("change",update_show_status);
        //$fail_greater_4_hour_flag.on("change",update_show_status);



        $.show_key_value_table("课程确认", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/confirm_test_lesson", {
                    "require_id"             : opt_data.current_require_id,
                    "success_flag"             : $success_flag.val(),
                    "fail_reason"              : $fail_reason.val(),
                    "test_lesson_fail_flag"    : $test_lesson_fail_flag.val(),
                    "fail_greater_4_hour_flag" : fail_greater_4_hour_flag,
                });
            }
        },function(){
            update_show_status();
        });

    });

    $(".opt-notify-lesson").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if (opt_data.notify_lesson_flag ==0) {
            alert("上课前两天内，才可设置");
            return;
        }
        var set_flag=1;
        var title="要设置［"+opt_data.nick+"］:";

        if (opt_data.notify_lesson_flag==2)  {
            set_flag=0;
            title+="未通知";

        }else{
            title+="已通知";
            set_flag=1;
        }

        BootstrapDialog.confirm(title,function(val){
            if (val) {
                $.do_ajax("/ss_deal/seller_student_lesson_set_notify_flag",{
                    require_id: opt_data.current_require_id,
                    notify_flag: set_flag
                });
            }
        });
        //alert(opt_data.notify_lesson_flag);

    });


    //init ui
    if (g_args.cur_page==10001 || g_args.cur_page==10002  ){
        $(".opt-telphone").hide();
    }

    if ( g_args.cur_page==10001  ) {
        $("#id_origin_assistantid").parent().parent().hide();
    }else{

    }




    $(".opt-kuoke").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var $subject=$("<select/>");
        Enum_map.append_option_list("subject",$subject,true);
        var arr=[
            ["姓名",  opt_data.nick],
            ["电话", opt_data.phone ],
            ["科目", $subject],
        ];
        $.show_key_value_table("扩课", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                $.do_ajax("/ss_deal/seller_student_add_subject", {
                    "userid"  : opt_data.userid ,
                    "subject"  : $subject.val()
                });
            }
        });


    });
    $("#id_add").on("click",function(){
        var $origin_assistantid = $("<input/>") ;
        var $phone = $("<input/>") ;
        var $subject= $("<select/>") ;
        var $grade= $("<select/>") ;
        var $origin_userid = $("<input/>") ;
        Enum_map.append_option_list("subject",$subject,true);
        Enum_map.append_option_list("grade",$grade,true);
        var arr=[
            ["负责人(cc/助教)", $origin_assistantid ],
            ["电话", $phone ],
            ["年级", $grade],
            ["科目", $subject],
            ["介绍人", $origin_userid],
        ];

        $.show_key_value_table("新增转介绍", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                var phone=$.trim($phone.val());
                if (phone.length!=11) {
                    alert("手机号要11位") ;
                    return;
                }
                var origin_assistantid= $origin_assistantid.val();

                if (!(origin_assistantid>0)) {
                    alert("请选择负责人") ;
                    return;
                }

                var origin_userid=$origin_userid.val();
                if (!(origin_userid>0)) {
                    alert("请选择介绍人") ;
                    return;
                }

                BootstrapDialog.confirm("要新增转介绍? 手机["+phone +"] ",function(val){
                    if (val) {
                        $.do_ajax("/ss_deal/ass_add_seller_user", {
                            "phone"         : phone,
                            "origin_userid" : origin_userid,
                            "origin_assistantid" : origin_assistantid,
                            "grade"         : $grade.val(),
                            "subject"       : $subject.val()
                        });
                    }
                } );
            }
        },function(){
            $.admin_select_user( $origin_userid, "student"  );
            $.admin_select_user( $origin_assistantid , "account"  );
        });

    });
    $(".opt-get_stu_performance").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax('/ss_deal/get_stu_performance_for_seller',{
            "require_id":opt_data.current_require_id
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

    var init_noit_btn_ex=function( id_name, count, title, value_class) {
        var btn=$('#'+id_name);
        count=count*1;
        btn.data("value",count);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (!value_class) value_class="btn-warning";
        if (value >0 ) {
            btn.addClass(value_class);
        }
    };
    var init_noit_btn=function( id_name, count, title) {
        init_noit_btn_ex( id_name, count, title, null);
    };


    $.do_ajax( "/ss_deal/seller_noti_info",{},function(resp){
        init_noit_btn_ex("id_tmk_new_no_called_count",   resp.tmk_new_no_call_count,    "微信-新分配例子未回访数", "btn-danger" );
        init_noit_btn("id_new_no_called_count",   resp.new_not_call_count,    "新分配例子未回访数" );
        init_noit_btn("id_no_called_count",   resp.not_call_count,    "例子未回访数" );
        init_noit_btn("id_next_revisit",   resp.next_revisit_count,    "需再次回访数" );
        init_noit_btn("id_lesson_today",  resp.today,  "今天上课须通知数" );
        init_noit_btn("id_lesson_tomorrow", resp.tomorrow, "明天上课须通知数" );
        init_noit_btn("id_return_back_count", resp.return_back_count, "被驳回未处理的个数" );
        init_noit_btn("id_require_count",  resp.require_count,"已预约未排数" );
        init_noit_btn("id_no_confirm_count",  resp.no_confirm_count,"课程未确认数" );
       // init_noit_btn("id_end_class_stu",resp.end_class_stu_num,"一个月内结课学生数" );
    });

    var init_and_reload=function(  set_func ) {
        $('#id_subject').val(-1);
        $('#id_grade').val(-1);
        $('#id_seller_student_status').val(-1);
        $("#id_phone_name").val("");
        $("#id_phone_location").val("");
        $("#id_has_pad").val(-1);
        $("#id_userid").val(-1);
        $("#id_seller_resource_type").val(-1);
        $("#id_origin_assistantid").val(-1);
        $("#id_success_flag").val(-1);
        $("#id_tmk_student_status").val(-1);
       // $("#id_end_class_flag").val(-1);
        var now=new Date();
        var t=now.getTime()/1000;

        set_func(t);
        load_data();
    };

    $("#id_no_confirm_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 5,  0, now-86400*14,  now);
            $("#id_success_flag").val(0);
        });
    });


    $("#id_new_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            $('#id_seller_student_status').val(0);
            $("#id_seller_resource_type").val(0);
        });
    });
    $("#id_tmk_new_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            $('#id_seller_student_status').val(0);
            $('#id_tmk_student_status').val(3);
        });
    });


    $("#id_no_called_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 4,  0, now-86400*60 ,  now);
            $('#id_seller_student_status').val(0);
        });
    });





    $("#id_next_revisit").on("click",function(){

        init_and_reload(function(now){
            $.filed_init_date_range( 1,  0, now-7*86400,  now);
        });

    });

    $("#id_return_back_count").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 3,  0, now-14*86400,  now);
            $('#id_seller_student_status').val(110 );
        });
    });

    $("#id_require_count").on("click",function(){

        init_and_reload(function(now){
            $.filed_init_date_range( 3,  0, now-14*86400,  now);
            $('#id_seller_student_status').val(200);
        });
    });

  /*  $("#id_end_class_stu").on("click",function(){
        init_and_reload(function(now){
            $.filed_init_date_range( 8,  0, now-86400*30 ,  now);
            $('#id_seller_student_status').val(-2);
            $('#id_end_class_flag').val(1);
        });
    });*/




    $("#id_lesson_tomorrow ,#id_lesson_today").on("click",function(){
        var me=this;
        init_and_reload(function(now){
            var start_time=0 ;
            var end_time=0 ;
            if ($(me).attr("id")=="id_lesson_today") {
                start_time= now;
                end_time= now;
            }else{
                start_time= now+86400;
                end_time= now+86400;
            }
            $.filed_init_date_range( 5,  1, start_time ,  end_time);
        });
    });



    var init_show_name_list_flag=false;


    $(".opt-seller-require").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if(!( opt_data.current_lessonid )) {
            alert("还没有排课");
            return;
        }
        if(opt_data.seller_require_change_flag == 1){
            alert("已有申请在进行中");
            return;
        }
        var now= (new Date()).getTime()/1000;
        var lesson_time = $.strtotime(opt_data.lesson_start);
        if(now > lesson_time - 3600*4){
            alert("离课程开始不足4小时,不能更换时间");
            return;
        }
        var $seller_require_change_type =$("<select></select>") ;
        var $require_change_lesson_time =$("<input></input>") ;
        var $teacherid = $("<input></input>") ;
        Enum_map.append_option_list("seller_require_change_type", $seller_require_change_type, true ,[1]);
        $require_change_lesson_time.datetimepicker( {
            lang:'ch',
            timepicker:true,
            format: "Y-m-d H:i",
            step:30,
            onChangeDateTime :function(){
            }
        });
        var arr=[
            ["申请类型", $seller_require_change_type  ],
            ["更改课程时间", $require_change_lesson_time ],
            ["更换老师",$teacherid]
        ];
        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        $seller_require_change_type.on("change",function(){
            var val = $seller_require_change_type.val();
            if(val==1){
                show_field($require_change_lesson_time,true);
                show_field($teacherid,false);
            }else{
                show_field($require_change_lesson_time,false);
                show_field($teacherid,true);
            }
        });
        $.show_key_value_table("申请更改时间", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/ss_deal/test_lesson_time_change", {
                    "require_id"             : opt_data.current_require_id,
                    "seller_require_change_type"  : $seller_require_change_type.val(),
                    "old_lesson_start":opt_data.lesson_start,
                    "userid":opt_data.userid,
                    "nick":opt_data.nick,
                    "teacherid":opt_data.teacherid,
                    "require_change_lesson_time"              : $require_change_lesson_time.val()

                });
            }
        },function(){
            var val = $seller_require_change_type.val();
            if(val==1){
                show_field($require_change_lesson_time,true);
                show_field($teacherid,false);
            }else{
                show_field($require_change_lesson_time,false);
                show_field($teacherid,true);
            }

            $.admin_select_user( $teacherid, "teacher");

        });

    });



    $(".opt-flow-node-list").on("click",function(){
        var opt_data=$(this).get_opt_data();

        /*
          var arr=[];
          $.show_key_value_table( "不传试卷审核进度",arr);
        */
        $.flow_show_node_list( opt_data.stu_test_paper_flowid );

        //$.flow_show_define_list( opt_data.stu_test_paper_flowid );
    });

    $(".opt-seller-green-channel").on("click",function(){
       // if(g_args.rank >=1 && g_args.rank <=10){
        var opt_data=$(this).get_opt_data();
        console.log(opt_data.current_require_id);
        if(opt_data.current_require_id <= 0){
            alert("你还没有试听申请哦!!");
            return;
        }
        if(opt_data.seller_student_status != 200){
            alert("只有预约未排的课才能申请绿色通道哦!");
            return;
        }


        var $green_channel_teacherid = $("<input></input>") ;
        var arr=[
            ["选择老师",$green_channel_teacherid]
        ];

        $.show_key_value_table("申请绿色通道", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                if($green_channel_teacherid.val() <= 0){
                    alert("请选择老师!");
                    return;
                }
                $.do_ajax("/ss_deal/set_green_channel_teacherid", {
                    "require_id"             : opt_data.current_require_id,
                    "green_channel_teacherid":$green_channel_teacherid.val()
                });
            }
        },function(){

            $.admin_select_user( $green_channel_teacherid, "teacher");
        });
       // }else{
           // alert("您没有权限进行绿色通道申请!");
            //return;
       // }

    });


    $('.opt-seller-qr-code').on("click", function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax("/stu_manage/set_stu_parent",{
            "studentid" : opt_data.userid,
            "phone"     : opt_data.phone,
        },function(){

            $.do_ajax("/ajax_deal/check_parent_count_and_clean",{
                "userid" : opt_data.userid
            },function(resp){
                if (resp.ret==0) {
                    var dlg = BootstrapDialog.show({
                        title: "分享给家长-关注微信家长端 学生["+ opt_data.phone +":"+ opt_data.nick+ "]",
                        message:
                        $('<img src= "/seller_student_new/erweima?phone='+opt_data.phone+'"/>'),
                        closable: true
                    });
                }else{
                    alert(resp.info);
                }

            });
                //dlg.getModalDialog().css("width", "600px");

        });

    });
    //check power 转介绍
    if (!$.check_power(1004) ) {
        $("#id_add").hide();
    }


    $(".opt-require-commend-teacher").on("click",function(){
        var opt_data=$(this).get_opt_data();
        //alert(opt_data.grade);
        if(opt_data.stu_request_test_lesson_time_old <= 0){
            alert("你还没有设置期待试听时间!!");
            return;
        }
        if(opt_data.seller_student_status > 200){
            alert("已排课或者已试听");
            return;
        }

        var id_except_teacher = $("<textarea />") ;
        var id_textbook = $("<input />") ;
        var id_stu_request_test_lesson_demand = $("<textarea />") ;
        var id_stu_score_info = $("<input />") ;
        var id_stu_character_info = $("<input />") ;
        var arr=[
            ["学生成绩", id_stu_score_info],
            ["学生性格",id_stu_character_info],
            ["教材版本",id_textbook],
            ["试听需求",id_stu_request_test_lesson_demand],
            ["备注(特殊要求)",id_except_teacher],
        ];
        id_textbook.val(opt_data.editionid_str);
        id_stu_request_test_lesson_demand.val(opt_data.stu_request_test_lesson_demand);
        id_stu_score_info.val(opt_data.stu_score_info);
        id_stu_character_info.val(opt_data.stu_character_info);


        $.show_key_value_table("申请推荐老师", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/user_deal/add_seller_require_commend_teacher", {
                    "except_teacher"             : id_except_teacher.val(),
                    "subject"                    : opt_data.subject,
                    "grade"                      : opt_data.grade,
                    "textbook"                   : id_textbook.val(),
                    "stu_request_test_lesson_demand" :  id_stu_request_test_lesson_demand.val(),
                    "stu_request_test_lesson_time"   : opt_data.stu_request_test_lesson_time_old,
                    "stu_request_lesson_time_info"   : opt_data.stu_request_lesson_time_info ,
                    "phone_location"                 : opt_data.phone_location ,
                    "stu_score_info"                 : id_stu_score_info.val(),
                    "stu_character_info"             : id_stu_character_info.val(),
                    "userid"                         : opt_data.userid,
                    "commend_type"                   : 2
                },function(res){
                    if(res.ret==-1){
                        BootstrapDialog.alert(res.info);
                    }else if(res.ret==1){
                        BootstrapDialog.alert(res.info,function(){
                                window.location.reload();
                        });

                    }
                });
            }
        });

    });

    $(".opt-test_lesson-review").on("click",function(){
        var opt_data  = $(this).get_opt_data();
        var $id_phone = $("<input/>");
        var $id_desc  = $("<textarea rows='' cols=''>");

        var arr=[
            ["学生",  $id_phone],
            ["申请说明",  $id_desc],
        ];

        $id_phone.val(opt_data.phone);

        $.show_key_value_table("排课申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/test_lesson_review/test_lesson_review_add",{
                    "userid" : opt_data.userid,
                    "review_desc"   : $id_desc.val(),
                })
            }
        })
    });


}
