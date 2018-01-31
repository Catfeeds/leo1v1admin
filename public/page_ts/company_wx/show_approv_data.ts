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

        var data_url = "";

        BootstrapDialog.show({
            title           : "添加数据下载地址",
            message         : html_node,
            closable        : true,
            closeByBackdrop : false,
            onshown         : function(dialog){
            custom_qiniu_upload("id_upload_add_tmp","id_container_add_tmp",g_args.qiniu_upload_domain_url,true,
            function (up, info, file){
                var res = $.parseJSON(info);
                data_url = g_args.qiniu_upload_domain_url + res.key;
                html_node.find(".data_url").html(data_url);
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

    $(".opt-add-page").on("click", function() {
        var id = $(this).parent().attr('data_id');
        var url = $(this).parent().parent().find(".page_url").attr("href");
        if (url == undefined) url = '';
        var page_url = $('<input name=page_url value="' + url + '">');
        var arr = [
            ['-',"数据地址-控制器应该以require加数字命名"],
            ['添加数据下载地址', page_url]
        ];

        $.show_key_value_table("更新修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var page_url = $("input[name='page_url']").val();
                if (!(page_url.indexOf("require") > -1)) {
                    alert("数据地址-控制器应该以require加数字命名");
                    return false;
                }
                $.do_ajax("/company_wx/update_approval_page_url",{
                    "id"       : id,
                    "page_url" : page_url
                });
            }
        });

    });

    $('#id_add').on('click', function() {
        $.do_ajax("/company_wx/pull_approve_data", {});
    });

    $(".download").on("click", function() {
        $.do_ajax("/company_wx/download_log",{});
    });

});
