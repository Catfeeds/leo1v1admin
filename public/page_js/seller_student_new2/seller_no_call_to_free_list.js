/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_no_call_to_free_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			page_count:	$('#id_page_count').val(),
			global_tq_called_flag:	$('#id_global_tq_called_flag').val(),
			seller_student_status:	$('#id_seller_student_status').val()

        });
    }



	Enum_map.append_option_list("tq_called_flag",$("#id_global_tq_called_flag")); 
	Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status")); 

	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_global_tq_called_flag').val(g_args.global_tq_called_flag);
	$('#id_seller_student_status').val(g_args.seller_student_status);
	$('#id_page_count').val(g_args.page_count);

    $.admin_select_user(
        $('#id_admin_revisiterid'),
        "admin", load_data ,false, {
            "main_type": 2, //分配用户
        }
    );


	$('.opt-change').set_input_change_event(load_data);
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
        } );
    });

    $("#id_set_select_list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var select_userid_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_userid_list.push( $item.data("userid") ) ;
            }
        } ) ;

        $.do_ajax(
            '/ss_deal/free_to_new_user',
            {
                'userid_list' : JSON.stringify(select_userid_list )
            });
    });

});


