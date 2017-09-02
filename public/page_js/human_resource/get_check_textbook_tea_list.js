/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_check_textbook_tea_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
		        page_count          :	$('#id_page_count').val(),
            textbook_check_flag : $("#id_textbook_check_flag").val(),
        });
    }
    $(".fa-download").hide();

    $(".opt-teacher-info").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var textbook = opt_data["teacher_textbook"];

        $.do_ajax("/user_deal/get_teacher_textbook",{
            "textbook" : textbook
        },function(response){
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["num"], this["textbook"]  ]);
                if (this["has_textbook"]) {
                    select_list.push (this["num"]) ;
                }
            });

            $(this).admin_select_dlg({
                header_list     : [ "id","教材版本" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/user_deal/set_teacher_textbook",{
                        "teacherid"     : opt_data.teacherid,
                        "textbook_list" : JSON.stringify(select_list),
                        "old_textbook"  : opt_data.teacher_textbook,
                    });
                }
            });
        }) ;
    });

	  $('.opt-change').set_input_change_event(load_data);

    Enum_map.append_option_list("boolean",$("#id_textbook_check_flag"));
    $("#id_textbook_check_flag").val(g_args.textbook_check_flag);

});
