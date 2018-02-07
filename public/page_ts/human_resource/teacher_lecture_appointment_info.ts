/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_lecture_appointment_info.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ({
            date_type                  : $('#id_date_type').val(),
            opt_date_type              : $('#id_opt_date_type').val(),
            start_time                 : $('#id_start_time').val(),
            end_time                   : $('#id_end_time').val(),
            lecture_appointment_status : $('#id_lecture_appointment_status').val(),
            teacherid                  : $('#id_teacherid').val(),
            user_name                  : $('#id_user_name').val(),
            status                     : $('#id_status').val(),
            record_status              : $('#id_record_status').val(),
            grade                      : $('#id_grade').val(),
            subject                    : $('#id_subject').val(),
            teacher_ref_type           : $('#id_teacher_ref_type').val(),
            interview_type             : $('#id_interview_type').val(),
            lecture_revisit_type       : $('#id_lecture_revisit_type').val(),
            lecture_revisit_type_new   : $('#id_lecture_revisit_type_new').val(),
            have_wx                    : $('#id_have_wx').val(),
            full_time                  : $('#id_full_time').val(),
            fulltime_teacher_type      : $('#id_fulltime_teacher_type').val(),
            accept_adminid             : $('#id_accept_adminid').val(),
            second_train_status        : $('#id_second_train_status').val(),
            teacher_pass_type          : $('#id_teacher_pass_type').val(),
            gender                     : $('#id_gender').val(),
            is_test_user               : $('#id_is_test_user').val(),
        });
    }

    //实例化一个plupload上传对象
    var uploader = $.plupload_Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/ss_deal/upload_from_xls_cp', //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xls files", extensions : "xls" },
                { title : "xlsx files", extensions : "xlsx" }
            ],
            max_file_size : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });

    uploader.init();
    uploader.bind('FilesAdded',function(up, files) {
        uploader.start();
    });
    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery          : function() {
            load_data();
        }
    });

    Enum_map.append_option_list("lecture_appointment_status", $('#id_lecture_appointment_status'));
    Enum_map.append_option_list("grade", $('#id_grade'),false,[100,200,300]);
    Enum_map.append_option_list_by_not_id("subject", $('#id_subject'),false,[0,11]);
    Enum_map.append_option_list("boolean", $('#id_have_wx'));
    Enum_map.append_option_list("lecture_revisit_type", $('#id_lecture_revisit_type'),false,[0,1,2,3,4]);
    Enum_map.append_option_list("lecture_revisit_type", $('#id_lecture_revisit_type_new'),false,[0,2,5,6,8]);
    Enum_map.append_option_list("boolean", $('#id_full_time'));
    Enum_map.append_option_list("fulltime_teacher_type", $('#id_fulltime_teacher_type'),false,[1,2]);
    Enum_map.append_option_list("gender", $('#id_gender'));
    Enum_map.append_option_list("boolean", $('#id_is_test_user'));
    if(g_args.interview_type==-1){
        Enum_map.append_option_list("check_status", $('#id_status'));
    }else if(g_args.interview_type==0){
        Enum_map.append_option_list("check_status", $('#id_status'),false,[]);
    }else if(g_args.interview_type==1){
        Enum_map.append_option_list("check_status", $('#id_status'));
    }else{
        Enum_map.append_option_list("check_status", $('#id_status'),false,[0,1,2]);
    }

    //Enum_map.append_option_list("teacher_ref_type", $('#id_teacher_ref_type'));


    $('#id_lecture_appointment_status').val(g_args.lecture_appointment_status);
    $('#id_full_time').val(g_args.full_time);
    $('#id_user_name').val(g_args.user_name);
    $('#id_grade').val(g_args.grade);
    $('#id_subject').val(g_args.subject);
    $('#id_status').val(g_args.status);
    $("#id_teacherid").val(g_args.teacherid);
    $("#id_record_status").val(g_args.record_status);
    $("#id_teacher_ref_type").val(g_args.teacher_ref_type);
    $('#id_interview_type').val(g_args.interview_type);
    $('#id_have_wx').val(g_args.have_wx);
    $('#id_lecture_revisit_type').val(g_args.lecture_revisit_type);
    $('#id_lecture_revisit_type_new').val(g_args.lecture_revisit_type_new);
    $('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
    $('#id_accept_adminid').val(g_args.accept_adminid);
    $('#id_second_train_status').val(g_args.second_train_status);
    $('#id_teacher_pass_type').val(g_args.teacher_pass_type);
    $('#id_gender').val(g_args.gender);
    $('#id_is_test_user').val(g_args.is_test_user);

    $.enum_multi_select($("#id_teacher_ref_type"),"teacher_ref_type", function( ){
        load_data();
    });


    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);

    $('.opt-change').set_input_change_event(load_data);
    $.admin_select_user(
        $('#id_accept_adminid'),
        "admin", load_data,false,{"main_type":8});


    var upload_func = function(id,url) {
        var j_uploader = new plupload.Uploader({
            browse_button      : id, //触发文件选择对话框的按钮，为那个元素id
            url                : url, //服务器端的上传页面地址
            flash_swf_url      : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
            silverlight_xap_url: '/js/qiniu/plupload/Moxie.xap',//silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
            filters : {
                mime_types  : [ //只允许上传图片和zip文件
                    { title : "csv files", extensions : "csv" }
                ],
                max_file_size : '40m', //最大只能上传400kb的文件
                prevent_duplicates : true //不允许选取重复文件
            }
        });

        j_uploader.init();
        j_uploader.bind('FilesAdded',
                        function(up, files) {
                            j_uploader.start();
                        });

        j_uploader.bind('FileUploaded',function( uploader,file,responseObject){
            $("<div></div>").admin_select_dlg_ajax({
                "opt_type" : "select", // or "list"
                "url"      : "/user_manage/get_user_list",
                //其他参数
                "args_ex" : {
                    "type"       : "teacher",
                },
                select_primary_field   : "id",
                select_display         : "nick",
                select_no_select_value : 0,
                select_no_select_title : "[未设置]",
                //字段列表
                'field_list' :[
                    {
                        title:"id",
                        width :50,
                        field_name:"id"
                    },{
                        title:"手机",
                        field_name:"phone"
                    },{
                        title:"推荐人",
                        field_name:"nick"
                    }
                ] ,
                //查询列表
                filter_list:[[{
                    size_class : "col-md-8" ,
                    title      : "姓名/电话",
                    'arg_name' : "nick_phone",
                    type       : "input"
                }]],
                "auto_close" : true,
                "onChange"   : function(val,item) {
                    var phone="";
                    if (item.phone>0) {
                        phone=item.phone;
                    }
                    $.do_ajax("/ss_deal/add_teacher_lecture_appointment_origin",{
                        "phone"   : phone,
                        "id_list" : JSON.stringify(JSON.parse(responseObject.response).data)
                    });
                },
                "onLoadData" : null
            });
        });
    };

    upload_func("id_upload_csv_cp","/seller_student/upload_from_csv_cp");

    $(".opt-set-lecture-revisit-type").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_lecture_revisit_type = $("<select/>");
        var id_return_revisit_note = $("<textarea />");
        if(opt_data.full_time==0){
            Enum_map.append_option_list("lecture_revisit_type", id_lecture_revisit_type, true,[0,1,2,3,4] );
        }else{
            Enum_map.append_option_list("lecture_revisit_type", id_lecture_revisit_type, true,[0,2,5,6,8] );
        }
        var arr=[
            ["回访状态", id_lecture_revisit_type],
            ["备注",id_return_revisit_note]
        ];
        id_lecture_revisit_type.val(opt_data.lecture_revisit_type);
        id_return_revisit_note.val(opt_data.custom);
        $.show_key_value_table("修改状态", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/ss_deal/update_lecture_revisit_type',{
                    "id" : opt_data.id,
                    "lecture_revisit_type" : id_lecture_revisit_type.val(),
                    "custom":id_return_revisit_note.val()
                });
            }
        });
    });

    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id       = opt_data.id;

        var id_lecture_appointment_status = $("<select/>");
        var id_reference                  = $("<input/>");
        var id_phone                      = $("<input/>");
        var id_email                      = $("<input/>");
        var id_name                       = $("<input/>");
        var id_qq                         = $("<input/>");
        Enum_map.append_option_list("lecture_appointment_status", id_lecture_appointment_status, true );

        var arr = [
            ["老师姓名", id_name],
            ["QQ", id_qq],
            ["状态", id_lecture_appointment_status],
            ["推荐人号码",id_reference],
            ["号码",id_phone],
            ["邮箱",id_email]
        ];

        id_reference.val(opt_data.reference);
        id_phone.val(opt_data.phone);
        id_email.val(opt_data.email);
        id_lecture_appointment_status.val(opt_data.lecture_appointment_status);
        id_name.val(opt_data.name);
        id_qq.val(opt_data.qq);

        $.show_key_value_table("修改状态", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/ss_deal/update_lecture_appointment_status',{
                    "id"                         : id,
                    "lecture_appointment_status" : id_lecture_appointment_status.val(),
                    "reference"                  : id_reference.val(),
                    "phone"                      : id_phone.val(),
                    "email"                      : id_email.val(),
                    "name"                       : id_name.val(),
                    "qq"                         : id_qq.val(),
                });
            }
        });
    });

    $("#id_add_teacher_lecture_appointment").on("click",function(){
        var id_answer_begin_time            = $("<input />");
        var id_name                         = $("<input />");
        var id_phone                        = $("<input />");
        var id_email                        = $("<input />");
        var id_qq                           = $("<input />");
        var id_grade_ex                     = $("<select />");
        var id_subject_ex                   = $("<select />");
        var id_textbook                     = $("<input />");
        var id_school                       = $("<input />");
        var id_teacher_type                 = $("<select />");
        var id_reference                    = $("<input />");
        var id_self_introduction_experience = $("<textarea />");
        var id_lecture_revisit_type         = $("<select/>");

        id_answer_begin_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i:s',
            step:1
        });

        Enum_map.append_option_list("lecture_revisit_type", id_lecture_revisit_type, true,[0,1,2,3,4] );
        Enum_map.append_option_list_by_not_id("subject", id_subject_ex, true,[11] );
        Enum_map.append_option_list("grade_part", id_grade_ex, true );
        Enum_map.append_option_list("identity", id_teacher_type, true ,[0,5,6,7,8]);
        var arr=[
            ["报名时间", id_answer_begin_time],
            ["姓名", id_name],
            ["电话", id_phone],
            ["邮箱", id_email],
            ["qq", id_qq],
            ["年级段", id_grade_ex],
            ["科目", id_subject_ex],
            ["毕业院校", id_school],
            ["师资", id_teacher_type],
            ["推荐人(手机)", id_reference],
            ["回访状态", id_lecture_revisit_type],
        ];

        $.show_key_value_table("新增试讲预约", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/ss_deal/add_lecture_appointment_one',{
                    "answer_begin_time"    : id_answer_begin_time.val(),
                    "name"                 : id_name.val(),
                    "phone"                : id_phone.val(),
                    "email"                : id_email.val(),
                    "qq"                   : id_qq.val(),
                    "grade_ex"             : id_grade_ex.val(),
                    "subject_ex"           : id_subject_ex.val(),
                    "school"               : id_school.val(),
                    "teacher_type"         : id_teacher_type.val(),
                    "reference"            : id_reference.val(),
                    "lecture_revisit_type" : id_lecture_revisit_type.val()
                });
            }
        });
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        BootstrapDialog.confirm("确定要删除"+id+"？", function(val){
            if (val) {
                $.do_ajax( '/ss_deal/delete_lecture_appointment', {
                    'id' : id
                });
            }
        });
    });

    $("#id_update_lecture_appointment_status").on("click",function(){
        var opt_data       = $(this).get_opt_data();
        var select_id_list = [];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_id_list.push( $item.data("id") ) ;
            }
        } ) ;

        var id_lecture_appointment_status=$("<select/>");
        Enum_map.append_option_list("lecture_revisit_type", id_lecture_appointment_status, true,[0,1,2,3,4] );

        var arr=[
            ["状态",id_lecture_appointment_status]
        ];

        $.show_key_value_table("批量修改状态", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/ss_deal/update_lecture_appointment_status_list',{
                    "id_list":JSON.stringify(select_id_list ),
                    "lecture_appointment_status" : id_lecture_appointment_status.val()
                });
            }
        });
    });

    $("#id_all_through").on("click",function(){
        var select_id_list = [];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_id_list.push( $item.data("id") ) ;
            }
        });

        var id_time=$("<input/>");

        id_time.datetimepicker( {
            lang             : 'ch',
            timepicker       : true,
            format           : "Y-m-d H:i",
            onChangeDateTime : function(){
            }
        });
        var arr=[
            ["通过时间",id_time]
        ];

        $.show_key_value_table("设置通过时间",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
	              $.do_ajax("/teacher_test_class/set_teacher_through",{
                    "id_list"                : JSON.stringify(select_id_list ),
                    "train_through_new_time" : id_time.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });

            }
        });
    });



    $("#id_select_all").on("click",function(){
        $(".opt-select-item").iCheck("check");
    });

    $("#id_select_other").on("click",function(){
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ($item.iCheckValue() ) {
                $item.iCheck("uncheck");
            }else{
                $item.iCheck("check");
            }
        });
    });

    $("#id_set_zs_work_status").on("click",function(){
        $.do_ajax( "/ajax_deal2/get_admin_work_status",{
            "account_role" :8,
        },function(resp){
            var data = resp.data;
            var title = "调整工作状态";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>招师</td><td>状态</td><td>操作</td></tr></table></div>");


            $.each(data,function(i,item){
                html_node.find("table").append("<tr><td>"+item.account+"</td><td class=\"status_str\">"+item.admin_work_status_str+"</td><td class=\"edit_work_status\" data-uid=\""+item.uid+"\" data-status=\""+item.admin_work_status+"\"><a href=\"javascript:;\">调整</a></td></tr>");
            });
            html_node.find(".edit_work_status").on("click",function(){
                if(g_args.tea_adminid !=967 && g_args.tea_adminid !=448 && g_args.tea_adminid !=349 && g_args.tea_adminid != 72 && g_args.tea_adminid != 492){
                    alert("没有权限操作!");
                    return;
                }
                var m = $(this);
                var uid = $(this).data("uid");
                var status = $(this).data("status");
                var id_status = $("<select><option value=\"0\">休息</option><option value=\"1\">工作</option></select>");
                id_status.val(status);
                var arr =[
                    ["状态",id_status]
                ];
                $.show_key_value_table("修改状态", arr ,{
                    label    : '确认',
                    cssClass : 'btn-warning',
                    action   : function(dialog) {
                        $.do_ajax( '/ajax_deal2/set_admin_work_status',{
                            "adminid":uid,
                            "status":id_status.val()
                        },function(){
                            var status_str="工作";
                            if(id_status.val() ==0){
                                status_str="休息";
                            }
                            m.parent().find(".status_str").text(status_str);
                            dialog.close();
                        });
                    }
                });


            });


            var dlg=BootstrapDialog.show({
                title:title,
                message :  html_node   ,
                closable: false,
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
            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );

        });

    });
    $(".opt-return-back-new").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var phone = opt_data.phone;
        var id_return_revisit_origin = $("<select />");
        var id_return_revisit_note = $("<textarea />");
        //Enum_map.append_option_list("revisit_origin",id_return_revisit_origin,true,[1,2,3]);

        var arr = [
            [ "回访渠道",  id_return_revisit_origin] ,
            [ "回访记录",  id_return_revisit_note]
        ];

        $.show_key_value_table("录入回访信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/human_resource/add_lecture_revisit_record", {
                    "phone"          : phone,
                    "revisit_note"   : id_return_revisit_note.val(),
                    "revisit_origin" : id_return_revisit_origin.val()
                });
            }
        });
  });

    $(".opt-return-back-list").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var phone    = opt_data.phone;
    $.ajax({
      type     :"post",
      url      :"/human_resource/get_lecture_revisit_info",
      dataType :"json",
            size     : BootstrapDialog.SIZE_WIDE,
      data     : {"phone":phone},
      success  : function(result){
        var html_str="<table class=\"table table-bordered table-striped\"  > ";
                html_str+=" <tr><th> 时间  <th> 状态 <th> 负责人 <th>备注 </tr>   ";
        $.each( result.revisit_list ,function(i,item){
                    //console.log(item);
                    //return;
                    var revisit_person  ="";
                    if(item.revisit_person  ) {
                        revisit_person  = item.revisit_person;
                    }
          html_str=html_str+"<tr><td>"+item.revisit_time_str +"</td><td>"+item.revisit_origin_str+"</td><td>"+ item.sys_operator +"</td><td>"+item.revisit_note+" </td></tr>";
        } );



                var dlg=BootstrapDialog.show({
                    title: '回访记录',
                    message :  html_str ,
                    closable: true,
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            //dlg.setSize(BootstrapDialog.SIZE_WIDE);
                            dialog.close();
                        }
                    }]
                });

                if (!$.check_in_phone()) {
                    dlg.getModalDialog().css("width", "800px");
                }

      }
    });

  });

    $(".opt-more_grade").on("click",function(){
        var data       = $(this).get_opt_data();
        var id_email   = $("<input/>");

        var arr = [
            ["邮箱",id_email],
        ];
        id_email.val(data.email);

        $.show_key_value_table("发送邮件",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                var url = "/common_new/send_lecture_email";
                $.do_ajax(url,{
                    "id"     : data.id,
                    "email"  : id_email.val(),
                    "name"   : data.name,
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });
    });

    $(".show_detail").on("click",function(){
        var val = $(this).data("value");
        BootstrapDialog.alert({
            title: "数据",
            message:val ,
            closable: true,
            callback: function(){
            }
        });
    });

    $(".opt-edit-full_time").on("click",function(){
        var data           = $(this).get_opt_data();
        var id_flag        = $("<select/>");
        var id_record_info = $("<textarea/>");
        var flag_html      = "<option value='0'>不通过</option>"
            +"<option value='1'>通过</option>"
            +"<option value='2'>老师未到</option>"
            +"<option value='3'>待定</option>";
        id_flag.append(flag_html);
        id_flag.val(data.full_status);
        id_record_info.val(data.full_record_info);

        var arr = [
            ["是否通过",id_flag],
            ["面试评价",id_record_info],
        ];

        $.show_key_value_table("全职老师面试评价",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/tea_manage_new/set_full_time_teacher_record",{
                    "phone"       : data.phone,
                    "flag"        : id_flag.val(),
                    "record_info" : id_record_info.val(),
                    "nick"        : data.name,
                },function(result){
                    console.log(result);
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });
    });

    $(".opt-telphone").on("click",function(){
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
        });

    });


    if ( window.location.pathname=="/human_resource/teacher_lecture_appointment_info_full_time" || window.location.pathname=="/human_resource/teacher_lecture_appointment_info_full_time/") {
        $("#id_lecture_appointment_status").parent().parent().hide();
        $("#id_record_status").parent().parent().hide();
        $("#id_lecture_revisit_type").parent().parent().hide();
    }else{
        $("#id_lecture_appointment_status").parent().parent().hide();
        $("#id_record_status").parent().parent().hide();
        $("#id_lecture_revisit_type_new").parent().parent().hide();
        $("#id_fulltime_teacher_type").parent().parent().hide();
        $("#id_second_train_status").parent().parent().hide();
        $("#id_teacher_pass_type").parent().parent().hide();

    }

    $(".opt-trans_info").on("click",function(){
        var data = $(this).get_opt_data();
        BootstrapDialog.show({
            title   : "清除老师扩课信息",
            message : "是否清除老师扩课信息?",
            buttons : [{
                label  : "返回",
                action : function(dialog) {
                    dialog.close();
                }
            }, {
                label    : "确认",
                cssClass : "btn-warning",
                action   : function(dialog) {
                    $.do_ajax("/user_manage_new/reset_teacher_trans_subject",{
                        "id"    : data.id,
                        "phone" : data.phone,
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

    $(".opt-edit-hand").on("click",function(){
        var opt_data           = $(this).get_opt_data();
        var id_answer_begin_time            = $("<input />");
        var id_name                         = $("<input />");
        var id_email                        = $("<input />");
        var id_qq                           = $("<input />");
        var id_phone                        = $("<input />");
        var id_grade_ex                     = $("<select />");
        var id_subject_ex                   = $("<select />");
        var id_textbook                     = $("<input />");
        var id_school                       = $("<input />");
        var id_teacher_type                 = $("<select />");
        var id_reference                    = $("<input />");
        var id_self_introduction_experience = $("<textarea />");
        var id_lecture_revisit_type         = $("<select/>");

        id_answer_begin_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i:s',
            step:1
        });

        Enum_map.append_option_list("lecture_revisit_type", id_lecture_revisit_type, true,[0,1,2,3,4] );
        Enum_map.append_option_list("subject", id_subject_ex, true );
        Enum_map.append_option_list("grade_part", id_grade_ex, true );
        Enum_map.append_option_list("identity", id_teacher_type, true );
        var arr=[
            ["答题开始时间", id_answer_begin_time],
            ["姓名", id_name],
            ["邮箱", id_email],
            ["qq", id_qq],
            ["手机", id_phone],
            ["年级段", id_grade_ex],
            ["科目", id_subject_ex],
            ["毕业院校", id_school],
            ["师资", id_teacher_type],
            ["推荐人(手机)", id_reference],
            ["回访状态", id_lecture_revisit_type],
        ];
        id_answer_begin_time.val(opt_data.begin);
        id_name.val(opt_data.name);
        id_email.val(opt_data.email);
        id_qq.val(opt_data.qq);
        id_grade_ex.val(opt_data.grade_ex);
        id_subject_ex.val(opt_data.subject_ex);
        id_school.val(opt_data.school);
        id_teacher_type.val(opt_data.teacher_type);
        id_reference.val(opt_data.reference);
        id_lecture_revisit_type.val(opt_data.lecture_revisit_type);
        id_phone.val(opt_data.phone);

        $.show_key_value_table("修改试讲预约", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/ss_deal/update_lecture_appointment_info',{
                    "answer_begin_time"            : id_answer_begin_time.val(),
                    "name"                         : id_name.val(),
                    "email"                        : id_email.val(),
                    "qq"                           : id_qq.val(),
                    "phone"                        : id_phone.val(),
                    "grade_ex"                     : id_grade_ex.val(),
                    "subject_ex"                   : id_subject_ex.val(),
                    "school"                       : id_school.val(),
                    "teacher_type"                 : id_teacher_type.val(),
                    "reference"                    : id_reference.val(),
                    "lecture_revisit_type"         : id_lecture_revisit_type.val(),
                    "id"                           : opt_data.id
                });
            }
        });
    });

    $(".opt-1v1-lesson-set-new").on("click",function(){
        BootstrapDialog.alert("面试试讲暂时关闭！");
        return ;
       //  var opt_data          = $(this).get_opt_data();
       //  var id_subject        = $("<select/>");
       //  var id_grade          = $("<select/>");

       //  Enum_map.append_option_list("subject",id_subject,true);
       //  Enum_map.append_option_list("grade", id_grade,true,[100,200,300]);
       //  id_subject.val(opt_data.subject_ex);
       //  id_grade.val(opt_data.grade_ex);

       //  var arr = [
       //      ["科目",  id_subject ]  ,
       //      ["年级 ", id_grade]  ,
       //  ];

       //  $.show_key_value_table("选择科目年级", arr ,[{
       //      label    : '确认',
       //      cssClass : 'btn-warning',
       //      action   : function(dialog) {
       //          var subject = id_subject.val();
       //          //alert(subject);
       //          var grade = id_grade.val();
       //          if(subject >5){
       //              alert("小学科不能按此方式排课");
       //              return;
       //          }
       //          var title = "空闲时间选择";
       //          var html_node = $("<table class=\"table table-bordered table-striped\" id=\"cal_week\"><tr id=\"th_list_1\"><th width=\"120px\">时段</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr><tbody id=\"id_time_body_1\" > <tr data-timeid=\"09:00\"><td>09:00-09:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"09:30\"><td>09:30-10:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr> <tr data-timeid=\"10:00\"><td>10:00-10:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"10:30\"><td>10:30:11:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"11:00\"><td>11:00-11:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"11:30\"><td>11:30-12:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"12:00\"><td>12:00-12:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"12:30\"><td>12:30-13:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"13:00\"><td>13:00-13:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"13:30\"><td>13:30-14:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"14:00\"><td>14:00-14:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"14:30\"><td>14:30-15:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"15:00\"><td>15:00-15:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"15:30\"><td>15:30-16:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"16:00\"><td>16:00-16:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"16:30\"><td>16:30-17:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"17:00\"><td>17:00-17:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"17:30\"><td>17:30-18:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"18:00\"><td>18:00-18:30</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr><tr data-timeid=\"18:30\"><td>18:30-19:00</td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> </tr></tbody></table>");

       //          $.do_ajax('/user_deal/get_teacher_no_free_list',{
       //              "subject" : subject,
       //              "grade"   : grade
       //          },function(resp) {
       //              var userid_list   = resp.data;
       //              var next_day = g_args.next_day;
       //             // alert(next_day);
       //             // console.log(userid_list);
       //              var tb = html_node.find("#id_time_body_1").find("tr");
       //              var th = html_node.find("#th_list_1");
       //              var now_date = new Date(); //时间对象
       //              var now_time = now_date.getTime();

       //              th.each(function(){
       //                  var $this=$(this);
       //                  $this.find("th").each(function(i,item){
       //                      if (i!=0) {
       //                          var $th=$(item);
       //                          var tmp_date1=$.DateFormat(next_day+(i-2)*86400,"MM-dd" );
       //                          $th.text(tmp_date1);
       //                      }
       //                      //console.log(i);
       //                  });
       //              });
       //             // console.log(tb);

       //              $.each(userid_list,function(u,it){
       //                  //console.log(u);
       //                  console.log(it);
       //                  tb.each(function() {
       //                      var $this=$(this);
       //                      var timeid=$this.data("timeid");
       //                      $this.find("td").each(function(i,item){
       //                          if (i!=0) {//过滤１
       //                              var tmp_date=$.DateFormat(next_day+(i-2)*86400,"yyyy-MM-dd" );

       //                              var $td=$(item);
       //                              var tmt = tmp_date+" "+timeid;
       //                              var oldTime = (new Date(tmt)).getTime();
       //                            //  console.log(tmt);
       //                              if(tmt == it || now_time>=oldTime){
       //                                  $td.addClass("select_free_time");
       //                              }
       //                          }
       //                      });
       //                  });

       //              });

       //              tb.each(function() {
       //                  var $this=$(this);
       //                  var timeid=$this.data("timeid");
       //                  $this.find("td").each(function(i,item){
       //                      if (i!=0) {//过滤１
       //                          var tmp_date=$.DateFormat(next_day+(i-2)*86400,"yyyy-MM-dd" );

       //                          var $td=$(item);
       //                          var tmt = tmp_date+" "+timeid;
       //                          $td.on("click",function(){
       //                              if ($td.hasClass("select_free_time")) {
       //                                  alert("不能排课!");
       //                                  return;
       //                              }else{
       //                                  BootstrapDialog.show({
       //                                    title   : "排课",
       //                                    message : "确认排课么？",
       //                                    buttons : [{
       //                                      label  : "返回",
       //                                      action : function(dialog) {
       //                                        dialog.close();
       //                                      }
       //                                    }, {
       //                                      label    : "确认",
       //                                      cssClass : "btn-warning",
       //                                      action   : function(dialog) {
       //                                              $.do_ajax("/tea_manage_new/set_train_lesson_new",{
       //                                                  "subject":subject,
       //                                                  "grade"   :grade,
       //                                                  "id" :    opt_data.id,
       //                                                  "phone"            : opt_data.phone,
       //                                                  "tea_nick"         : opt_data.name,
       //                                                  "day"    :tmp_date,
       //                                                  "time": tmt
       //                                              },function(result){
       //                                                  if(result.ret==0){
       //                                                      window.location.reload();
       //                                                  }else{
       //                                                      BootstrapDialog.alert(result.info);
       //                                                  }
       //                                              })
       //                                      }
       //                                    }]
       //                                  });
       //                              }
       //                          });
       //                      }
       //                  });
       //              });
       //          });

       //          var dlg=BootstrapDialog.show({
       //              title:title,
       //              message :  html_node   ,
       //              closable: true,
       //              buttons:[{
       //                  label: '返回',
       //                  cssClass: 'btn',
       //                  action: function(dialog) {
       //                      dialog.close();

       //                  }
       //              }],
       //              onshown:function(){

       //              }

       //          });

       //          dlg.getModalDialog().css("width","1024px");


       //      }
       //  }]);

       // /* var teacherid = $(this).data("teacherid");
       //  if(teacherid > 0){
       //      var title = "学生详情";
       //      var html_node = $("<div id=\"div_table\"><div class=\"col-md-12\" id=\"div_grade\"><div class=\"col-md-2\">年级统计:</div></div><br><div class=\"col-md-12\" id=\"div_subject\"><div class=\"col-md-2\">科目统计:</div></div><br><br><br><table   class=\"table table-bordered \"><tr><td>id</td><td>名字</td><td>年级</td><td>科目</td><tr></table></div>");

       //      $.do_ajax('/tongji_ss/get_teacher_stu_info_new',{
       //          "teacherid" : teacherid
       //      },function(resp) {
       //          var userid_list   = resp.data;
       //          // console.log(userid_list);
       //          var grade_count   = resp.grade;
       //          var subject_count = resp.subject;
       //          for(var i in grade_count){
       //              html_node.find("#div_grade").append("<div class=\"col-md-1\">"+i+":"+grade_count[i]+"</div>");
       //          }
       //          for(var i in subject_count){
       //              html_node.find("#div_subject").append("<div class=\"col-md-1\">"+i+":"+subject_count[i]+"</div>");
       //          }*/

       //          /*html_node.prepend("<div class=\"col-md-12\"><div class=\"col-md-2\">年级统计:</div><div class=\"col-md-3\">小学:"+grade_count.primary+"</div><div class=\"col-md-3\">初中:"+grade_count.junior+"</div><div class=\"col-md-3\">高中:"+grade_count.senior+"</div></div><br><br><br>");*/

       //          /*$.each(userid_list,function(i,item){
       //              var userid = item["userid"];
       //              var name = item["nick"];
       //              var subject = item["subject_str"];
       //              var grade = item["grade_str"];
       //              html_node.find("table").append("<tr><td>"+userid+"</td><td>"+name+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
       //          });
       //      });

       //      var dlg=BootstrapDialog.show({
       //          title:title,
       //          message :  html_node   ,
       //          closable: true,
       //          buttons:[{
       //              label: '返回',
       //              cssClass: 'btn',
       //              action: function(dialog) {
       //                  dialog.close();

       //              }
       //          }],
       //          onshown:function(){

       //          }

       //      });

       //      dlg.getModalDialog().css("width","1024px");

       //  }*/

    });

    $(".opt-set-teacher-pass-type").on("click",function(){
        var data           = $(this).get_opt_data();
        var id_teacher_pass_type        = $("<select/>");
        var id_no_pass_reason = $("<textarea />");
        var flag_html      = "<option value='0'>未设置</option>"
            +"<option value='2'>未入职</option>";
        id_teacher_pass_type.append(flag_html);
        id_teacher_pass_type.val(data.teacher_pass_type);
        id_no_pass_reason.val(data.no_pass_reason);

        var arr = [
            ["入职状态",id_teacher_pass_type],
            ["理由",id_no_pass_reason],
        ];

        $.show_key_value_table("编辑入职状态",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/tea_manage_new/set_teacher_pass_type",{
                    "id"       : data.id,
                    "teacher_pass_type":id_teacher_pass_type.val(),
                    "no_pass_reason" : id_no_pass_reason.val()
                },function(result){
                    console.log(result);
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        });

    });

    $(".opt-set-part-teacher").on("click",function(){
        var data  = $(this).get_opt_data();
        var phone = data.phone;
        BootstrapDialog.confirm("确定设置为兼职老师", function(val){
            if (val) {
                $.do_ajax( '/ss_deal2/set_part_time_teacher', {
                    'phone' : phone
                });
            }
        });
    });

    $(".opt-set-teacher-info").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id       = opt_data.id;

        var id_name                       = $("<input/>");
        var id_phone                      = $("<input/>");
        var id_email                      = $("<input/>");
        var id_qq                         = $("<input/>");
        var id_reference                  = $("<input/>");
        var id_age                        = $("<input/>");
        var id_gender                     = $("<select/>");
        var id_grade_ex                   = $("<select/>");
        var id_subject_ex                 = $("<select/>");
        var id_identity                   = $("<select/>");
        var id_lecture_revisit_type       = $("<select/>");
        var id_return_revisit_note        = $("<textarea />");

        Enum_map.append_option_list("grade_part", id_grade_ex ,true);
        Enum_map.append_option_list("subject", id_subject_ex, true, [0,1,2,3,4,5,6,7,8,9,10]);
        Enum_map.append_option_list("identity", id_identity, true);
        Enum_map.append_option_list("gender", id_gender, true);
        if(opt_data.full_time==0){
            Enum_map.append_option_list("lecture_revisit_type", id_lecture_revisit_type, true,[0,1,2,3,4] );
        }else{
            Enum_map.append_option_list("lecture_revisit_type", id_lecture_revisit_type, true,[0,2,5,6,8] );
        }
        var red_font = "<font color='red'>";
        var red_font_end = "</font>";
        var arr = [
            ["-----","红色必填"],
            [red_font+"姓名"+red_font_end, id_name],
            [red_font+"电话"+red_font_end, id_phone],
            [red_font+"邮箱"+red_font_end,id_email],
            [red_font+"QQ"+red_font_end, id_qq],
            [red_font+"性别"+red_font_end,id_gender],
            [red_font+"年龄"+red_font_end,id_age],
            [red_font+"年级"+red_font_end,id_grade_ex],
            [red_font+"科目"+red_font_end,id_subject_ex],
            [red_font+"老师身份"+red_font_end,id_identity],
            ["推荐人号码",id_reference],
            ["回访状态", id_lecture_revisit_type],
            ["备注",id_return_revisit_note],
        ];

        id_name.val(opt_data.name);
        id_phone.val(opt_data.phone);
        id_email.val(opt_data.email);
        id_qq.val(opt_data.qq);
        id_reference.val(opt_data.reference);
        id_age.val(opt_data.age);
        id_gender.val(opt_data.gender);
        id_grade_ex.val(opt_data.grade_ex);
        id_subject_ex.val(opt_data.subject_ex);
        id_identity.val(opt_data.teacher_type);
        id_lecture_revisit_type.val(opt_data.lecture_revisit_type);
        id_return_revisit_note.val(opt_data.custom);

        $.show_key_value_table("修改状态", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/ss_deal/update_lecture_appointment_info',{
                    "id"                   : id,
                    "name"                 : id_name.val(),
                    "phone"                : id_phone.val(),
                    "email"                : id_email.val(),
                    "qq"                   : id_qq.val(),
                    "reference"            : id_reference.val(),
                    "age"                  : id_age.val(),
                    "gender"               : id_gender.val(),
                    "grade_ex"             : id_grade_ex.val(),
                    "subject_ex"           : id_subject_ex.val(),
                    "teacher_type"         : id_identity.val(),
                    "lecture_revisit_type" : id_lecture_revisit_type.val(),
                    "custom"               : id_return_revisit_note.val()
                });
            }
        });
    });

    $(".opt-plan-train_lesson").on("click",function(){
        var opt_data          = $(this).get_opt_data();
        var id_subject        = $("<select/>");
        var id_grade          = $("<select/>");
        var id_record_teacher = $("<input/>");
        var id_start_time     = $("<input/>");
        var full_time         = opt_data.full_time;

        id_start_time.datetimepicker( {
            lang             : 'ch',
            timepicker       : true,
            format           : "Y-m-d H:i",
            onChangeDateTime : function(){
            }
        });

        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("grade", id_grade,true,[100,200,300]);
        id_subject.val(opt_data.subject_ex);
        id_grade.val(opt_data.grade_ex);

        var arr = [
            ["审核老师",id_record_teacher],
            ["科目",id_subject],
            ["年级",id_grade],
            ["上课时间",id_start_time],
        ];

        $.show_key_value_table("排课", arr ,[{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/tea_manage_new/add_train_lesson_new",{
                    "phone"            : opt_data.phone,
                    "lesson_start"     : id_start_time.val(),
                    "subject"          : id_subject.val(),
                    "grade"            : id_grade.val(),
                    "record_teacherid" : id_record_teacher.val(),
                    "tea_nick"         : opt_data.name,
                    "id"               : opt_data.id
                });
            }
        }],function(){
            $.admin_select_user(id_record_teacher,"research_teacher");
        });
    });

    $("#id_add_teacher_lecture_appointment_for_test").on("click",function(){
        var id_teacher_type = $("<select />");
        var id_reference    = $("<input />");
        var id_num          = $("<input />");

        var arr = [
            ["老师身份",id_teacher_type],
            ["推荐人",id_reference],
            ["测试服务器性能问题","请添加20以下的数字"],
            ["添加个数",id_num],
        ];
        id_num.val(19);
        Enum_map.append_option_list_by_not_id("identity",id_teacher_type,true,[0]);
        $.show_key_value_table("添加测试报名数据",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_test_class/add_teacher_lecture_appointment_for_test",{
                    "teacher_type" : id_teacher_type.val(),
                    "reference"    : id_reference.val(),
                    "num"          : id_num.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        },function(){
            $.admin_select_user(id_reference,"teacher");
        });
    });

    $(".opt-test-through").on("click",function(){
        var data = $(this).get_opt_data();
        var id_train_through_new_time = $("<input>");
        id_train_through_new_time.datetimepicker( {
            lang             : 'ch',
            timepicker       : true,
            format           : "Y-m-d H:i",
            onChangeDateTime : function(){
            }
        });

        var arr = [
            ["通过时间",id_train_through_new_time]
        ];
        $.show_key_value_table("设置通过时间",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
	              $.do_ajax("/teacher_test_class/set_teacher_through",{
                    "phone"                  : data.phone,
                    "train_through_new_time" : id_train_through_new_time.val()
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });

            }
        });
    });

    $(".opt-set-teacher-subject-info").on("click",function(){
        var data = $(this).get_opt_data();

        BootstrapDialog.show({
	          title   : "重置老师扩课信息",
	          message : "重置老师扩课信息",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/human_resource/reset_teacher_trans_info",{
                        "id":data.id
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
