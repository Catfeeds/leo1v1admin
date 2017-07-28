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
			      have_wx                    : $('#id_have_wx').val(),
			      full_time                  : $('#id_full_time').val(),
        });
    }

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
    Enum_map.append_option_list("subject", $('#id_subject'));
    Enum_map.append_option_list("boolean", $('#id_have_wx'));
    Enum_map.append_option_list("lecture_revisit_type", $('#id_lecture_revisit_type'));
    Enum_map.append_option_list("boolean", $('#id_full_time'));
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
    $.enum_multi_select($("#id_teacher_ref_type"),"teacher_ref_type", function( ){
        load_data();
    });


    $.admin_select_user( $("#id_teacherid"), "teacher", load_data);

    $('.opt-change').set_input_change_event(load_data);
    
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
        Enum_map.append_option_list("lecture_revisit_type", id_lecture_revisit_type, true );
        var arr=[
            ["回访状态", id_lecture_revisit_type],
        ];
        id_lecture_revisit_type.val(opt_data.lecture_revisit_type);
        $.show_key_value_table("修改状态", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/ss_deal/update_lecture_revisit_type',{
                    "id" : opt_data.id,
                    "lecture_revisit_type" : id_lecture_revisit_type.val()
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
        Enum_map.append_option_list("lecture_appointment_status", id_lecture_appointment_status, true );

        var arr=[
            ["状态", id_lecture_appointment_status],
            ["推荐人号码",id_reference],
            ["号码",id_phone],
            ["邮箱",id_email]
        ];
        id_reference.val(opt_data.reference);
        id_phone.val(opt_data.phone);
        id_email.val(opt_data.email);
        id_lecture_appointment_status.val(opt_data.lecture_appointment_status);

        $.show_key_value_table("修改状态", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/ss_deal/update_lecture_appointment_status',{
                    "id" : id,
                    "lecture_appointment_status" : id_lecture_appointment_status.val(),
                    "reference" : id_reference.val(),
                    "phone"     : id_phone.val(),
                    "email"     : id_email.val()
                });
            }
        });
    });

    $("#id_add_teacher_lecture_appointment").on("click",function(){
        var id_answer_begin_time            = $("<input />");
        var id_answer_end_time              = $("<input />");
        var id_name                         = $("<input />");
        var id_phone                        = $("<input />");
        var id_email                        = $("<input />");
        var id_custom                       = $("<input />");
        var id_grade_ex                     = $("<input />");
        var id_subject_ex                   = $("<input />");
        var id_textbook                     = $("<input />");
        var id_school                       = $("<input />");
        var id_teacher_type                 = $("<input />");
        var id_reference                    = $("<input />");
        var id_self_introduction_experience = $("<textarea />");
        var id_lecture_appointment_status   = $("<select/>");        

        id_answer_end_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i:s',
            step:1
        });
        id_answer_begin_time.datetimepicker({
            datepicker:true,
            timepicker:true,
            format:'Y-m-d H:i:s',
            step:1
        });

        Enum_map.append_option_list("lecture_appointment_status", id_lecture_appointment_status, true );
        var arr=[
            ["答题开始时间", id_answer_begin_time],         
            ["答题结束时间", id_answer_end_time],           
            ["自定义字段", id_custom],          
            ["姓名", id_name],           
            ["电话", id_phone],          
            ["邮箱", id_email],          
            ["年级段", id_grade_ex],         
            ["科目", id_subject_ex],                     
            ["教材", id_textbook],           
            ["毕业院校", id_school],           
            ["师资", id_teacher_type],           
            ["自我介绍及经验", id_self_introduction_experience],           
            ["推荐人", id_reference],           
            ["状态", id_lecture_appointment_status],         
        ];

        $.show_key_value_table("新增试讲预约", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/ss_deal/add_lecture_appointment_one',{
                    "answer_begin_time"            : id_answer_begin_time.val(),
                    "answer_end_time"              : id_answer_end_time.val(),
                    "custom"                       : id_custom.val(),
                    "name"                         : id_name.val(),
                    "phone"                        : id_phone.val(),
                    "email"                        : id_email.val(),
                    "grade_ex"                     : id_grade_ex.val(),
                    "subject_ex"                   : id_subject_ex.val(),
                    "textbook"                     : id_textbook.val(),
                    "school"                       : id_school.val(),
                    "teacher_type"                 : id_teacher_type.val(),
                    "self_introduction_experience" : id_self_introduction_experience.val(),
                    "reference"                    : id_reference.val(),
                    "lecture_appointment_status"   : id_lecture_appointment_status.val()                                
                });
            }
        });
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        BootstrapDialog.confirm("确定要删除？", function(val){
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
        Enum_map.append_option_list("lecture_appointment_status", id_lecture_appointment_status, true );
        
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

    $(".opt-return-back-new").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var phone = opt_data.phone;
        var id_return_revisit_origin = $("<select />");
        var id_return_revisit_note = $("<textarea />");
        Enum_map.append_option_list("revisit_origin",id_return_revisit_origin,true,[1,2,3]);              
        
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
                html_str+=" <tr><th> 时间  <th> 回访渠道 <th> 负责人 <th>内容 </tr>   ";
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

        $.show_key_value_table("扩课年级",arr,{
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

    $(".opt-trans_grade").on("click",function(){
        var data = $(this).get_opt_data();
        $.do_ajax("/tea_manage/get_teacher_info_by_phone",{
            "phone" : data.phone
        },function(result){
            var id_subject     = $("<select/>");
            var id_grade_start = $("<select/>");
            var id_grade_end   = $("<select/>");
            var id_not_grade   = $("<div />");
            var id_textbook    = $("<div />");
            var textbook_arr   = check_data_to_arr(data.tea_textbook,",");

            var arr  = [
                ["科目",id_subject],
                ["开始年级",id_grade_start],
                ["结束年级",id_grade_end],
                ["禁止年级",id_not_grade],
                ["教材版本",id_textbook],
            ];

            var grade_start = 0;
            var grade_end   = 0;
            var subject     = 0;
            var not_grade   = "";

            if(result.res.length>0){
                grade_start = result.res.grade_start;
                grade_end   = result.res.grade_end;
                subject     = result.res.subject;
                not_grade   = result.res.not_grade;
            }else{
                grade_start = data.grade_start;
                grade_end   = data.grade_end;
                subject     = data.tea_subject;
                not_grade   = data.not_grade;
            }
            var not_grade_arr = check_data_to_arr(not_grade,",");
            Enum_map.append_option_list("subject",id_subject,true);
            Enum_map.append_option_list("grade_range",id_grade_start,true);
            Enum_map.append_option_list("grade_range",id_grade_end,true);
            Enum_map.append_checkbox_list("grade",id_not_grade,"not_grade");
            Enum_map.append_checkbox_list("region_version",id_textbook,"textbook");
            id_grade_start.val(grade_start);
            id_grade_end.val(grade_end);
            id_subject.val(subject);

            $.show_key_value_table("设置年级",arr,{
                label    : "确认",
                cssClass : "btn-warning",
                action   : function(dialog) {
                    var not_grade = "";
                    $("input[name='not_grade']:checked").each(function(){
                        if(not_grade==""){
                            not_grade = $(this).val();
                        }else{
                            not_grade += ","+$(this).val();
                        }
                    });
                    var check_textbook="";
                    $("input[name='textbook']:checked").each(function(){
                        if(check_textbook==""){
                            check_textbook = $(this).val();
                        }else{
                            check_textbook += ","+$(this).val();
                        }
                    });

                    BootstrapDialog.show({
	                    title   : "发送确认",
	                    message : "确定给老师发送扩年级课件么？",
	                    buttons : [{
		                    label  : "返回",
		                    action : function(dialog) {
			                    dialog.close();
		                    }
	                    }, {
		                    label    : "确认",
		                    cssClass : "btn-warning",
		                    action   : function(dialog) {
                                // $.do_ajax("/human_resource/set_teacher_appointment_info",{
                                //     "id"          : data.id,
                                //     "grade_start" : id_grade_start.val(),
                                //     "grade_end"   : id_grade_end.val(),
                                //     "not_grade"   : not_grade,
                                //     "textbook"    : check_textbook,
                                //     "subject"     : id_subject.val(),
                                // },function(result){
                                //     var info = "";
                                //     if(result.ret==0){
                                //         info = "扩年级试讲已发送。"
                                //         dialog.close();
                                //     }else{
                                //         info=result.info;
                                //     }
                                //     BootstrapDialog.alert(result.info);
                                // })
		                    }
	                    }]
                    });
                }
            },function(){
                var check_not="";
                if(not_grade_arr[0]){
                    $.each(not_grade_arr,function(k,v){
                        $("input[name='not_grade']").each(function(){
                            check_not=$(this).val();
                            if(check_not==v){
                                $(this).attr("checked","true");
                            }
                        });
                    });
                }
                if(textbook_arr[0]){
                    $.each(textbook_arr,function(k,v){
                        $("input[name='textbook']").each(function(){
                            check_not=$(this).val();
                            if(check_not==v){
                                $(this).attr("checked","true");
                            }
                        });
                    });
                }
                id_subject.on("change",function(){
                    var subject  = $(this).val();
                    var textbook = "all_textbook";
                    if(subject==3){
                        textbook = "english_textbook";
                    }
                    id_textbook.empty();
                    Enum_map.append_checkbox_list(textbook,id_textbook,"textbook");
                });
            });
        })
    });

    $(".opt-plan-train_lesson").on("click",function(){
        var opt_data          = $(this).get_opt_data();
        var id_subject        = $("<select/>");
        var id_grade          = $("<select/>");
        var id_record_teacher = $("<input/>");
        var id_start_time     = $("<input/>");
        
        id_start_time.datetimepicker( {
            lang       : 'ch',
            timepicker : true,
            format     : "Y-m-d H:i",
            onChangeDateTime :function(){
            }
        });

        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("grade", id_grade,true,[100,200,300]);
        id_subject.val(opt_data.subject_num);
       
        var arr = [
            ["审核老师",  id_record_teacher ]  ,
            ["科目",  id_subject ]  ,
            ["年级 ", id_grade]  ,
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
                    "tea_nick"         : opt_data.name
                });
            }
        }],function(){
            $.admin_select_user( id_record_teacher, "research_teacher");
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

    if(g_args.tea_adminid !=349 && g_args.tea_adminid !=72 && g_args.tea_adminid !=448){
        $(".fa-download").hide();
        $(".page-opt-show-all-xls").hide();
    }

     

});
