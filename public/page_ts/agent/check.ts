/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-assign_sub_adminid_list.d.ts" />
function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){
    //实例化一个plupload上传对象
    var uploader = new plupload.Uploader({
        browse_button : 'id_add_lesson_by_excel', //触发文件选择对话框的按钮，为那个元素id
        url : '/tea_manage_new/add_open_class_by_xls_new', //服务器端的上传页面地址
        flash_swf_url       : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [
                { title : "xls files", extensions : "xls" },
                { title : "xlsx files", extensions : "xlsx" }
            ],
            max_file_size      : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });

    uploader.init();
    uploader.bind('FilesAdded',function(up, files) {
        uploader.start();
    });

    if(g_args.account == 'tom'){
        window.download_show();
    }

    $('.opt-change').set_input_change_event(load_data);
});
