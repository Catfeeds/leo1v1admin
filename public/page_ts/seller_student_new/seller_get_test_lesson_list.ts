/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-seller_get_test_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			adminid:	$('#id_adminid').val()
        });
    }


	$('#id_adminid').val(g_args.adminid);


	$('.opt-change').set_input_change_event(load_data);

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

});

