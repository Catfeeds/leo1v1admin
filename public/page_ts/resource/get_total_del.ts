/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-get_total_del.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        use_type:	$('#id_use_type').val(),
        resource_type:	$('#id_resource_type').val(),
        subject:	$('#id_subject').val(),
        grade:	$('#id_grade').val(),
        file_title:	$('#id_file_title').val()
    });
}
$(function(){

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
    Enum_map.append_option_list("use_type", $("#id_use_type"));
    Enum_map.append_option_list("resource_type", $("#id_resource_type"),false,[1,2,3,4,5,6,7,9]);
    Enum_map.append_option_list("subject", $("#id_subject"));
    Enum_map.append_option_list("grade", $("#id_grade"));

    $('#id_use_type').val(g_args.use_type);
    $('#id_resource_type').val(g_args.resource_type);
    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_tag_one').val(g_args.tag_one);

    $('#id_file_title').val(g_args.file_title);

    var city_num = $('#id_tag_two').val();

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

        var res_id_list = [],file_id_list = [],file_link_list = [];
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                res_id_list.push( $(this).data('id') );
                file_id_list.push( $(this).data('file_id') );
                file_link_list.push($(this).data('file_link') );
            }
        });

        if(res_id_list.length == 0) {
            BootstrapDialog.alert('请先选择文件！');
        } else {
            var res_id_info  = JSON.stringify(res_id_list);
            var file_id_info = JSON.stringify(file_id_list);
            var file_link_info = JSON.stringify(file_link_list);
            if(opt_type == 4){
                var tip = '确定要还原吗？';
            } else {
                var tip = '永久删除后将不可恢复！确定要永久删除吗？';
            }
            if( confirm(tip) ){
                $.ajax({
                    type    : "post",
                    url     : "/resource/batch_del_resource",
                    dataType: "json",
                    data    : {
                        "type"        : opt_type,
                        "res_id_str"  : res_id_info,
                        "file_id_str" : file_id_info,
                        "file_link_str"   : file_link_list,
                    },
                    success : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        }
                    }
                });
            }
        }

    };

    $('.opt-forever-del').on('click', function(){
        do_restore_or_del($(this),6);
    });

    $('.opt-forever-del-file').on('click', function(){
        var res_id_list = [],file_id_list = [],file_link_list = [];
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                res_id_list.push( $(this).data('id') );
                file_id_list.push( $(this).data('file_id') );
                file_link_list.push($(this).data('file_link') );
            }
        });

        if(res_id_list.length == 0) {
            BootstrapDialog.alert('请先选择文件！');
        } else {
            var res_id_info  = JSON.stringify(res_id_list);
            var file_id_info = JSON.stringify(file_id_list);
            var file_link_info = JSON.stringify(file_link_list);
            var tip = '永久删除后将不可恢复！确定要永久删除吗？';
            
            if( confirm(tip) ){
                $.ajax({
                    type    : "post",
                    url     : "/resource/batch_del_file",
                    dataType: "json",
                    data    : {
                        "res_id_str"  : res_id_info,
                        "file_id_str" : file_id_info,
                        "file_link_str"   : file_link_info,
                    },
                    success : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        }
                    }
                });
            }
        }

    });

    $('.opt-change').set_input_change_event(load_data);

    //预览讲义
    $('.opt-look').click(function(){
        var id = $(this).data('file_id');
        var file_link = $(this).data('file_link');
        var have_del = 0;
        var pdf_url = "";

        do_ajax('/resource/total_del_file',{'file_name':file_link,'tea_res_id':id},function(ret){
            //console.log(ret);
            if(ret.ret == 0 ){
                pdf_url = ret.url;
                if( ret.status == 500 ){
                    have_del = 1;
                }
            }

            if(pdf_url == ''){
                BootstrapDialog.alert('已经删除该文件,无法访问！');
                return false;
            }
            if( have_del == 1 ){
                //console.log(1111);
                BootstrapDialog.alert('在cdn已经删除该文件,由于缓存问题还存在，你确定查看！');
                //window.open(pdf_url);
                console.log(pdf_url);
                //lookPdf(pdf_url);

            }else{
                lookPdf(pdf_url);
                console.log(pdf_url);                
            }
        });
    })

    $('body').on('click', function(){
        $('.look-pdf').hide().children().children().empty();
    });

});

function lookPdf(pdf_url){
    $('.look-pdf').show();
    PDFObject.embed(pdf_url, ".look-pdf-son");
    $('.look-pdf-son').css({'width':'120%','height':'120%','margin':'-10%'});
}
