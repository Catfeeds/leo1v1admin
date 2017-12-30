/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/textbook_manage-get_subject_grade_textbook_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			address:	$('#id_address').val()

        });
    }

    Enum_map.append_option_list("grade", $("#id_grade"),false,[100,200,300] );
    Enum_map.append_option_list("subject", $("#id_subject") );

    $('#id_grade').val(g_args.grade);
	  $('#id_subject').val(g_args.subject);
	  $('#id_address').val(g_args.address);

    //实例化一个plupload上传对象
    var uploader = $.plupload_Uploader({
        browse_button : 'id_upload_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/ss_deal/upload_subject_grade_textbook_from_xls', //服务器端的上传页面地址
       // url : '/ss_deal/upload_ass_stu_from_xls', //服务器端的上传页面地址
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

            var screen_height=window.screen.availHeight-300;        
            $(this).admin_select_dlg({
                header_list     : [ "id","教材版本" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                div_style       : {"height":screen_height,"overflow":"auto"},
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

    $("#id_add").on("click",function(){
        var id_province = $("<input />"); 
        var id_city = $("<input />"); 
        var id_educational_system = $("<input />"); 
        var id_grade = $("<select />"); 
        var id_subject = $("<select />"); 
        var id_teacher_textbook = $("<input />"); 
        Enum_map.append_option_list("grade", id_grade,true,[100,200,300] );
        Enum_map.append_option_list("subject", id_subject,true,[1,2,3,4,5,6,7,8,9,10] );

        var arr=[
            ["省", id_province],
            ["市", id_city],
            ["年级", id_grade],
            ["科目", id_subject],
            ["教材", id_teacher_textbook],
            ["学制", id_educational_system],
        ];
        id_teacher_textbook.on("click",function(){
            var textbook  = "";
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
                        id_teacher_textbook.val(select_list);
                        dlg.close();
                    }
                });
                
            });
        });
        
        $.show_key_value_table("添加地区教材", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                $.do_ajax( '/ajax_deal2/add_textbook_one', {
                    "province" :id_province.val(),
                    "city"   :id_city.val(),
                    "subject":id_subject.val(),
                    "grade":id_grade.val(),
                    "teacher_textbook":id_teacher_textbook.val(),
                    "educational_system":id_educational_system.val()
                });
            }
        });

    });




	$('.opt-change').set_input_change_event(load_data);
});









