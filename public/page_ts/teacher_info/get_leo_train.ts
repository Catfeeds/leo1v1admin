/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_leo_train.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
	if( $('#id_resource_type').val() == 6 && book != []) {
        //$('#id_tag_one').val(-1);
    }else{
    	//$('#id_tag_one').val(-1);
    }

    if(global_mark==0){
        sub_info = {
            order_by_str  : g_args.order_by_str,
            resource_type :	$('#id_resource_type').val(),
            subject       :	$('#id_subject').val(),
            grade         :	$('#id_grade').val(),
            tag_one       :	$('#id_tag_one').val(),
            tag_two       :	$('#id_tag_two').val(),
            tag_three     :	$('#id_tag_three').val(),
            tag_four      :	$('#id_tag_four').val(),
            tag_five      : $('#id_tag_five').val(),
        };
    } else {
        sub_info = {
            order_by_str  : g_args.order_by_str,
            resource_type :	$('#id_resource_type').val(),
            subject       :	$('#id_subject').val(),
            tag_one       :	$('#id_tag_one').val(),
            tag_two       :	$('#id_tag_two').val(),
            tag_three     :	$('#id_tag_three').val(),
            tag_four      :	$('#id_tag_four').val(),
            tag_five      : $('#id_tag_five').val(),
        };
    }
    $.reload_self_page (sub_info);
}
$(function(){


	$('#id_resource_type').val(g_args.resource_type);
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_tag_one').val(g_args.tag_one);
	$('#id_tag_two').val(g_args.tag_two);
	$('#id_tag_three').val(g_args.tag_three);
	$('#id_tag_four').val(g_args.tag_four);
	$('#id_tag_five').val(g_args.tag_five);

	var get_sub_grade_tag = function(subject,grade,obj,opt_type,sel_val){
        obj.empty();
        $.ajax({
            type     : "post",
            //url      : "/resource/get_sub_grade_tag_js",
            url      : "/resource/get_sub_grade_book_tag",
            dataType : "json",
            data : {
                'resource_type' : $('#id_resource_type').val(),
                'subject' : subject,
                'grade'   : grade,
                'bookid'  : $("#id_tag_one").val(),
                'season_id': $("#id_tag_two").val(),
            } ,
            success : function(result){
                if(result.ret == 0){
                    obj.empty();
                    obj.next().remove();
                    var tag_info = result.tag;
                    //console.log(tag_info);
                    if($(tag_info).length == 0) {
                        if(opt_type == 1){
                            obj.append('<option value="-1">全部</option>');
                        } else {
                            obj.after('<p style="color:red;">请先选择科目、年级!</p>');
                        }
                    } else {
                        var tag_str = '<option value="-1">全部</option>';

                        $.each($(tag_info),function(i, val){
                            tag_str = tag_str + '<option value='+val.id
                            +'>'+val.tag+'</option>';
                        });
                        obj.append(tag_str);
                        obj.val(sel_val);
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

    Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,type_list);
    // Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[1,2,3,4,5,6]);
    Enum_map.append_option_list("subject", $("#id_subject"),true, tea_sub);
    Enum_map.append_option_list("grade", $("#id_grade"),true, tea_gra);

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
    if(tag_four != ''){
        Enum_map.append_option_list(tag_four, $("#id_tag_four"));
    } else {
        $("#id_tag_four").append('<option value="-1">全部</option>');
    }
    if(tag_five != ''){
        Enum_map.append_option_list(tag_five, $("#id_tag_five"));
    } else {
        $("#id_tag_five").append('<option value="-1">全部</option>');
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
    $('#id_tag_four').val(g_args.tag_four);
    $('#id_tag_five').val(g_args.tag_five);

    if($('#id_resource_type').val() == 1 ){
         get_sub_grade_tag($('#id_subject').val(), $('#id_grade').val(), $('#id_tag_four'), 1, g_args.tag_four);
    }else if($('#id_resource_type').val() == 3 ){
        get_sub_grade_tag($('#id_subject').val(), $('#id_grade').val(), $('#id_tag_four'), 1, g_args.tag_four);
    } else if($('#id_resource_type').val() == 6) {
        get_province($('#id_tag_three'));
    } else {
        $("#id_tag_four").append('<option value="-1">全部</option>');
    }

    $('#id_tag_two').val(g_args.tag_two);
    $('#id_tag_three').val(g_args.tag_three);
    $('#id_file_title').val(g_args.file_title);
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
        var file_type = $(this).data('file_type');
        file_type = file_type.toLowerCase();

        var newTab;
        if( file_type == "mp4" || file_type == "mp3" ){
            newTab = window.open('about:blank');
        }
        
        do_ajax('/teacher_info/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            if(ret.ret == 0){ 
                if( ret.url.toLowerCase().indexOf(".mp4") > 0 || ret.url.toLowerCase().indexOf(".mp3") > 0){
                    newTab.location.href = ret.url;
                }else{
                    //newTab.close();
                    $('.look-pdf').show();
                    $('.look-pdf-son').mousedown(function(e){
                        if(e.which == 3){
                            return false;
                        }
                    });

                    PDFObject.embed(ret.url, ".look-pdf-son");
                    $('.look-pdf-son').css({'width':'120%','height':'120%','margin':'-10%'});
                }
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    });

    $('body').on('click', function(){
        // $('.look-pdf').hide().empty();
        $('.look-pdf').hide().children().children().empty();
    });

    var color_id = 0,color_res = 0,color_flag = 0;
    $('.common-table tr').each(function(i){
        if(i>0){
            if($(this).data('resource_id') == color_res){
                $(this).css('background',color_id );
            } else {
                color_res = $(this).data('resource_id');
                (color_flag == 0) ? color_flag = 1: color_flag = 0;
                (color_flag == 0) ? color_id = '#e1f5fa' : color_id = '#eee';
                $(this).css('background',color_id);
            }
        }
    });


    global_mark=0;
    $('#id_subject').on("change",function(){
        global_mark=1;
    });

	$('.opt-change').set_input_change_event(load_data);
});

