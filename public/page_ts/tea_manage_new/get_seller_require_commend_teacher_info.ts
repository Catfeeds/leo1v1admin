/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_seller_require_commend_teacher_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            accept_adminid:$('#id_accept_adminid').val(),
            require_adminid:$('#id_require_adminid').val()
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
    $('#id_accept_adminid').val(g_args.accept_adminid);
    $('#id_require_adminid').val(g_args.require_adminid);
    $.admin_select_user(
        $('#id_require_adminid'),
        "admin", load_data,false,{"main_type":1});
    $.admin_select_user(
        $('#id_accept_adminid'),
        "admin", load_data,false,{"main_type":4});


    if ( window.location.pathname=="/tea_manage_new/get_seller_require_commend_teacher_info_seller/" || window.location.pathname=="/tea_manage_new/get_seller_require_commend_teacher_info_seller") {
        $(".opt-edit").hide();
        $("#id_add_seller_and_ass_record").hide();
    }else{
        $(".opt-edit-new").hide();
        $(".opt-del").hide();
    } 

   
    
    
    $(".opt-del").on("click",function(){        
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        if(opt_data.accept_time >0){
            alert("该申请已有处理方案,不能删除");
            return;
        }      

        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_teacher_require_deal', {
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
                $.do_ajax( '/user_deal/del_teacher_require_deal', {
                    'id' : id
                });
            } 
        });


    });

    $(".opt-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;
        //alert(id);
        var id_accept_reason = $("<textarea />");             
        // var id_record_teacher  = $("<textarea />");             
        var id_record_teacher = $("<input />");             
        var id_accept_flag = $("<select />");            
        Enum_map.append_option_list( "set_boolean",id_accept_flag,true,[1,2]);
        var arr = [
            [ "是否接受",  id_accept_flag],
            [ "推荐老师",  id_record_teacher],
            [ "备注(驳回理由)",  id_accept_reason] 
        ];

        id_accept_reason.val(opt_data.accept_reason);
        id_record_teacher.val(opt_data.record_teacher);
        id_accept_flag.on("click",function(){
            if(id_accept_flag.val() ==1){
                id_accept_reason.parent().parent().show();
                id_record_teacher.parent().parent().show();
            }else{
                id_accept_reason.parent().parent().show();
                id_record_teacher.parent().parent().hide();
            }

        });
        
        $.show_key_value_table("推荐老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/set_seller_commend_teacher_info", {
                    "id"                     : id,
                    "accept_reason"          : id_accept_reason.val(),
                    "record_teacher"         : id_record_teacher.val(),
                    "accept_flag"            : id_accept_flag.val()
                });
            }
        },function(){
            if(id_accept_flag.val() ==1){
                id_accept_reason.parent().parent().show();
                id_record_teacher.parent().parent().show();
            }else{
                id_accept_reason.parent().parent().show();
                id_record_teacher.parent().parent().hide();
            }

        });
	});

    
    $(".opt-edit-new").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if(opt_data.accept_time >0){
            alert("该申请已有处理方案,不能删除");
            return;
        }      

        var id_except_teacher = $("<textarea />") ;
        var arr=[
            ["备注(特殊要求)",id_except_teacher]
        ];
        id_except_teacher.val(opt_data.except_teacher);
        $.show_key_value_table("修改申请", arr, {
            label    : '提交',
            cssClass : 'btn-danger',
            action   : function(dialog) {
                $.do_ajax("/user_deal/update_seller_require_commend_teacher", {
                    "except_teacher"             : id_except_teacher.val(),
                    "id"                         : opt_data.id
                });
            }
        });
        
    });

   
	$('.opt-change').set_input_change_event(load_data);
});







