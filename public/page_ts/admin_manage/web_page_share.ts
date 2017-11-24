/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-web_page_share.d.ts" />


function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    web_page_id: g_args.web_page_id	,
		    uid:	$('#id_uid').val(),
		    account_role:	$('#id_account_role').val()
    });
}
$(function(){

	  Enum_map.append_option_list("account_role",$("#id_account_role"));

	  $('#id_uid').val(g_args.uid);
	  $('#id_account_role').val(g_args.account_role);

    $.admin_select_user(
        $('#id_uid'),
        "admin", load_data ,false, {
            "main_type": -1, //分配用户
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


    $("#id_send").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var select_userid_list=[];

        $(".opt-select-item").each(function(){
            var $item=$(this) ;
            if($item.iCheckValue()) {
                select_userid_list.push( $item.data("userid") ) ;
            }
        });


        var arr=[
            ["标题", g_args.web_page_title ],
            ["url", g_args.web_page_url ],
        ];

        $.show_key_value_table("发送", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/ajax_deal2/web_page_info_send_admin', {
                    'userid_list' : JSON.stringify(select_userid_list ),
                    "web_page_id" : g_args.web_page_id,
                });

            }
        });


    });

});
