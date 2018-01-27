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
        var html_txt = $.dlg_get_html_by_class('dlg_add_pic_info');
        html_txt=html_txt.
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" )
            .replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" )
        ;
        var html_node = $("<div></div>").html(html_txt);

        var url = "";

        BootstrapDialog.show({
            title           : "添加数据下载地址",
            message         : html_node,
            closable        : true,
            closeByBackdrop : false,
            onshown         : function(dialog){
            custom_qiniu_upload("id_upload_add_tmp","id_container_add_tmp",g_args.qiniu_upload_domain_url,true,
            function (up, info, file){
                var res = $.parseJSON(info);
                url = g_args.qiniu_upload_domain_url + res.key;
                html_node.find(".pic_url").html(url);
            });

            }
            ,buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                action : function(dialog) {
                $.do_ajax("/company_wx/update_approval_data_url",{
                    "id"       : id,
                    "data_url" : data_url
                });
                }
            },{
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });

        // var data_url = $('<input name=data_url>');
        // var arr = [
        //     ['添加数据下载地址', data_url]
        // ];

        // $.show_key_value_table("更新修改", arr ,{
        //     label    : '确认',
        //     cssClass : 'btn-warning',
        //     action   : function(dialog) {
        //         var data_url = $("input[name='data_url']").val();
        //         $.do_ajax("/company_wx/update_approval_data_url",{
        //             "id"       : id,
        //             "data_url" : data_url
        //         });
        //     }
        // });

    });

});
