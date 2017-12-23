/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-train_lesson_list.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type       : $('#id_date_type').val(),
			opt_date_type   : $('#id_opt_date_type').val(),
			start_time      : $('#id_start_time').val(),
			end_time        : $('#id_end_time').val(),
			lesson_status   : $('#id_lesson_status').val(),
			teacherid       : $('#id_teacherid').val(),
			lesson_sub_type : $('#id_lesson_sub_type').val(),
			train_type      : $('#id_train_type').val(),
        });
    }

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
    Enum_map.append_option_list("lesson_sub_type",$("#id_lesson_sub_type"));
    Enum_map.append_option_list("train_type",$("#id_train_type"));
    $("#id_train_type").val(g_args.train_type);
    $("#id_lesson_sub_type").val(g_args.lesson_sub_type);
    $("#id_is_test_flag").val(g_args.is_test_flag);
    $("#id_lesson_status").val(g_args.lesson_status);
    $("#id_lessonid").val(g_args.lessonid);
    $("#id_teacherid").val(g_args.teacherid);

    $.admin_select_user( $("#id_teacherid"),"teacher", load_data);

    $("#id_add_lesson").on("click",function(){
        do_add_or_update_lesson_info("add",0);
    });

    $(".opt-lesson").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        do_add_or_update_lesson_info("update",lessonid);
    });

    var do_add_or_update_lesson_info = function(type_str,lessonid){
        var id_lesson_name  = $("<input/>");
        var id_lesson_start = $("<input/>");
        var id_lesson_end   = $("<input/>");
        var id_teacherid    = $("<input/>");
        var id_subject      = $("<select/>");
        var id_grade        = $("<select/>");
        var id_sub_type     = $("<select/>");
        var id_train_type   = $("<select/>");

        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("grade",id_grade,true);
        Enum_map.append_option_list("lesson_sub_type",id_sub_type,true);
        Enum_map.append_option_list("train_type",id_train_type,true);

        var arr = [
            ["课程名称",id_lesson_name],
            ["上课开始时间",id_lesson_start],
            ["上课结束时间",id_lesson_end],
            ["上课老师",id_teacherid],
            ["科目",id_subject],
            ["年级",id_grade],
            ["子分类",id_sub_type],
            ["培训类型",id_train_type],
        ];

        if(type_str=="update"){
            $.do_ajax("/tea_manage/get_train_lesson",{
                "lessonid" : lessonid
            },function(result){
                if(result.ret!=0){
                    BootstrapDialog.alert(result.info);
                    return false;
                }else{
                    var data=result.data;
                    id_lesson_name.val(data.lesson_name);
                    id_lesson_start.val(data.lesson_start_str);
                    id_lesson_end.val(data.lesson_end_str);
                    id_teacherid.val(data.teacherid);
                    id_subject.val(data.subject);
                    id_grade.val(data.grade);
                    id_sub_type.val(data.lesson_sub_type);
                    id_train_type.val(data.train_type);
                }
            });
        }

        $.show_key_value_table("培训课程信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/tea_manage/add_train_lesson",{
                    "lessonid"        : lessonid,
                    "lesson_name"     : id_lesson_name.val(),
                    "lesson_start"    : id_lesson_start.val(),
                    "lesson_end"      : id_lesson_end.val(),
                    "teacherid"       : id_teacherid.val(),
                    "subject"         : id_subject.val(),
                    "grade"           : id_grade.val(),
                    "type"            : type_str,
                    "lesson_sub_type" : id_sub_type.val(),
                    "train_type"      : id_train_type.val(),
                },function(result){
                    if(result.ret!=0){
                        BootstrapDialog.alert(result.info);
                    }else{
                        window.location.reload();
                    }
                });
            }
        },function(){
            $.admin_select_user(id_teacherid,"teacher",function(){});
	        id_lesson_start.datetimepicker({
		        datepicker : true,
		        timepicker : true,
		        format     : 'Y-m-d H:i',
		        step       : 30 
	        });
	        id_lesson_end.datetimepicker({
		        datepicker : false,
		        timepicker : true,
		        format     : 'H:i',
		        step       : 30 
	        });
        });
    }

	$('.opt-change').set_input_change_event(load_data);
    $(".opt-qr-pad-at-time").on("click", function () {
        var lessonid = $(this).get_opt_data("lessonid");
        var url      = $(this).data("type");
        var title    = $(this).attr("title");
        //得到 
        $.do_ajax("/tea_manage/get_lesson_xmpp_audio", {
            "lessonid": lessonid
        }, function (result) {
            var data = result.data;
            var args = "title=lessonid:" + lessonid + "&beginTime=" + data.lesson_start + "&endTime=" + data.lesson_end
                + "&roomId=" + data.roomid + "&xmpp=" + data.xmpp + "&webrtc=" + data.webrtc + "&ownerId=" + data.teacherid
                + "&type=" + data.type + "&audioService=" + data.audioService;
            var args_64 = $.base64.encode(args);
            console.log(args);
            var text = encodeURIComponent(url + args_64);

            var dlg = BootstrapDialog.show({
                title: title,
                message: "<div style = \"text-align:center\"><img width=\"350px\" src=\"/common/get_qr?text=" + text + "\"></img>",
                closable: true
            });
            dlg.getModalDialog().css("width", "600px");
        });
    });

    $(".opt-add_train_lesson_user").on("click",function(){
        var data                 = $(this).get_opt_data();
        var id_train_lesson_type = $("<select/>");
        var id_train_lessonid    = $("<input/>");
        var id_subject           = $("<div />");
        var id_grade             = $("<div />");
        var id_create_day        = $("<input/>");
        var id_through_day       = $("<input/>");
        var id_test_transfor_per = $("<div />");
        var id_is_test_user      = $("<select />");
        var id_has_limit         = $("<select />");
        var id_is_freeze         = $("<select />");
        var id_min_per           = "<input id='id_min_per' width='100' />";
        var id_max_per           = "<input id='id_max_per' width='100' />";
        var transfor_html        = "小于"+id_min_per+"<br>大于"+id_max_per;
        var arr  = [
            ["培训课程类型",id_train_lesson_type],
            ["源培训课程",id_train_lessonid],
            ["老师科目",id_subject],
            ["老师年级",id_grade],
            ["老师试讲通过时间(天)",id_create_day],
            ["老师培训通过时间(天)",id_through_day],
            ["老师转化率(不用填%)",id_test_transfor_per],
            ["是否被限课",id_has_limit],
            ["是否被冻结",id_is_freeze],
        ];

        if(g_account=="adrian" || g_account=="jim"){
            var test=["测试老师",id_is_test_user];
            Enum_map.append_option_list("boolean",id_is_test_user,true);
            arr.push(test);
        }

        Enum_map.append_option_list("train_lesson_type",id_train_lesson_type,true);
        Enum_map.append_option_list("boolean",id_has_limit);
        Enum_map.append_option_list("boolean",id_is_freeze);
        Enum_map.append_checkbox_list("subject",id_subject,"tea_subject");
        Enum_map.append_checkbox_list("grade_part_ex",id_grade,"tea_grade");
        id_create_day.val(30);
        id_through_day.val("不限制");
        id_test_transfor_per.html(transfor_html);

        $.show_key_value_table("菜单",arr,{
            label    : "确定",
            cssClass : "btn-warning",
            action   : function(dialog) {
                BootstrapDialog.show({
	                title   : "添加老师中",
	                message : "添加中，请稍后",
	                buttons : [{
		                label  : "返回",
		                action : function(dialog_alert) {
                            dialog_alert.close();
		                }
	                }],
                    onshown:function(dialog_alert){
                        var subject_str = "";
                        var grade_str   = "";
                        $("input[name='tea_subject']:checked").each(function(){
                            subject_str+=$(this).val()+",";
                        });
                        $("input[name='tea_grade']:checked").each(function(){
                            grade_str+=$(this).val()+",";
                        });
                        var is_test_user=0;
                        if(g_account=="adrian" || g_account=="jim"){
                            is_test_user=id_is_test_user.val();
                        }

                        $.do_ajax("/tea_manage/add_train_lesson_user",{
                            "type"           : id_train_lesson_type.val(),
                            "subject"        : subject_str,
                            "grade_part_ex"  : grade_str,
                            "create_day"     : id_create_day.val(),
                            "through_day"    : id_through_day.val(),
                            "min_per"        : $("#id_min_per").val(),
                            "max_per"        : $("#id_max_per").val(),
                            "lessonid"       : data.lessonid,
                            "is_test_user"   : is_test_user,
                            "has_limit"      : id_has_limit.val(),
                            "is_freeze"      : id_is_freeze.val(),
                            "train_lessonid" : id_train_lessonid.val(),
                        },function(result){
                            BootstrapDialog.alert(result.info);
                            dialog_alert.close();
                            if(result.ret==0){
                                dialog.close();
                            }
                        });

                    }
                });
            }
        },function(){
            id_train_lessonid.admin_select_dlg_ajax({
                "opt_type" : "select",
                "url"      : "/tea_manage/get_lesson_list",
                "args_ex"  : {
                    "type" : "train_lesson"
                },
                select_primary_field : "lessonid",
                select_display       : "lesson_name",
                'field_list'         : [{
                    title      : "lessonid",
                    width      : 50,
                    field_name : "lessonid"
                },{
                    title      : "课程名称",
                    field_name : "lesson_name",
                }],filter_list:[
                    [{
                        size_class : "col-md-8" ,
                        title      : "课程名称",
                        'arg_name' : "lesson_name",
                        type       : "input"
                    }]
                ],
            });

            id_subject.parents("tr").hide();
            id_grade.parents("tr").hide();
            id_create_day.parents("tr").hide();
            id_through_day.parents("tr").hide();
            id_test_transfor_per.parents("tr").hide();
            id_has_limit.parents("tr").hide();
            id_is_freeze.parents("tr").hide();
            id_train_lessonid.parents("tr").hide();
            

            id_train_lesson_type.on("change",function(){
                var lesson_type = $(this).val();
                if(lesson_type==1 || lesson_type==4){
                    id_subject.parents("tr").hide();
                    id_grade.parents("tr").hide();
                    id_create_day.parents("tr").hide();
                    id_through_day.parents("tr").hide();
                    id_test_transfor_per.parents("tr").hide();
                    id_has_limit.parents("tr").hide();
                    id_is_freeze.parents("tr").hide();
                    id_train_lessonid.parents("tr").hide();
                }else if(lesson_type==2){
                    id_subject.parents("tr").show();
                    id_grade.parents("tr").show();
                    id_create_day.parents("tr").show();
                    id_through_day.parents("tr").show();
                    id_test_transfor_per.parents("tr").show();
                    id_has_limit.parents("tr").show();
                    id_is_freeze.parents("tr").show();
                    id_train_lessonid.parents("tr").hide();
                }else if(lesson_type==3){
                    id_subject.parents("tr").hide();
                    id_grade.parents("tr").hide();
                    id_create_day.parents("tr").hide();
                    id_through_day.parents("tr").hide();
                    id_test_transfor_per.parents("tr").show();
                    id_has_limit.parents("tr").hide();
                    id_is_freeze.parents("tr").hide();
                    id_train_lessonid.parents("tr").show();
                }
            });

            id_create_day.blur(function(){
                var day=$(this).val();
                if(day=="" || day==0){
                    $(this).val("不限制");
                }
            });
            id_create_day.focus(function(){
                if($(this).val()=="不限制"){
                    $(this).val("");
                }
            });
            id_through_day.blur(function(){
                var day=$(this).val();
                if(day=="" || day==0){
                    $(this).val("不限制");
                }
            });
            id_through_day.focus(function(){
                if($(this).val()=="不限制"){
                    $(this).val("");
                }
            });
            $("#id_min_per").blur(function(){
                var day=$(this).val();
                if(day=="" || day==0){
                    $(this).val("不限制");
                }
            });
            $("#id_min_per").focus(function(){
                if($(this).val()=="不限制"){
                    $(this).val("");
                }
            });
            $("#id_max_per").blur(function(){
                var day=$(this).val();
                if(day=="" || day==0){
                    $(this).val("不限制");
                }
            });
            $("#id_max_per").focus(function(){
                if($(this).val()=="不限制"){
                    $(this).val("");
                }
            });


        });
    });

    var add_new_user = function(data,user_type){
        BootstrapDialog.show({
	        title   : "添加确定",
	        message : "确定添加新入职培训名单么？",
	        buttons : [{
		        label  : "返回",
		        action : function(dialog) {
			        dialog.close();
		        }
	        },{
		        label    : "确认",
		        cssClass : "btn-warning",
		        action   : function(dialog) {
                }
	        }]
        });
    }

    $(".opt-set-server").on("click", function () {
        var courseid    = $(this).get_opt_data("courseid");
        var id_region   = $("<select/>");
        var region_html = "<option value=\"h\">杭州</option>"
            +"<option value=\"b\">北京</option>"
            +"<option value=\"q\">青岛-测试</option>";
        id_region.html(region_html);
        var id_server   = $("<select/>");
        var server_html="<option value=\"00\">1</option>"
            +"<option value=\"01\">2</option>"
            +"<option value=\"02\">3</option>"
            +"<option value=\"03\">4</option>"
            +"<option value=\"04\">5</option>";
        id_server.html(server_html);
        var arr=[
            ["",id_region],
            ["",id_server],
        ];

        $.do_ajax('/stu_manage/get_course_server',{
            'courseid' : courseid
        },function(data){
            if (data['ret'] == 0) {
                id_region.val(data["info"][0]);
                id_server.val(data["info"][1]);
                $.show_key_value_table("选择服务器",arr,{
                    label:"确认",
                    cssClass:"btn-warning",
                    action:function(dialog) {
                        var region = id_region.val();
                        var server = id_server.val();
                        if (region == -1 || server == -1) {
                            alert("请选择地区以及服务器!");
                            return;
                        }

                        $.do_ajax('/stu_manage/set_course_server',{
                            'courseid' : courseid,
                            'region'   : region,
                            'id'       : server,
                        },function(data){
                            if (data['ret'] == 0) {
                                window.location.reload();
                            } else {
                                BootstrapDialog.alert(data['info']);
                            }
                        });
                    }
                });
            }
        });
    });


    $(".opt-upload").on("click",function(){
        var lessonid      = $(this).get_opt_data("lessonid");
        var id_button     = $("<button id=\"id_upload_tea_cw\" class=\"btn btn-primary\" >上传老师课件</button>");

        var arr = [
            ["",id_button],
        ];

        $.show_key_value_table("上传课件",arr,{
            label:"返回",
            cssClass:"btn-warning",
            action:function(dialog) {
                dialog.close();
            }
        },function(){
            $.custom_upload_file("id_upload_tea_cw",false,setCompleteTeacher,lessonid,["pdf","zip"],false);
        });
    });

    var setCompleteTeacher = function(up,info,file,lessonid) {
        var res = $.parseJSON(info);
        if (res.url) {
            BootstrapDialog.alert("地址解析出错！请刷新重试！");
        } else {
            $.do_ajax('/lesson_manage/set_tea_cw_url',{
                'tea_cw_url' : res.key,
                'lessonid'   : lessonid
            },function(data){
                if (data['ret']==0) {
                    BootstrapDialog.alert("上传成功");
                } else {
                    BootstrapDialog.alert(data.info );
                }
            });
        }
    };

    $(".opt-log-list").on("click", function () {
        var lessonid     = $(this).parent().data("lessonid");
        var teacherid    = $(this).parent().data("teacherid");
        var lesson_start = $(this).parent().data("lesson_start");
        var lesson_end   = $(this).parent().data("lesson_end");
        var lesson_type  = $(this).get_opt_data("lesson_type");
        var stu_id       = -1;

        var html_node = $.obj_copy_node("#id_lesson_log");
        $.do_ajax("/lesson_manage/get_lesson_user_list_for_login_log", {
            "lessonid" : lessonid
        }, function (ret) {
            var html_str = "";
            $.each(ret.list, function () {
                var userid = this[0];
                var name   = this[1];
                html_str += "<option value=\"" + userid + "\">" + name + "</option>";
            });
            html_node.find(".opt-userid").html(html_str);
        });

        html_node.find(".form-control").on("change", function () {
            var userid = html_node.find(".opt-userid").val();
            var server_type = html_node.find(".opt-server-type").val();
            load_data_ex(lessonid, userid, server_type);
        });

        BootstrapDialog.show({
            title: "进出列表",
            message: html_node,
            closable: true
        });

        var load_data_ex = function (lessonid, userid, server_type) {
            $.ajax({
                type: "post",
                url: "/supervisor/lesson_get_log",
                dataType: "json",
                data: {
                    'lessonid'     : lessonid,
                    "userid"       : userid,
                    "server_type"  : server_type,
                    "teacher_id"   : teacherid,
                    "stu_id"       : stu_id,
                    "lesson_start" : lesson_start,
                    "lesson_end"   : lesson_end
                },
                success: function (result) {
                    if (result['ret'] == 0) {
                        var data = result['data'];
                        var html_str = "";
                        $.each(data, function (i, item) {
                            var cls = "warning";
                            if (item.opt_type == "login") {
                                cls = "success";
                            }
                            if (item.opt_type == "register") {
                                cls = "warning";
                            }
                            if (item.opt_type == "logout") {
                                cls = "danger";
                            }
                            var rule_str = "";
                            if (item.userid == stu_id) {
                                rule_str = "学生";
                            } else if (item.userid == teacherid) {
                                rule_str = "老师";
                            }

                            html_str += "<tr class=\"" + cls + "\" > <td>" + item.opt_time + "<td>" + rule_str + "<td>" + item.userid + "<td>" + item.server_type + "<td>" + item.opt_type + "<td>" + item.server_ip + "</tr>";
                        });

                        html_node.find(".data-body").html(html_str);
                    }
                }
            });
        };

        load_data_ex(lessonid, -1, -1);
    });


    $(".opt-add_single_user").on("click",function(){
        var lessonid  = $(this).get_opt_data("lessonid");
        var id_userid = $("<input/>");
        var arr = [
            ["姓名",id_userid],
        ];

        $.show_key_value_table("",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action:function(dialog) {
                $.do_ajax("/tea_manage/add_train_lesson_user",{
                    "type"     : 0,
                    "lessonid" : lessonid,
                    "userid"   : id_userid.val(),
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                });
            }
        },function(){
            $.admin_select_user(id_userid,"teacher",function(){});
        });

    });

    $('.opt-get_user_list').on('click',  function(){
        var data = $(this).get_opt_data();
        var id_all_user     = "<button id='id_all_user' class='btn btn-primary'>参与培训名单</button>";
        var id_through_user = "<button id='id_through_user' class='btn btn-primary'>通过培训名单</button>";
        var arr=[
            ["",id_all_user],
            ["",id_through_user],
        ];

        $.show_key_value_table("菜单",arr,{
            label:"返回",
            cssClass:"btn-warning",
            action:function(dialog) {
                dialog.close();
            }
        },function(){
            $("#id_all_user").on("click",function(){
	              show_user_list(data);
            });

            $("#id_through_user").on("click",function(){
	              show_through_user_list(data);
            });
        });
    });

    var show_user_list = function(data){
        show_ajax_table({
            "title" : "学生列表",
            "bind"  : function($id_body){
                $id_body.find(".opt-del-user").on("click",function(){
                    var userid = $(this).data("userid");
                    var realname= $(this).data("realname");
                    BootstrapDialog.confirm("要删除["+realname+"]?!",function(ret){
                        if(ret){
                            $.do_ajax( "/tea_manage/del_train_user",{
                                "lessonid" : data.lessonid,
                                "userid"   : userid
                            },function(result){
                                // if(result.ret!=0){
                                //     BootstrapDialog.alert(result.info);
                                // }else{
                                //     window.location.reload();
                                // }
                            });
                        }
                    });
                });
            },"field_list" : [{
                "name"  : "userid",
                "title" : "id"
            },{
                "name"  : "realname",
                "title" : "参加者",
            },{
                "name"  : "subject_str",
                "title" : "第一科目",
            },{
                "name"  : "phone",
                "title" : "手机"
            },{
                "name"  : "score",
                "title" : "分数"
            },{
                "title"  : "操作",
                "render" : function(val,item){
                    return "<a data-realname=\""+item["realname"]+"\"data-userid=\""+item["userid"]+"\" class=\"opt-del-user\">删除</a>";
                }
            }],"request_info" : {
                "url"  : "/tea_manage/get_train_lesson_user" ,
                "data" : {
                    "type"     : 0,
                    "lessonid" : data.lessonid
                }
            }
        });
    }

    var show_through_user_list = function(data){
        show_ajax_table({
            "title" : "通过列表",
            "field_list" : [{
                "name"  : "userid",
                "title" : "id"
            },{
                "name"  : "realname",
                "title" : "参加者",
            },{
                "name"  : "subject_str",
                "title" : "第一科目",
            },{
                "name"  : "score",
                "title" : "分数"
            },{
                "name"  : "phone",
                "title" : "手机"
            }],"request_info" : {
                "url"  : "/tea_manage/get_train_lesson_user" ,
                "data" : {
                    "type"     : 1,
                    "lessonid" : data.lessonid 
                }
            }
        });
    }

    $("#id_add_trial_train_lesson").on("click",function(){
        var id_teacherid = $("<input />");
        var arr = [
            ["老师",id_teacherid]
        ];
        $.show_key_value_table("选择老师",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/common/add_trial_train_lesson_by_admin",{
                    "teacherid":id_teacherid.val()
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        },function(){
            $.admin_select_user( id_teacherid,"teacher");
        });

    });


});
