/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_apply_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            user_name: $("#id_user_name").val()
        });
    }


	$("#id_user_name").val(g_args.user_name);
    $(".for_input").on ("keypress",function(e){
		if (e.keyCode==13){
			var field_name=$(this).data("field");
			var value=$(this).val();
			load_data();
		}
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
    
    $(".id_add").on("click", function(){
        var $name=$("<input/>");
        var $phone=$("<input/>");
		var $post  = $("<input/>");
		var $dept  = $("<input/>");
        
        var arr=[
            ["姓名",$name] ,
            ["电话", $phone],
            ["岗位", $post],
            ["部门", $dept],
        ];
        $.show_key_value_table("新增面试信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                $.do_ajax ('/admin_join/add_info', {
                    'name': $name.val(), 
                    'phone': $phone.val(), 
                    'post': $post.val(), 
                    'dept': $dept.val(), 		 
			    });
            }
        });
    });

    $(".opt-del").on("click", function(){
        var phone = $(this).parent().data("phone");
        BootstrapDialog.confirm("要删除？！",function(val){
            if(val) {
                $.do_ajax("/admin_join/apply_del",{
                    "opt_type"  : "del" ,
                    "phone" : phone
                } );
            }

        } );
    });
                    
    $(".opt-set-trial-info").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var phone = opt_data.phone;
        var id_trial_dept = $("<input />");
        var id_trial_post = $("<input />");
        var id_trial_start_time = $("<input />");
        var id_trial_end_time = $("<input />");
        id_trial_end_time.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d',
            "onChangeDateTime" : function() {
            }

	    });
        id_trial_start_time.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d',
            "onChangeDateTime" : function() {
            }

	    });


        id_trial_end_time.val( opt_data.trial_end_time_str);
        id_trial_start_time.val( opt_data.trial_start_time_str);
        id_trial_dept.val( opt_data.trial_dept);
        id_trial_post.val( opt_data.trial_post);
	    
        var arr = [
            [ "试用部门",  id_trial_dept] ,
            [ "试用岗位",  id_trial_post] ,
            [ "试用期开始时间",  id_trial_start_time] ,
            [ "试用期结束时间",  id_trial_end_time] ,
        ];

        $.show_key_value_table("修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/admin_join/update_trial_info", {
                    "phone":phone,
                    "trial_dept":id_trial_dept.val(),
                    "trial_post":id_trial_post.val(),
                    "trial_start_time":id_trial_start_time.val(),
                    "trial_end_time":id_trial_end_time.val()
                });
            }
        });
    });

	$('.opt-change').set_input_change_event(load_data);
});
