/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_leo_resource.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        order_by_str  : g_args.order_by_str,
        resource_type :	$('#id_resource_type').val(),
        subject       :	$('#id_subject').val(),
        grade         :	$('#id_grade').val(),
        tag_one       :	$('#id_tag_one').val(),
        tag_two       :	$('#id_tag_two').val(),
        tag_three     :	$('#id_tag_three').val(),
        tag_four      :	$('#id_tag_four').val()
    });
}
$(function(){

    $('#id_resource_type').val(g_args.resource_type);
    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_tag_one').val(g_args.tag_one);
    $('#id_tag_two').val(g_args.tag_two);
    $('#id_tag_three').val(g_args.tag_three);
    $('#id_tag_four').val(g_args.tag_four);

     //获取学科化标签
    var get_sub_grade_tag = function(subject,grade,obj,opt_type){
        obj.empty();
        $.ajax({
            type     : "post",
            url      : "/resource/get_sub_grade_tag_js",
            dataType : "json",
            data : {
                'subject' : subject,
                'grade'   : grade,
            } ,
            success : function(result){
                if(result.ret == 0){
                    obj.empty();
                    obj.next().remove();
                    var tag_info = result.tag;
                    if($(tag_info).length == 0) {
                        if(opt_type == 1){
                            obj.append('<option value="-1">全部</option>');
                        } else {
                            obj.after('<p style="color:red;">请先选择科目、年级!</p>');
                        }
                    } else {
                        var tag_str = '';
                        $.each($(tag_info),function(i, val){
                            tag_str = tag_str + '<option value='+i+'>'+val+'</option>';
                        });
                        obj.append(tag_str);
                    }
                } else {
                    alert(result.info);
                }
            }
        });
    }

    var get_province = function(obj,is_true){
        if (is_true == true){
            var pro = '';
        } else {
            var pro = '<option value="-1">[全部]</option>';
        }
        $.each(ChineseDistricts[86],function(i,val){
            pro = pro + '<option value='+i+'>'+val+'</option>'
        });
        $(obj).empty();
        $(obj).append(pro);

    }

    var get_city = function(obj,city_num, is_true){
         if (is_true == true){
            var pro = '';
        } else {
            var pro = '<option value="-1">[全部]</option>';
        }
        if(city_num > 0){
            $.each(ChineseDistricts[city_num],function(i,val){
                pro = pro + '<option value='+i+'>'+val+'</option>'
            });
        }
        $(obj).empty();
        $(obj).append(pro);

    }
    Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[1,2,3,4,5,6]);
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


    $('#id_use_type').val(g_args.use_type);
    $('#id_resource_type').val(g_args.resource_type);
    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_tag_one').val(g_args.tag_one);

    if($('#id_resource_type').val() == 3){
        get_sub_grade_tag($('#id_subject').val(), $('#id_grade').val(), $('#id_tag_four'), 1);
    } else if($('#id_resource_type').val() == 6) {
        get_province($('#id_tag_two'));
    } else {
        $("#id_tag_four").append('<option value="-1">全部</option>');
    }

    $('#id_tag_two').val(g_args.tag_two);
    $('#id_tag_three').val(g_args.tag_three);
    $('#id_tag_four').val(g_args.tag_four);
    $('#id_file_title').val(g_args.file_title);

    var city_num = $('#id_tag_two').val();
    if($('#id_resource_type').val() == 6 && city_num != -1){
        get_city($('#id_tag_three'), city_num);
    }

    // $("#id_select_all").on("click",function(){
    //     $(".opt-select-item").iCheck("check");
    // });

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

    $('.collect').each(function(){

        var is_get = $(this).hasClass('opt-get');
        $(this).on('mouseover',function(){
            if(is_get){
                $(this).text('点击收藏');
            } else {
                $(this).text('取消收藏');
            }
        });

        $(this).on('mouseleave',function(){
            if(is_get){
                $(this).text('未收藏');
            } else {
                $(this).text('已收藏');
            }
        });

        $(this).on('click', function(){
            do_ajax('/teacher_info/do_collect',{
                'is_get'  : is_get,
                'id'      : $(this).data('id'),
                'file_id' : $(this).data('file_id'),
            },function(ret){

                if(ret.ret==0){
                    BootstrapDialog.alert("操作成功！");
                    setTimeout(function(){
                        window.location.reload();
                    },1000);

                }else{
                    alert(ret.info);
                }
            });
        });

    });

    $('.opt-look').on('click',function(){
        var id = $(this).data('file_id');
        do_ajax('/teacher_info/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            if(ret.ret == 0){
                $('.look-pdf').show();
                $('.look-pdf-son').mousedown(function(e){
                    if(e.which == 3){
                        return false;
                    }
                });
                PDFObject.embed(ret.url, ".look-pdf-son");
                $('.look-pdf-son').css({'width':'120%','height':'120%','margin':'-10%'});
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    });
    $('body').on('click', function(){
        // $('.look-pdf').hide().empty();
        $('.look-pdf').hide().children().children().empty();
    });

    $('.opt-change').set_input_change_event(load_data);
});
