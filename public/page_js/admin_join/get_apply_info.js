/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_join-get_apply_info.d.ts" />

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
                    
	$('.opt-change').set_input_change_event(load_data);
});
