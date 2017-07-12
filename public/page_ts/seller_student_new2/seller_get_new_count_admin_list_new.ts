/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_get_new_count_admin_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			adminid:	$('#id_adminid').val()
        });
    }

    $(".common-table" ).tbody_scroll_table();
    $(".common-table" ).table_admin_level_4_init();

	  $('#id_adminid').val(g_args.adminid);

    $(".opt-detail").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/seller_student_new2/seller_get_new_count_list?adminid="+opt_data.adminid );
    });

	  $('.opt-change').set_input_change_event(load_data);
});

