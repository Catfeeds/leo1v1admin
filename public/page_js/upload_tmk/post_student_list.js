/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/upload_tmk-post_student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            postid: g_args.postid,
            is_new_flag:	$('#id_is_new_flag').val(),

        });
    }

    var uploader = $.plupload_Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/upload_tmk/upload_xls?postid='+g_args.postid , //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xlsx files", extensions : "xlsx" }
            ],
            max_file_size : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });

    uploader.init();
    uploader.bind('FilesAdded',function(up, files) {
        uploader.start();
    });

    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("boolean",$("#id_is_new_flag"));


    $('.opt-change').set_input_change_event(load_data);
    $('#id_is_new_flag').val(g_args.is_new_flag);


    $('#id_del_all_exam').on('click',function(){
        if (confirm('确定清除所有的例子吗？')) {
            $.do_ajax("/upload_tmk/del_all_example",{
                "postid" : g_args.postid
            });

        }
    });

    $('#id_do_publish').on('click',function(){
        if (confirm('确定推送所有数据吗？')) {
            $.do_ajax("/upload_tmk/do_publish",{
                "postid" : g_args.postid
            });
        }
    });

});
