/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-get_del.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        use_type:	$('#id_use_type').val(),
        resource_type:	$('#id_resource_type').val(),
        subject:	$('#id_subject').val(),
        grade:	$('#id_grade').val(),
        tag_one:	$('#id_tag_one').val(),
        tag_two:	$('#id_tag_two').val(),
        tag_three:	$('#id_tag_three').val(),
        file_title:	$('#id_file_title').val()
    });
}
$(function(){

    Enum_map.append_option_list("use_type", $("#id_use_type"),true);
    Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[1,2,3,4,5,6,7,9]);
    Enum_map.append_option_list("subject", $("#id_subject"));
    Enum_map.append_option_list("grade", $("#id_grade"));
    if(tag_one != ''){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"));
    } else {
        $("#id_tag_one").append('<option value="-1">全部</option>');
    }

    if(tag_two != ''){
        Enum_map.append_option_list(tag_two, $("#id_tag_two"));
    } else {
        $("#id_tag_two").append('<option value="-1">全部</option>');
    }

    if(tag_three != ''){
        Enum_map.append_option_list(tag_three, $("#id_tag_three"));
    } else {
        $("#id_tag_three").append('<option value="-1">全部</option>');
    }

    if(tag_four != ''){
        Enum_map.append_option_list(tag_four, $("#id_tag_four"));
    } else {
        $("#id_tag_four").append('<option value="-1">全部</option>');
    }




    $('#id_use_type').val(g_args.use_type);
    $('#id_resource_type').val(g_args.resource_type);
    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_tag_one').val(g_args.tag_one);
    $('#id_tag_two').val(g_args.tag_two);
    $('#id_tag_three').val(g_args.tag_three);
    $('#id_tag_four').val(g_args.tag_four);
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


    var do_restore_or_del = function(obj,opt_type){

        var res_id_list = [],file_id_list = [];
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                res_id_list.push( $(this).data('id') );
                file_id_list.push( $(this).data('file_id') );
            }
        });

        if(res_id_list.length == 0) {
            BootstrapDialog.alert('请先选择文件！');
        } else {
            var res_id_info  = JSON.stringify(res_id_list);
            var file_id_info = JSON.stringify(file_id_list);

            $.ajax({
                type    : "post",
                url     : "/resource/del_or_restore_resource",
                dataType: "json",
                data    : {
                    "type"        : opt_type,
                    "res_id_str"  : res_id_info,
                    "file_id_str" : file_id_info,
                },
                success : function(result){
                    if(result.ret == 0){
                        window.location.reload();
                    }
                }
            });
        }

    };

    $('.opt-forever-del').on('click', function(){
        do_restore_or_del($(this),6);
    });

    $('.opt-restore').on('click', function(){
        do_restore_or_del($(this),4);
    });

    $('.opt-change').set_input_change_event(load_data);
});
