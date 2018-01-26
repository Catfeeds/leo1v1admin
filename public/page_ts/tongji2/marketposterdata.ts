/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-marketposterdata.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		    adminid:	$('#id_adminid').val()
		});
}
$(function(){


	  $('#id_adminid').admin_select_user_new({
		    "user_type"    : "account",
		    "select_value" : g_args.adminid,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_adminid",
		    "only_show_in_th_input"     : false,
		    "can_select_all_flag"     : true
	  });


    $('.showDetail').on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $uid = opt_data.uid;
        var html_node    = $.obj_copy_node("#id_assign_log");
        BootstrapDialog.show({
            title: "分配列表",
            message: html_node,
            closable: true
        });

        $.ajax({
            type: "post",
            url: "/ss_deal/getMarkePostertData",
            dataType: "json",
            data: {
                'uid': $uid,
            },
            success: function (result) {
                if (result['ret'] == 0) {
                    var data = result['data'];
                    var html_str = "";
                    $.each(data, function (i, item) {
                        var cls = "success";

                        html_str += "<tr class=\"" + cls + "\" > <td>" + item.studentid + "<td>" + item.phone + "<td>" + item.add_date +  "</tr>";
                    });

                    html_node.find(".data-body").html(html_str);
                }
            }
        });
    });

	$('.opt-change').set_input_change_event(load_data);
});

