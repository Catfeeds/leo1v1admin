/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_tongji_cr.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		name:	$('#id_name').val()
		});
}
$(function(){


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

    $('#id_order_by_str').val(g_args.order_by_str);
    $('#id_name').val(g_args.name);

    $(".apply_num").on("click",function(){
         var nick = $(this).data("nick");
         var opt_date_type = $('#id_opt_date_type').val();
         var start_time    = $('#id_start_time').val();
         var end_time      = $('#id_end_time').val();
        window.open(
             '/user_manage/refund_list?is_test_user=0&has_money=1&assistant_nick='+nick+ "&opt_date_type=" + opt_date_type + "&start_time=" + start_time + "&end_time=" + end_time
         );
    });

    $(".one_month").on("click",function(){
         var opt_data=$(this).get_opt_data();
         var adminid = $(this).data("id");
         var opt_date_type = $('#id_opt_date_type').val();
         var start_time    = $('#id_start_time').val();
         var end_time      = $('#id_end_time').val();
         window.open(
             '/user_manage/contract_list?assistantid='+ adminid + "&contract_status=3&has_money=1" + "&opt_date_type=" + opt_date_type + "&start_time=" + start_time + "&end_time=" + end_time
         );
    });

    $(".detail_info").on("click",function(){
        var assistantid = $(this).data("userid");
        group_name = $(this).data("group_name");
        name = $(this).data("name");
        $.do_ajax("/ss_deal/get_assistant_info_by_id", {
            "assistantid" : assistantid,
        }, function (ret) {
            if(ret != 0){
                var gender = ret.data.gender;
                var age    = ret.data.age;
                var nick   = ret.data.nick;
                var id_nick = $("<input readonly='readonly'/>");  
                var id_age = $("<input readonly='readonly'/>");  
                var id_gender  = $("<input readonly='readonly'/>");   
                var id_group_name = $("<input readonly='readonly' />");
                var id_name = $("<input readonly='readonly' />");

                Enum_map.append_option_list("gender", id_gender, true);

                id_nick.val(nick);
                id_gender.val(gender);
                id_age.val(age);
                id_group_name.val(group_name);
                id_name.val(name);

                var arr = [
                    ["姓名", id_nick],
                    ["性别", id_gender],
                    ["年龄", id_age],
                    ["校区", id_group_name],
                    ["小组", id_name]
                ];
                $.show_key_value_table("助教信息", arr, {
                    cssClass :  'btn-waring',
                    action   :   function(dialog){
                    }
                });
            }
        });
    });


	$('.opt-change').set_input_change_event(load_data);
});
