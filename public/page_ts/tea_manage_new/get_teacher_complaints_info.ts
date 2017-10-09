/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage_new-get_teacher_complaints_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			id:	$('#id_id').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			adminid:	$('#id_adminid').val(),
			accept_adminid:	$('#id_accept_adminid').val(),
			accept_adminid_flag:	$('#id_accept_adminid_flag').val(),
			require_adminid:	$('#id_require_adminid').val()
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
	$('#id_id').val(g_args.id);
	$('#id_adminid').val(g_args.adminid);
	$('#id_accept_adminid').val(g_args.accept_adminid);
	$('#id_accept_adminid_flag').val(g_args.accept_adminid_flag);
	$('#id_require_adminid').val(g_args.require_adminid);
    $.admin_select_user(
        $('#id_require_adminid'),
        "admin", load_data,false,{"main_type":3});
    $.admin_select_user(
        $('#id_accept_adminid'),
        "admin", load_data,false,{"main_type":4});


    if (window.location.pathname=="/tea_manage_new/get_teacher_complaints_info_jw" || window.location.pathname=="/tea_manage_new/get_teacher_complaints_info_jw/") {
        $(".opt-edit").hide();
    }else{
        $(".opt-edit-new").hide();
        $(".opt-del").hide();
    } 

    $(".opt-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;            
        var id_record_scheme = $("<textarea />");
        var id_record_scheme_url = $("<div><input class=\"record_scheme_url\" id=\"record_scheme_url\" type=\"text\" readonly><span ><a class=\"upload_gift_pic\" id=\"id_upload_record_scheme\" href=\"javascript:;\">上传</a></span></div>");
        
        var arr = [
            [ "处理方案",  id_record_scheme],
            [ "相关图片(可不传)",  id_record_scheme_url] 
        ];

        id_record_scheme.val(opt_data.record_scheme);
        id_record_scheme_url.find("#record_scheme_url").val(opt_data.surl);      
        
        $.show_key_value_table("提交处理方案", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/set_teacher_complaints_record_scheme", {                   
                    "id"                     : id,
                    "record_scheme"          : id_record_scheme.val(),
                    "record_scheme_url"      : id_record_scheme_url.find("#record_scheme_url").val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_record_scheme',1,function (up, info, file) {
                var res = $.parseJSON(info);                
                $("#record_scheme_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);  
        
        });
	});
    
    $(".show_pic").on('click',function(){
        var url = $(this).data("url");
        $.wopen(url);
        
    });
    

    $(".opt-edit-new").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        if(opt_data.accept_time >0){
            alert("该反馈已有处理方案,不能修改");
            return;
        }      
        var id_complaints_info = $("<textarea />");
        var id_complaints_info_url = $("<div><input class=\"complaints_info_url\" id=\"complaints_info_url\" type=\"text\"readonly ><div><span ><a class=\"upload_gift_pic\" id=\"id_upload_complaints_info\" href=\"javascript:;\">上传</a></span><span><a style=\"margin-left:20px\" href=\"javascript:;\" id=\"id_del_complaints_info\">删除</a></span></div></div>");
        var arr = [
            ["投诉内容",  id_complaints_info],
            ["相关图片",  id_complaints_info_url ]
        ];
        id_complaints_info.val(opt_data.complaints_info);
        id_complaints_info_url.find("#complaints_info_url").val(opt_data.curl);      

        id_complaints_info_url.find("#id_del_complaints_info").on("click",function(){
            id_complaints_info_url.find("#complaints_info_url").val("");
        });

        $.show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var url = id_complaints_info_url.find("#complaints_info_url").val();

                $.do_ajax("/user_deal/update_complaints_teacher_info", {
                    "id":id,
                    "complaints_info": id_complaints_info.val(),
                    "complaints_info_url": id_complaints_info_url.find("#complaints_info_url").val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_complaints_info',true,function (up, info, file) {
                var res = $.parseJSON(info);

                $("#complaints_info_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

        });
 
     

	});
    
    $(".opt-del").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        if(opt_data.accept_time >0){
            alert("该反馈已有处理方案,不能删除");
            return;
        }      


        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_complaints_teacher_info', {
                    'id' : id
                });
            } 
        });

    });

    $(".opt-del-new").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;      
        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_complaints_teacher_info', {
                    'id' : id
                });
            } 
        });

    });

    
   
    download_hide();

	$('.opt-change').set_input_change_event(load_data);
});








