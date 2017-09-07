/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/textbook_manage-get_subject_grade_textbook_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    //实例化一个plupload上传对象
    var uploader = $.plupload_Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/ss_deal/upload_subject_grade_textbook_from_xls', //服务器端的上传页面地址
        // url : '/ss_deal/upload_lecture_from_xls', //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xls files", extensions : "xls" },
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

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var textbook  = opt_data["teacher_textbook"];
        console.log(textbook);
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
                    //alert(JSON.stringify(select_list));return;
                    $.do_ajax("/ajax_deal2/set_teacher_textbook",{
                        "id": opt_data.id,
                        "textbook_list":JSON.stringify(select_list),
                        "old_textbook": opt_data.teacher_textbook,
                    });
                }
            });
        }) ;
        
    });





	$('.opt-change').set_input_change_event(load_data);
});









