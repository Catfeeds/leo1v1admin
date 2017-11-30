/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-admin_card_log_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			adminid:	$('#id_adminid').val()
        });
    }


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
	$('#id_adminid').val(g_args.adminid);
    $.admin_select_user( $("#id_adminid"),"admin", load_data ,false, {"main_type" : -1});


	$('.opt-change').set_input_change_event(load_data);
    var upload_func=function(id,url) {
        var j_uploader = new plupload.Uploader({
            browse_button : id, //触发文件选择对话框的按钮，为那个元素id
            url : url, //服务器端的上传页面地址
            flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
            silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
            filters: {
                mime_types : [ //只允许上传图片和zip文件
                    { title : "xls files", extensions : "xls" }
                ],
                max_file_size : '40m', //最大只能上传400kb的文件
                prevent_duplicates : true //不允许选取重复文件
            }
        });  


        j_uploader.init();

        j_uploader.bind('FilesAdded',
                        function(up, files) {
                            
                            j_uploader.start();
                        });

        j_uploader.bind('FileUploaded',
                        function( uploader,file,responseObject) {
                            alert( responseObject.response );
                            window.location.reload();
                        });

    };

    upload_func( "id_upload_xls", "/tongji/upload_from_xls_card_log" );

    if(g_account=="程燕"){
        download_show();
    }
});


