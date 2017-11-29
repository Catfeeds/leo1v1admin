/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-get_all.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        user_type     :	$('#id_user_type').val(),
        resource_type :	$('#id_resource_type').val(),
        subject       :	$('#id_subject').val(),
        grade         :	$('#id_grade').val(),
        file_title    :	$('#id_file_title').val()
    });
}
$(function(){

    Enum_map.append_option_list("user_type", $("#id_user_type"),true);
    Enum_map.append_option_list("resource_type", $("#id_resource_type"),true);
    Enum_map.append_option_list("subject", $("#id_subject"));
    Enum_map.append_option_list("grade", $("#id_grade"));

    $('#id_user_type').val(g_args.user_type);
    $('#id_resource_type').val(g_args.resource_type);
    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_file_title').val(g_args.file_title);

    $("#id_select_all").on("click",function(){
        $(".opt-select-item").iCheck("check");
    });

    $("#id_select_other").on("click",function(){
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ($item.iCheckValue() ) {
                $item.iCheck("uncheck");
            }else{
                $item.iCheck("check");
            }
        } );
    });

    $('.opt-add').on('click', function(){
        add_info();
    });

    var add_info = function(){
        var id_user_type     = $("<select class=\"user\"/>");
        var id_resource_type = $("<select class=\"resource\"/>");
        var id_subject       = $("<select/>");
        var id_grade         = $("<select class=\"grade sel_flag\"/>");
        var id_tag_one       = $("<select class=\"tag_one sel_flag\"/>");
        var id_tag_two       = $("<select class=\"tag_two sel_flag\"/>");
        var id_tag_three     = $("<select class=\"tag_three sel_flag\"/>");
        var id_file          = $("<button class=\"btn\" id=\"id_file\">选择文件</button>");

        Enum_map.append_option_list("user_type",id_user_type,true);
        Enum_map.append_option_list("resource_type",id_resource_type,true);
        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("grade",id_grade,true);
        Enum_map.append_option_list("region_version",id_tag_one,true);
        Enum_map.append_option_list("resource_type2",id_tag_two,true);
        Enum_map.append_option_list("resource_season",id_tag_three,true);

        id_user_type.val(g_args.user_type);
        id_resource_type.val(g_args.resource_type);
        id_subject.val(g_args.subject);
        id_grade.val(g_args.grade);

        var arr= [
            ["使用角色：", id_user_type],
            ["资源类型：", id_resource_type],
            ["科目：", id_subject],
            ["年级：", id_grade],
            ["教材版本：", id_tag_one],
            ["资料类型：", id_tag_two],
            ["春署秋寒：", id_tag_three],
            ["", id_file],
        ];


        $.show_key_value_table('新建', arr,false,function(){
            $('.resource').change(function(){
                $('.sel_flag').empty();
                $('.sel_flag').val(0);
                $('.sel_flag').parent().parent().show();
                change_tag($(this).val());
            });

            multi_upload_file('id_file',1,function(up,file) {
                $('.close').click();
                $('.opt_process').show();
            },function(up, file, info) {
                console.log(info.response);
                console.log(info.response.hash);
                console.log(info.response['hash']);
                // if( info.status == 200){
                //     $.ajax({
                //         type     : "post",
                //         url      : "/resource/add_resource",
                //         dataType : "json",
                //         data : {
                //             'user_type'     : id_user_type.val(),
                //             'resource_type' : id_resource_type.val(),
                //             'subject'       : id_subject.val(),
                //             'grade'         : id_grade.val(),
                //             'tag_one'       : id_tag_one.val(),
                //             'tag_two'       : id_tag_two.val(),
                //             'tag_three'     : id_tag_three.val(),
                //             'file_title'    : file.name,
                //             'file_type'     : file.type,
                //             'file_size'     : file.size,
                //             'file_hash'     : info.response.hash,
                //         } ,
                //         success   : function(result){
                //             if(result.ret == 0){
                //                 // window.location.reload();
                //             } else {
                //                 alert(result.info);
                //             }
                //         }
                //     });

                // }

            }, ["jpg","png"],'fsUploadProgress');

        },false,600);
    };

    var change_tag = function(val){
        if(val < 3){//1v1
            Enum_map.append_option_list("grade",$('.grade'),true);
            Enum_map.append_option_list("region_version",$('.tag_one'),true);
            Enum_map.append_option_list("resource_type2",$('.tag_two'),true);
            Enum_map.append_option_list("resource_season",$('.tag_three'),true);
            $('.tag_one').parent().prev().text('教材版本：');
            $('.tag_two').parent().prev().text('资料类型：');
            $('.tag_three').parent().prev().text('春署秋寒：');
        } else if(val == 3){
            Enum_map.append_option_list("grade",$('.grade'),true);
            Enum_map.append_option_list("resource_free",$('.tag_one'),true);
            $('.tag_one').parent().prev().text('试听类型：');
            $('.tag_two').parent().parent().hide();
            $('.tag_three').parent().parent().hide();
        } else if (val == 4 || val == 5) {
            Enum_map.append_option_list("grade",$('.grade'),true);
            Enum_map.append_option_list("region_version",$('.tag_one'),true);
            $('.tag_one').parent().prev().text('教材版本：');
            $('.tag_two').parent().parent().hide();
            $('.tag_three').parent().parent().hide();
        } else if (val == 6 ){
            Enum_map.append_option_list("grade",$('.grade'),true);
            Enum_map.append_option_list("resource_year",$('.tag_one'),true);
            Enum_map.append_option_list("resource_type2",$('.tag_two'),true);
            Enum_map.append_option_list("resource_season",$('.tag_three'),true);
            $('.tag_one').parent().prev().text('年份：');
            $('.tag_two').parent().prev().text('省份：');
            $('.tag_three').parent().prev().text('城市：');
        } else if (val == 7) {
            $('.grade').parent().parent().hide();
            Enum_map.append_option_list("resource_year",$('.tag_one'),true);
            Enum_map.append_option_list("resource_type2",$('.tag_two'),true);
            Enum_map.append_option_list("resource_season",$('.tag_three'),true);
            $('.tag_one').parent().prev().text('一级知识点：');
            $('.tag_two').parent().prev().text('二级知识点：');
            $('.tag_three').parent().prev().text('三级知识点：');
        } else if (val == 8) {
            $('.grade').parent().parent().hide();
            Enum_map.append_option_list("season",$('.tag_one'),true);
            Enum_map.append_option_list("resource_year",$('.tag_two'),true);
            Enum_map.append_option_list("resource_type2",$('.tag_three'),true);
            $('.tag_one').parent().prev().text('四情类型：');
            $('.tag_two').parent().prev().text('省份：');
            $('.tag_three').parent().prev().text('城市：');
        } else if (val == 9){
            Enum_map.append_option_list("grade",$('.grade'),true);
            Enum_map.append_option_list("region_version",$('.tag_one'),true);
            Enum_map.append_option_list("resource_train",$('.tag_two'),true);
            $('.tag_one').parent().prev().text('教材版本：');
            $('.tag_two').parent().prev().text('培训资料：');
            $('.tag_three').parent().parent().hide();
        }
    };

    $('.opt-del').on('click', function(){
        $('.opt-select-item').each(function(){

            console.log($(this).attr('checked'));

        });
    });


    $('.opt-change').set_input_change_event(load_data);
});

