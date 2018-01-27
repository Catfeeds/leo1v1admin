/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/company_wx-show_approv_data.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		});
}
$(function(){

	  $('.opt-change').set_input_change_event(load_data);

    $('.opt-edit').on('click', function () {
        var id = $(this).parent().attr('data_id');
        var data_url = $('<input name=data_url>');
        var arr = [
            ['添加数据下载地址', data_url]
        ];

        $.show_key_value_table("更新修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var data_url = $("input[name='data_url']").val();
                $.do_ajax("/company_wx/update_approval_data_url",{
                    "id"       : id,
                    "data_url" : data_url
                });
            }
        });

    });

});
