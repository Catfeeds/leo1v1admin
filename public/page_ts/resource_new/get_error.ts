/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource_new-get_error.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
	var res_type = 0;
    if($('#id_use_type').val() == 1){
        if( $('#id_resource_type').val() >7) {
            res_type = 1;
        } else {
            res_type = $('#id_resource_type').val();
        }
    } else if ($('#id_use_type').val() == 2){
        res_type = 9;
    } else {
        res_type = 8;
    }
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		use_type:	$('#id_use_type').val(),
		resource_type:	$('#id_resource_type').val(),
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		tag_one:	$('#id_tag_one').val(),
		tag_two:	$('#id_tag_two').val(),
		tag_three:	$('#id_tag_three').val(),
		tag_four:	$('#id_tag_four').val(),
		tag_five:	$('#id_tag_five').val(),
		file_title:	$('#id_file_title').val(),
		date_type_config:   $('#id_date_type_config').val(),
        date_type:  $('#id_date_type').val(),
        opt_date_type:  $('#id_opt_date_type').val(),
        start_time: $('#id_start_time').val(),
        end_time:   $('#id_end_time').val(),
        error_type: $('#id_error_type').val(),
		sub_error_type: $('#id_sub_error_type').val(),
		file_id:    $('#id_file_id').val(),
		});
}
$(function(){
	 $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    //获取学科化标签
    var get_sub_grade_tag = function(subject,grade,booid,resource_type,season_id,obj,opt_type){
        obj.empty();
        //console.log(season_id);
        $.ajax({
            type     : "post",
            url      : "/resource/get_sub_grade_book_tag",
            dataType : "json",
            data : {
                'subject' : subject,
                'grade'   : grade,
                'bookid'  : booid,
                'resource_type' : resource_type,
                'season_id'  : season_id
            } ,
            success : function(result){
                if(result.ret == 0){
                    obj.empty();
                    obj.parent().find('span.tag_warn').remove();
                    //console.log(result);
                    var tag_info = result.tag;
             
                    if($(tag_info).length == 0) {
                        if(opt_type == 1){
                            if( subject > 0 && grade > 0){
                                obj.append('<option value="-1">暂无标签</option>');
                            }else{
                                obj.append('<option value="-1">资源类型、科目和年级是必选</option>');
                                $('#id_tag_four').css({'color':"#a2a2a2"});
                            }
                        } else {
                            obj.after('<span class="tag_warn" style="color:red;margin-left:8px">暂时未添加标签!</span>');
                        }
                    } else {
                        if(opt_type == 1){
                           var tag_str = '<option value="-1">全部</option>';                          
                        }else{
                            var tag_str = '';
                        }

                        $.each($(tag_info),function(i,item){                        
                            tag_str = tag_str + '<option value='+item.id+'>'+item.tag+'</option>';
                        });
                        obj.append(tag_str);
                        if(opt_type == 1){
                            obj.val(g_args.tag_four);
                        }
                    }
                } else {
                    alert(result.info);
                }
            }
        });
    }

    var get_province = function(obj,is_true){
        if (is_true == true){
            var pro = '<option value="0">[全部]</option>';
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
            var pro = '<option value="0">[全部]</option>';
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
    Enum_map.append_option_list("resource_error",$('#id_error_type'));
    Enum_map.append_option_list("use_type", $("#id_use_type"),true,[1,2]);
    $('#id_use_type').val(g_args.use_type);



    if(g_args.use_type == 1){
        Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[1,2,3,4,5,6,7]);
        $('#id_resource_type').val(g_args.resource_type);
    } else if (g_args.use_type == 2){
        Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[9]);
        $('#id_resource_type').val(9);
    } else {
        Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[8]);
        $('#id_resource_type').val(8);
    }

    if(tag_one == 'region_version'){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"), false, book);
    } else if(tag_one != ''){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"));
    }else{
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
        if( parseInt(g_args.resource_type) == '' || parseInt(g_args.resource_type) == 1 || parseInt(g_args.resource_type) == 3 ){
            //console.log(g_args.resource_type);
            $("#id_tag_four").append('<option value="-1">先选择科目和年级</option>');
        }else{
            $("#id_tag_four").append('<option value="-1">全部</option>');
        }
    }

    if(tag_five != ''){
        Enum_map.append_option_list(tag_five, $("#id_tag_five"));
    } else {
        $("#id_tag_five").append('<option value="-1">全部</option>');
    }

    if(is_teacher == 1){
        Enum_map.append_option_list("subject", $("#id_subject"),true, my_subject);
        Enum_map.append_option_list("grade", $("#id_grade"),true, my_grade);
        if( g_args.subject == -1 || g_args.subject == ''){
            $("#id_subject").val(my_subject[0]);
        }else{
            $('#id_subject').val(g_args.subject);
        }
        if( g_args.grade == -1 || g_args.grade == ''){
            $("#id_grade").val(my_grade[0]);
        }else{
            $("#id_grade").val(g_args.grade);
        }
    }else{
        Enum_map.append_option_list("subject", $("#id_subject"),false, my_subject);
        Enum_map.append_option_list("grade", $("#id_grade"),false, my_grade);
        $('#id_subject').val(g_args.subject);
        $('#id_grade').val(g_args.grade);
    }

    $('#id_tag_one').val(g_args.tag_one);

    if($('#id_resource_type').val() == 3 || $('#id_resource_type').val() == 1 ){
        var season_default = -1;
        if( g_args.tag_two > 0 ){
            season_default = g_args.tag_two
        }
        get_sub_grade_tag($('#id_subject').val(), $('#id_grade').val(),$('#id_tag_one').val(),$('#id_resource_type').val(),season_default,$('#id_tag_four'), 1);
    } else if($('#id_resource_type').val() == 6) {
        get_province($('#id_tag_three'));
        if($('.right-menu').length>0){
            $('.right-menu').each(function(){
              
                var province_id = parseInt($(this).find('.province').text());
                if( parseInt(province_id) != 0 ){
                    var province = ChineseDistricts['86'][province_id];
                    $(this).find('.province').text(province);
                }else{
                    $(this).find('.province').text('全部');
                }

                var city_id = parseInt($(this).find('.city').text());
                if( parseInt(city_id) != 0 ){
                    var city = ChineseDistricts[province_id][city_id];
                    $(this).find('.city').text(city);
                }else{
                    $(this).find('.city').text('全部');
                }

            })
        }
    } else {
        //$("#id_tag_four").append('<option value="-1">全部</option>');
    }

    $('#id_tag_three').val(g_args.tag_three);

    var city_num = $('#id_tag_three').val();
    if($('#id_resource_type').val() == 6 && city_num != -1){
        get_city($('#id_tag_four'), city_num);
    }

    $('#id_tag_two').val(g_args.tag_two);
    $('#id_tag_four').val(g_args.tag_four);
    $('#id_tag_five').val(g_args.tag_five);
    $('#id_file_title').val(g_args.file_title);
    $('#id_error_type').val(g_args.error_type);
    $('#id_file_id').val(g_args.file_id);
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
        var timestamp = Date.parse(new Date());

        add_resource(timestamp);
    });

    //multi_resource_file 是个二维数组 [['课件版id','老师版id','学生版id'],['课件版id','老师版id','学生版id']]
    //whole_resource_file 是个一维数组 [ '课件版id','老师版id','学生版id','课件版id','老师版id']
    //resource_id_arr 插入多组课件时返回的资源id数组
    //whole_upload_num 总共上传文件的数量
    //have_upload_num  已经上传文件的数量
    var last_id  = 0, stu_hash = '', stu_link = '',multi_resource_file = [],whole_resource_file=[],resource_id_arr = [],whole_upload_num = 0,have_upload_num = 0;

    var remove_id = [];

    var add_resource = function(new_flag){

        var id_use_type      = $("<select class=\"use\"/>");
        var id_resource_type = $("<select class=\"resource\"/>");
        var id_subject       = $("<select class=\"subject\"/>");
        var id_grade         = $("<select class=\"grade sel_flag\"/>");

        var id_tag_one   = $("<select class=\"tag_one sel_flag\"/>");
        var id_tag_two   = $("<select class=\"tag_two sel_flag\"/>");
        var id_tag_three = $("<select class=\"tag_three sel_flag\"/>");
        var id_tag_four  = $("<select class=\"tag_four sel_flag\"/>");
        var id_tag_five  = $("<select class=\"tag_five sel_flag\"/>");

        var id_other_file = $("<button class=\"btn\" id=\"id_other_file\">选择文件</button>");//其他
        var id_les_file   = $("<button class=\"btn\" id=\"id_les_file\">选择文件</button>");//课件
        var id_tea_file   = $("<button class=\"btn\" id=\"id_tea_file\">选择文件</button>");//老师
        var id_stu_file   = $("<button class=\"btn\" id=\"id_stu_file\">选择文件</button>");//学生
        var id_ex_file    = $("<button class=\"btn\" id=\"id_ex_file\">选择文件</button>");//额外的讲义
        var id_video_file = $("<button class=\"btn\" id=\"id_video_file\">选择文件</button>");//培训视频

        //仅仅对resource_type=4,5时候使用
        var id_ff_file = $("<button class=\"btn\" id=\"id_ff_file\">选择文件</button>");


        var use_res = [1,[1,2,3,4,5,6,7],[9],[8]];
        Enum_map.append_option_list("use_type",id_use_type,true,[1,2]);
        Enum_map.append_option_list("resource_type",id_resource_type,true,use_res[g_args.use_type]);
        Enum_map.append_option_list("subject",id_subject,true,my_subject);
        Enum_map.append_option_list("grade",id_grade,true,my_grade);

        if(tag_one != 'region_version' && tag_one != ''){
            Enum_map.append_option_list(tag_one, id_tag_one,true );
        }

        if(tag_two != ''){
            Enum_map.append_option_list(tag_two,id_tag_two,true);
            if( g_args.tag_two > 0){
                id_tag_two.val(g_args.tag_two);
            }
        }
        if(tag_three != ''){
            Enum_map.append_option_list(tag_three,id_tag_three,true);
            if( g_args.tag_three > 0 ){
                id_tag_three.val(g_args.tag_three);
            }
        }
        if(tag_four != ''){
            Enum_map.append_option_list(tag_four,id_tag_four,true);
            if( g_args.tag_four > 0 ){
                id_tag_four.val(g_args.tag_four);
            }
        }

        if(tag_five != ''){
            Enum_map.append_option_list(tag_five,id_tag_five,true);
            if( g_args.tag_five > 0){
                id_tag_five.val(g_args.tag_five);
            }
        }

        id_use_type.val(g_args.use_type);
        id_resource_type.val(g_args.resource_type);
        if(g_args.subject > 0 ){
            id_subject.val(g_args.subject);
        }
        if( g_args.grade > 0 ){
            id_grade.val(g_args.grade);
        }

        //多文件上传
       
        var id_tag_four_search = $("<button class=\"btn btn-primary\" id=\"id_search_tag\" style=\"margin-left:10px\">搜索</button>");

        resource_id_arr = [];

        var arr= [
            ["分类", id_use_type],
            ["资源类型", id_resource_type],
            ["科目", id_subject],
            ["年级", id_grade],
            [tag_one_name, id_tag_one],
            [tag_two_name, id_tag_two],
            [tag_three_name, id_tag_three],
            [tag_four_name, [ id_tag_four,id_tag_four_search]],
            [tag_five_name, id_tag_five],
            [$("<span>上传文件(pdf,最大15M)</span><span style='color:red'> 必传*</span>"), id_other_file],
            [$("<span>上传文件(pdf,最大15M)</span><span style='color:red'> 必传*</span>"), id_ff_file],
            [$("<span>课件版(pdf,最大15M)</span><span style='color:red'> 必传*</span>"), id_les_file],
            [$("<span>老师版(pdf,最大15M)</span><span style='color:red'> 必传*</span>"), id_tea_file],
            [$("<span>学生版(pdf,最大15M)</span><span style='color:red'> 必传*</span>"), id_stu_file],
            ["额外的讲义(pdf,mp3,mp4,最大15M,可选)", id_ex_file],
            [$("<span>培训讲义或视频(pdf,mp3,mp4,最大100M,必传)</span><span style='color:red'> 必传*</span>"), id_video_file],
        ];

        $.show_key_value_table('新建', arr,{
            label    : '确认',
            cssClass : 'btn-info btn-mark',
            action   : function() {
                if(id_subject.val() == null || id_grade.val() == null ){
                    alert('请完善信息!');
                } else {

                    if( $('.resource').val() == 1 || $('.resource').val() == 3 ){
                        if(id_tag_four.val() == null){
                            alert('学科化标签是必填项!');
                            return false;
                        }
                    }

                    var obj_arr = [];  //必传
                    var other_arr = [];//非必传
                    //移除其他文件,计算文件数量
                    if(id_resource_type.val() <= 3){//1v1
                        $('.other_file,.ff_file').each(function(){
                            remove_id.push($(this).data('id'));
                            $(this).remove();
                        });

                        obj_arr = ['les_file','tea_file','stu_file','ex_file'];
                        
                        if(!check_file_num(obj_arr)) return;

                        var file_num = $('.tea_file').length;

                    } else if(id_resource_type.val() < 6 ){
                        $('.les_file,.tea_file,.stu_file,.other_file').each(function(){
                            remove_id.push($(this).data('id'));
                            $(this).remove();
                        });

                        obj_arr = ['ff_file','','','ex_file'];

                        if(!check_file_num(obj_arr)) return;

                        var file_num = $('.ff_file').length;

                    } else if(id_resource_type.val() == 6 ){
                        $('.les_file,.other_file').each(function(){
                            remove_id.push($(this).data('id'));
                            $(this).remove();
                        });

                        obj_arr = ['','tea_file','stu_file','ex_file'];
                        if(!check_file_num(obj_arr)) return;

                        var file_num = $('.tea_file').length;

                    } else {
                        $('.les_file,.tea_file,.stu_file,.ex_file,.ff_file').each(function(){
                            remove_id.push($(this).data('id'));
                            $(this).remove();
                        });

                        obj_arr = ['video_file'];
                        if(!check_file_num(obj_arr)) return;

                        var file_num = $('.video_file').length;
                    }
          
                    $.ajax({
                        type     : "post",
                        url      : "/resource/add_resource",
                        dataType : "json",
                        data : {
                            'use_type'      : id_use_type.val(),
                            'resource_type' : id_resource_type.val(),
                            'subject'       : id_subject.val(),
                            'grade'         : id_grade.val(),
                            'tag_one'       : id_tag_one.val(),
                            'tag_two'       : id_tag_two.val(),
                            'tag_three'     : id_tag_three.val(),
                            'tag_four'      : id_tag_four.val(),
                            'tag_five'      : id_tag_five.val(),
                            'add_num'       : file_num,
                        } ,
                        success : function(result){
                            whole_resource_file = [];  //总共上传文件
                            have_upload_num = 0;       //已经上传文件的数量
                            if(result.ret == 0){
                                // window.location.reload();
                                resource_id_arr = JSON.parse(result.resource_id_arr);
                                resource_id_arr.reverse();
                                console.log(resource_id_arr);
                               
                                for(var x in multi_resource_file){
                                    for(var y in multi_resource_file[x]){
                                        var file_id_whole = multi_resource_file[x][y];
                                        var file_id = file_id_whole.substr(3);
                                        var file_use_type = file_id_whole.substr(0,1);
                                        var file_obj =  {
                                            'file_use_type' : file_use_type,
                                            'resource_id' : resource_id_arr[x],
                                            'file_id' : file_id,
                                        };
                                                                        
                                        whole_resource_file.push(file_obj);
                                    }
                                }
                                console.log(whole_resource_file);
                                $('#up_load').attr('flag', new_flag);//开始上传
                                $('#up_load').click();//开始上传

                            } else {
                                alert(result.info);
                            }
                        },
                    });

                }
            }
        },function(){

            $('.sel_flag').each(function(){
                if($(this).parent().prev().text() == ''){
                    $(this).parent().parent().hide();
                }
            });

            $('.use').change(function(){
                $('.resource').empty();
                Enum_map.append_option_list("resource_type",id_resource_type,true,use_res[$(this).val()]);
                if($(this).val() == 1){
                    if( $('.resource').val() >7) {
                        $('.resource').val(1);
                    }
                } else if ($(this).val() == 2){
                    $('.resource').val(9);
                } else {
                    $('.resource').val(8);
                    alert('该类型暂不可用，请刷新页面！');
                }

                $('.sel_flag').empty();
                $('.sel_flag').val(0);
                $('.sel_flag').parent().parent().show();
                var type_id = $('.resource').val();
                change_tag(type_id);
            });

            $('.resource').change(function(){
                $('.sel_flag').empty();
                $('.sel_flag').val(0);
                $('.sel_flag').parent().parent().show();
                var type_id = $(this).val();
                change_tag(type_id);

            });

            //根据类型科目年级筛选教材
            $('.resource,.subject,.grade').change(function(){
                if( $('.resource').val() <6 || $('.resource').val() ==9){
                    get_book(0,$('.tag_one'));
                }
           
            });

            if( $('.resource').val() == 2 ){
                $('#id_other_file,#id_ff_file,#id_video_file').parent().parent().hide();
                get_book(g_args.tag_one,$('.tag_one'));
            } else if ($('.resource').val() ==3 || $('.resource').val() == 1){

                $('.subject').empty();
                Enum_map.append_option_list("subject",$('.subject'),true,[1,2,3,4,5]);
                if( g_args.subject < 6 && g_args.subject > 0){
                    id_subject.val(g_args.subject);
                }
                $('#id_other_file,#id_ff_file,#id_video_file').parent().parent().hide();
                get_book(g_args.tag_one,$('.tag_one'));
            }else if($('.resource').val() < 6){ //4,5
                $('#id_les_file,#id_other_file,#id_tea_file,#id_stu_file,#id_video_file').parent().parent().hide();
                get_book(g_args.tag_one,$('.tag_one'));
            }else if($('.resource').val() == 6){
                $('#id_les_file,#id_other_file,#id_ff_file,#id_video_file').parent().parent().hide();
                get_province($('.tag_three'), true);
                $('.tag_three').change(function(){
                    get_city($('.tag_four'), $(this).val(), true);
                });
                get_city($('.tag_four'), 110000, true);
                get_book(g_args.tag_one,$('.tag_one'));
                $('#id_search_tag').hide();
            } else {
                $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file,#id_ff_file,#id_other_file').parent().parent().hide();
                if($('.resource').val()==9){
                    get_book(g_args.tag_one,$('.tag_one'));
                }
            }

            //其他版本
            get_qiniu(new_flag,true,false,'id_other_file',0, 'other_file', 'pdf,PDF','150m');
            //课件版
            get_qiniu(new_flag,true,false,'id_les_file',0, 'les_file', 'pdf,PDF','150m');
            //老师版
            get_qiniu(new_flag,true,false,'id_tea_file',1, 'tea_file', 'pdf,PDF','150m');
            //学生版
            get_qiniu(new_flag,true,false,'id_stu_file',2, 'stu_file', 'pdf,PDF','150m');
            //额外讲义
            get_qiniu(new_flag,true,false,'id_ex_file',3, 'ex_file', 'pdf,PDF,mp3,mp4,MP3,MP4','150m');
            //仅对resource_type = 4,5
            get_qiniu(new_flag,true,false,'id_ff_file',0, 'ff_file', 'pdf,PDF','150m');
            //培训视频或者讲义
            get_qiniu(new_flag,true,false,'id_video_file',0, 'video_file', 'pdf,PDF,mp3,mp4,MP3,MP4','100m');

            $('#id_search_tag').click(function(){
                var grade = $('.grade').val();
                var subject = $('.subject').val();
                var bookid = $('.tag_one').val();
                var resource = $('.resource').val();
                var season_id = $('.tag_two').val();
                var obj = $('.tag_four');
                if( grade == null){
                    obj.after('<span class="tag_warn" style="color:red;margin-left:8px">请先选择年级!</span>');
                    return false;
                }
                if( subject == null){
                    obj.after('<span class="tag_warn" style="color:red;margin-left:8px">请先选择科目!</span>');
                    return false;
                }
                if( bookid == null){
                    obj.after('<span class="tag_warn" style="color:red;margin-left:8px">请先选择教材!</span>');
                    return false;
                }
                if( resource == null ){
                    obj.after('<span class="tag_warn" style="color:red;margin-left:8px">请先选择资源类型!</span>');
                    return false;
                }
                if( resource != 1 ){
                    season_id = 0;
                }

                get_sub_grade_tag(subject,grade,bookid,resource,season_id,obj);

            })
        },false,900);
    };

    //检查文件数量是否一样
    var check_file_num = function(obj,other_obj){
        var resource_file = [];
        var transfer_file = [];
        multi_resource_file = [];  //上传的文件
        whole_upload_num    = 0 ;  //总共上传文件的数量
        if(obj.length > 0){
            for(var x in obj){
                if(resource_file[x] instanceof Array == false ){
                    resource_file[x] = []
                }

                if(obj[x] != ''){
                    var $item = $("." + obj[x]);
                    $item.each(function(){
                        var file_id =  x + "__" + $(this).data('id');
                        resource_file[x].push(file_id);
                    });
                    whole_upload_num += $item.length;
                }
                
            }

            for(var y = 0;y < obj.length;y++){
                if( resource_file[y].length == 0){
                    if(obj[y] != '' && y != 3 ){
                        alert('缺少必传讲义!');
                        return false;
                    }
                }else{
                    transfer_file.push(resource_file[y]);
                }                
            }

            var first_length = transfer_file[0].length;

            for(var x in transfer_file){
                if(transfer_file[x].length > 20){
                    alert('每个课件最多传11个讲义!');
                    return false;
                }

                if( first_length != transfer_file[x].length){
                    alert('不同类型上传的讲义务必数量一致!');
                    return false;
                }

                for(var y = 0;y < first_length;y++){
                    if(multi_resource_file[y] instanceof Array == false ){
                        multi_resource_file[y] = []
                    }
                    multi_resource_file[y].push(transfer_file[x][y]);                                  
                }

            }

        }

        console.log(transfer_file);
        console.log(multi_resource_file);
        return true;
    }

    var clearUpload = function(obj){
        if(obj.length > 0){
            for(var x in obj){
                var $item = $("."+obj[x]);
                if($item.length > 0){
                    $item.each(function(){
                        $(this).parent().remove();
                    })
                }
            }
        }
    }
    var change_tag = function(val){
        $('#id_other_file,#id_tea_file,#id_stu_file,#id_les_file,#id_ex_file,#id_ff_file,#id_video_file').parent().parent().hide();
        clearUpload(['other_file','tea_file','stu_file','les_file','ex_file','ff_file','video_file']);
        if(val == 2){//1v1
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_season",$('.tag_two'),true);

            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('春署秋寒');
            $('.tag_three').parent().parent().hide();
            $('.tag_four').parent().parent().hide();
            $('.tag_five').parent().parent().hide();
            $('#id_other_file,#id_ff_file,#id_video_file').parent().parent().hide();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file').parent().parent().show();

        }else if( val == 1 ){
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_season",$('.tag_two'),true);
            Enum_map.append_option_list("resource_diff_level",$('.tag_five'),true);

            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('春署秋寒');
            $('.tag_three').parent().parent().hide();
            $('.tag_five').parent().parent().hide();
            //get_sub_grade_tag($('.subject').val(),$('.grade').val(),$('.tag_one').val(),$('.tag_four'));
            $('.tag_four').parent().prev().text('学科化标签');
            $('.tag_four').parent().parent().show();
            $('.tag_five').parent().prev().text('难度类型');
            $('.tag_five').parent().parent().show();

            $('#id_other_file,#id_ff_file,#id_video_file').parent().parent().hide();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file').parent().parent().show();
            $('#id_search_tag').show();
        }else if(val == 3){
            $('.subject').empty();
            Enum_map.append_option_list("subject",$('.subject'),true,[1,2,3,4,5]);
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_free",$('.tag_two'),true);
            Enum_map.append_option_list("resource_diff_level",$('.tag_three'),true);
            //get_sub_grade_tag($('.subject').val(),$('.grade').val(),$('.tag_one').val(),$('.tag_four'));
            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('试听类型');
            $('.tag_three').parent().prev().text('难度类型');
            $('.tag_four').parent().prev().text('学科化标签');
            $('.tag_five').parent().parent().hide();
            $('#id_other_file,#id_ff_file,#id_video_file').parent().parent().hide();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file').parent().parent().show();
            $('#id_search_tag').show();
       } else if (val == 4 || val == 5) {
            Enum_map.append_option_list("grade",$('.grade'),true, my_grade);
            Enum_map.append_option_list("resource_volume",$('.tag_five'),true,tag_five);
            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two,.tag_three,.tag_four').parent().parent().hide();
            $('.tag_five').parent().prev().text('上下册');
            $('.tag_five').parent().parent().show();
            $('#id_ff_file,#id_ex_file').parent().parent().show();
            $('#id_other_file,#id_les_file,#id_tea_file,#id_stu_file,#id_video_file').parent().parent().hide();

        } else if (val == 6 ){
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_year",$('.tag_two'),true);
            Enum_map.append_option_list("resource_volume",$('.tag_five'),true,tag_five);
            get_province($('.tag_three'), true);
            $('.tag_three').change(function(){
                get_city($('.tag_four'), $(this).val(), true);
            });
            get_city($('.tag_four'), 110000, true);
            $('.tag_three').append('<option value="-2">请先选择省份</option>');

            $('.tag_one').next().remove();
            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('年份');
            $('.tag_three').parent().prev().text('省份');
            $('.tag_four').parent().prev().text('城市');

            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            $('.tag_five').parent().prev().text('上下册');
            $('.tag_five').parent().parent().show();
            $('#id_tea_file,#id_stu_file,#id_ex_file').parent().parent().show();
            $('#id_les_file,#id_other_file,#id_ff_file,#id_video_file').parent().parent().hide();

            get_book(g_args.tag_one,$('.tag_one'));
            $('#id_search_tag').hide();
            $('.tag_four').parent().find('.tag_warn').remove();
        } else if (val == 7) {
            Enum_map.append_option_list("grade",$('.grade'),true,[100,200,300]);
            $('.tag_one').next().remove();
            $('.tag_one').parent().prev().text('一级知识点');
            $('.tag_two').parent().prev().text('二级知识点');
            $('.tag_three').parent().prev().text('三级知识点');
            $('.tag_four').parent().parent().hide();
            $('.tag_five').parent().parent().hide();

            $('#id_other_file').parent().parent().show();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file,#id_ff_file,#id_video_file').parent().parent().hide();
        } else if (val == 8) {
            $('.grade').parent().parent().hide();
            Enum_map.append_option_list("season",$('.tag_one'),true);
            Enum_map.append_option_list("resource_year",$('.tag_two'),true);

            $('.tag_one').next().remove();
            $('.tag_one').parent().prev().text('四情类型');
            $('.tag_two').parent().prev().text('省份');
            $('.tag_three').parent().prev().text('城市');
            $('.tag_four').parent().parent().hide();
            $('.tag_five').parent().parent().hide();

            $('#id_other_file').parent().parent().show();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file,#id_ff_file,#id_video_file').parent().parent().hide();

        } else if (val == 9){
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_season",$('.tag_two'),true);
            Enum_map.append_option_list("resource_train",$('.tag_three'),true);

            $('.tag_two').parent().prev().text('春暑秋寒');
            $('.tag_three').parent().prev().text('培训资料');
            $('.tag_one,.tag_four,.tag_five').parent().parent().hide();

            $('#id_video_file').parent().parent().show();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file,#id_ff_file,#id_other_file').parent().parent().hide();

        }
    };

    var get_book = function(bookid,obj){

        var resource_type = $('.resource').val();
        var subject = $('.subject').val();
        var grade = $('.grade').val();

        $.ajax({
            type     : "post",
            url      : "/resource/get_resource_type_js",
            dataType : "json",
            data : {
                'resource_type' : resource_type,
                'subject'       : subject,
                'grade'         : grade,
            } ,
            success   : function(result){
                if(result.ret == 0){
                    obj.empty();
                    obj.next('p').remove();
                    var agree_book = result.book;
                    if(agree_book.length == 0) {
                        obj.after('<p style="color:red;">该资源类型、科目、年级下暂无开放的教材版本!</p>');
                    } else {
                        //console.log(bookid);
                        Enum_map.append_option_list("region_version",obj,true,agree_book);
                        if(bookid != 0 && bookid != -1){
                            obj.val(bookid);
                        }else{
                            obj.val(agree_book[0]);
                        }
                    }
                } else {
                    alert(result.info);
                }
            }
        });
    }

    //预览讲义
    $('.opt-look').click(function(){
        var id = $(this).data('file_id');
        console.log(id);
        var newTab=window.open('about:blank');
        do_ajax('/resource/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            console.log(ret);
            if(ret.ret == 0){
                $('.look-pdf').show();
                $('.look-pdf-son').mousedown(function(e){
                    if(e.which == 3){
                        return false;
                    }
                });
                console.log(ret.url);
                newTab.location.href = ret.url;
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    })

    var opt_look = function(data_obj){
        var id = data_obj.data('file_id');
        var newTab=window.open('about:blank');
        do_ajax('/resource/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            if(ret.ret == 0){
                $('.look-pdf').show();
                $('.look-pdf-son').mousedown(function(e){
                    if(e.which == 3){
                        return false;
                    }
                });
                console.log(ret.url);
                newTab.location.href = ret.url;
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    };

    $('.opt-del').on('click', function(){
        do_del();
    });

    var test_func = function(){
        return remove_id;
    }

    var get_qiniu = function(flag,is_multi, is_auto_upload, btn_id,use_type=0,add_class,allow_str,max_size){

        multi_upload_file_new(flag, is_multi, is_auto_upload, btn_id, 0,
                              function(files){
                                  var name_str = '';
                                  if (!is_multi){
                                      //单文件上传
                                      remove_id.push($('.'+add_class).data('id'));
                                      $('.'+add_class).prev().remove();
                                      $('.'+add_class).remove();
                                      name_str = '<br/><span data-id='+files[0].id+' class='
                                          +add_class+' >'+files[0].name+'</span>';
                                      is_multi = 0;

                                  }else{
                                      //多文件上传

                                      var up_file = "<button class='up_file btn btn-info' onclick='up_move($(this))'>上移</button>";                          
                                      var down_file = "<button class='down_file btn btn-primary' onclick='down_move($(this))'>下移</button>";
                                      var dele_file = "<button class='dele_file btn btn-danger' onclick='dele_file($(this))'>删除</button>";
                                      $(files).each(function(i){
                                          name_str = name_str+'<div><span data-id='+files[i].id+' data-index='+i+' class='
                                              +add_class+' >'+files[i].name+'</span>' + up_file + down_file + dele_file + '</div>';
                                      });
                                  }
                               
                                  $('#'+btn_id).after(name_str);
                                
                                  return test_func();

                              },
                              function(up,file) {
                                  $('.close').click();
                                  $('.opt_process').show();

                                  window.onbeforeunload = function (event) {
                                      var c = event || window.event;
                                      if (/webkit/.test(navigator.userAgent.toLowerCase())) {
                                          return "刷新页面将导致正在上传的上传数据丢失！";
                                      } else {
                                          c.returnValue = "刷新页面将导致正在上传的上传数据丢失！";
                                      }
                                  }

                                  //判断不上传的文件
                                  return $.inArray(file.id, test_func());
                              },
                              function(up, file, info) {
                                  var res = $.parseJSON(info.response);
                                  // console.log(up);
                                  // console.log(file);
                                  // console.log(info);
                                  if(!is_multi){ 
                                      if( info.status == 200){
                                          //add_file(last_id, file, res, use_type);                                        
                                      }
                                  }else{
                                      have_upload_num += 1;
                                                                                                  
                                      if( info.status == 200){
                                          for(var x in whole_resource_file){
                                              if( file.id == whole_resource_file[x].file_id){
                                                  // console.log(have_upload_num);
                                                  // console.log(whole_upload_num);
                                                  //console.log( whole_resource_file[x]);
                                                  whole_resource_file[x].file_title =  file.name;
                                                  whole_resource_file[x].file_type =  file.type;
                                                  whole_resource_file[x].file_size =  file.size;
                                                  whole_resource_file[x].file_hash =  res.hash;
                                                  whole_resource_file[x].file_link =  res.key;
                                              }
                                          }                                        
               
                                      }
                         
                                      if( whole_resource_file.length == have_upload_num ){
                                          var data = { 'multi_data' : whole_resource_file };
                                          add_multi_file(data);
                                          console.log(1212);
                                          console.log(whole_resource_file);
                                      }
                                  }
                              },
                              allow_str,max_size,'fsUploadProgress'
                             );

    };

    var add_file = function (id, file, res, use_type){
        $.ajax({
            type     : "post",
            url      : "/resource/add_file",
            dataType : "json",
            data : {
                'resource_id'   : last_id,
                'file_title'    : file.name,
                'file_type'     : file.type,
                'file_size'     : file.size,
                'file_hash'     : res.hash,
                'file_link'     : res.key,
                'file_use_type' : use_type,
            } ,

            success   : function(result){
                if(result.ret == 0){
                    window.onbeforeunload=function(){};
                    //window.location.reload();
                } else {
                    alert(result.info);
                }
            }
        });
    };

    var add_multi_file = function (data){
        $.ajax({
            type     : "post",
            url      : "/resource/add_multi_file",
            dataType : "json",
            data : data,
            success   : function(result){
                if(result.ret == 0){
                    window.onbeforeunload=function(){};
                    //window.location.reload();
                } else {
                    alert(result.info);
                }
            }
        });
    };

    $('.opt-select-item').on('click',function(){
        if( $(this).iCheckValue()){
            var resource_id = $(this).data('id');
            $('.common-table tbody tr').each(function(){
                var other_id = $(this).find('.opt-select-item').data('id');
                if( other_id == resource_id ){
                    //console.log(other_id);
                    $(this).find('.opt-select-item').iCheck("check");
                }
            });
        }else{
            var resource_id = $(this).data('id');
            $('.common-table tbody tr').each(function(){
                var other_id = $(this).find('.opt-select-item').data('id');
                if( other_id == resource_id ){
                    //console.log(other_id);
                    $(this).find('.opt-select-item').iCheck("uncheck");
                }
            }); 
        }
    });

    var do_del = function(){
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
            if( confirm('若删除，则会同时删除与之相关联的其他文件,确定要删除？') ){
                $.ajax({
                    type    : "post",
                    url     : "/resource/del_or_restore_resource",
                    dataType: "json",
                    data    : {
                        "type"        : 3,
                        "res_id_str"  : res_id_info,
                        "file_id_str" : file_id_info,
                    },
                    success : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        }
                    }
                });
            };
        }
    };

    var re_name = function(obj){
        var id_file_title = $("<input />");
        id_file_title.val(obj.data('file_title'));
        var arr= [
            ["文件名称", id_file_title],
        ];

        $.show_key_value_table('重命名', arr,{
            label    : '确认',
            cssClass : 'btn-info',
            action   : function() {
                $.ajax({
                    type     : "post",
                    url      : "/resource/rename_resource",
                    dataType : "json",
                    data : {
                        'file_id'     : obj.data('file_id'),
                        'resource_id' : obj.data('resource_id'),
                        'file_title'  : id_file_title.val(),
                    } ,
                    success  : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        } else {
                            alert(result.info);
                        }
                    }
                });

            }
        },null,false,600);
    };

    var re_upload = function(resource_id,file_id, file_use_type, ex_num){

        if(file_use_type == 3){
            var allow_str = 'mp4,pdf,mp3,MP3,MP4,PDF';
        } else {
            var allow_str = 'pdf,PDF';
        }
        //重新上传
        multi_upload_file('',false,true,'upload_flag',1,'',function(up,file) {
            $('.opt_process').show();
        },function(up, file, info) {
            var res = $.parseJSON(info.response);
            if( info.status == 200){
                $.ajax({
                    type     : "post",
                    url      : "/resource/reupload_resource",
                    dataType : "json",
                    data : {
                        'resource_id'   : resource_id,
                        'file_id'       : file_id,
                        'file_title'    : file.name,
                        'file_type'     : file.type,
                        'file_size'     : file.size,
                        'file_hash'     : res.hash,
                        'file_link'     : res.key,
                        'file_use_type' : file_use_type,
                        'ex_num'        : ex_num,
                    } ,
                    success   : function(result){
                        if(result.ret == 0){
                            // window.location.reload();
                        } else {
                            alert(result.info);
                        }
                    }
                });
            }
        }, allow_str,'fsUploadProgress');

        //上传额外讲义
        multi_upload_file('',true,true,'ex_upload_flag',1,'',function(up,file) {
            $('.opt_process').show();
        },function(up, file, info) {
            var res = $.parseJSON(info.response);
            if( info.status == 200){
                $.ajax({
                    type     : "post",
                    url      : "/resource/reupload_resource",
                    dataType : "json",
                    data : {
                        'resource_id'   : resource_id,
                        'file_title'    : file.name,
                        'file_type'     : file.type,
                        'file_size'     : file.size,
                        'file_hash'     : res.hash,
                        'file_link'     : res.key,
                        'file_use_type' : 3,
                        'ex_num'        : 0,
                    } ,
                    success   : function(result){
                        if(result.ret == 0){
                            // window.location.reload();
                        } else {
                            alert(result.info);
                        }
                    }
                });
            }
        }, 'mp4,pdf,mp3,MP3,MP4,PDF','fsUploadProgress');

    };

    var menu_hide = function(){
        $('#contextify-menu').hide();
        return $('#contextify-menu');
    };

    var get_edit_list = function(obj){
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"      : "/resource/get_list_by_resource_id_js",
            //其他参数
            "args_ex" : {
                resource_id   : obj.data('resource_id'),
                file_use_type : obj.data('file_use_type'),
                ex_num        : obj.data('ex_num'),
            },
            //字段列表
            'field_list' :[
                {
                title:"时间",
                render:function(val,item) {
                    return item.create_time;
                }
            },{
                title:"操作人",
                render:function(val,item) {
                    return item.nick ;
                }
            },{
                title:"类型",
                render:function(val,item) {
                    return item.visit_type_str;
                }
            }
            ],
            filter_list: [],
            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,
        });

    };

    var resource_detail = function(obj){

        var arr= [
            ["角色", obj.data('use_type_str')],
            ["资源类型", obj.data('resource_type_str')],
            ["科目", obj.data('subject_str')],
            ["年级", obj.data('grade_str')],
            [obj.data('tag_one_name'), obj.data('tag_one_str')],
            [obj.data('tag_two_name'), obj.data('tag_two_str')],
            [obj.data('tag_three_name'), obj.data('tag_three_str')],
            [obj.data('tag_four_name'), obj.data('tag_four_str')],
            ["文件名称", obj.data('file_title')],
            ["文件信息",obj.data('file_use_type_str')],
            ["文件大小", obj.data('file_size')+'M'],
        ];
        $.show_key_value_table('文件详情', arr,false,function(){
            $('.bootstrap-dialog-message td').each(function(i){
                if($(this).text() == ''){
                    $(this).parent().hide();
                }
            });
        },false,600);
    };

    //右键自定义
    var options = {items:[
        {text: '重命名', onclick: function() {
            var data_obj = menu_hide();
            re_name(data_obj);
        }},
        {text: '上传新版本',onclick: function() {
            menu_hide();
        }, id:'upload_flag'},
        {text: '上传额外文件',onclick: function() {
            menu_hide();
        }, id:'ex_upload_flag'},
        {text: '删除', onclick: function() {
            var data_obj = menu_hide();
            do_del();
        }},
        {text: '操作记录', onclick: function() {
            var data_obj = menu_hide();
            get_edit_list(data_obj);
        }},
        {text: '文件详情', onclick: function() {
            var data_obj = menu_hide();
            resource_detail(data_obj);
        }},
        {text: '预览讲义', onclick: function() {
            var data_obj = menu_hide();
            opt_look(data_obj);
        }},
    ],before:function(){
        var resource_id   = $(this).attr('resource_id');
        var file_id       = $(this).attr('file_id');
        var ex_num        = $(this).attr('ex_num');
        var file_use_type = $(this).attr('file_use_type');
        re_upload(resource_id, file_id, file_use_type, ex_num);
        //选中标记
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ( file_id == $(this).data('file_id') ) {
                $item.iCheck("check");
            }else{
                $item.iCheck("uncheck");
            }
        } );

    },onshow:function(){}};
    $('.right-menu').contextify(options);

    $('body').click(function(){
        menu_hide();
    });

    $('#id_resource_type').change(function(){
        $('#id_tag_one').val(-1);
        $('#id_tag_two').val(-1);
        $('#id_tag_three').val(-1);
        $('#id_tag_four').val(-1);
        $('#id_tag_five').val(-1);
    });
    var color_id = 0,color_res = 0,color_flag = 0;
    $('.common-table tr').each(function(i){
        if(i>0){
            if($(this).data('resource_id') == color_res){
                $(this).css('background',color_id );
            } else {
                color_res = $(this).data('resource_id');
                (color_flag == 0) ? color_flag = 1: color_flag = 0;
                (color_flag == 0) ? color_id = '#e6e6e6' : color_id = '#bfbfbf';
                $(this).css('background',color_id);
            }
        }
    });
    $('.opt-change').set_input_change_event(load_data);

    $(".opt-sub-tag").click(function(){
        var subject = $('#id_subject').val();
        var grade = $('#id_grade').val();
        var bookid = $('#id_tag_one').val();
        var resource_type = $('#id_resource_type').val();
        if( resource_type != 1){
            //标准试听课
            var season_id = -1;
        }else{
            //1对1精品课程
            var season_id = parseInt($('#id_tag_two').val());
        }
        window.open("/resource/sub_grade_book_tag?subject="+subject+"&grade="+grade+
                    "&bookid="+bookid+"&resource_type="+resource_type+"&season_id="+season_id);
    })

});


function multi_upload_file_new(new_flag,is_multi,is_auto_start,btn_id, is_public_bucket ,select_func,befor_func, complete_func, ext_file,max_size,process_id ){
    if( max_size == undefined ){
        max_size = '15m';
    }
    do_ajax( "/common/get_new_bucket_info",{
        // is_public: is_public_bucket ? 1:0
    },function(ret){
        var domain_name=ret.domain;
        console.log(domain_name);
        var token=ret.token;
        //保证每次new不同的对象
        var qi_niu = ['Qiniu_'+new_flag];
        // console.log(qi_niu[0]);
        qi_niu[0] = new QiniuJsSDK();
        var uploader = qi_niu[0].uploader({
            runtimes: 'html5,flash,html4',
            browse_button: btn_id , //choose files id
            // container: 'container',
            // drop_element: 'container',
            max_file_size: max_size,
            filters: {
                mime_types: [
                    {title: "", extensions: ext_file}
                ]
            },
            flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
            // dragdrop: true,
            chunk_size: '4mb',
            multi_selection: is_multi,
            uptoken: token,
            domain: "http://"+domain_name,
            get_new_uptoken: false,
            auto_start: is_auto_start,
            // log_level: 5,
            init: {
                'BeforeChunkUpload': function(up, file) {
                    // console.log("before chunk upload:", file.name);
                },
                'FilesAdded': function(up, files) {
                    // $('table').show();
                    // $('#success').hide();
                    //删除单选文件的多余文件
                    var remove_file_id = select_func(files);
                    $(remove_file_id).each(function(i,val){
                        if(val != undefined){
                            uploader.removeFile(val);
                            $('#'+val).remove();
                        }
                    });
                    plupload.each(files, function(file) {
                        var progress = new FileProgress(file, 'fsUploadProgress');
                        progress.setStatus("等待...");
                        progress.bindUploadCancel(up);
                    });

                },
                'BeforeUpload': function(up, file) {

                    var is_remove = befor_func(up, file);
                    if(process_id != '') {
                        var progress = new FileProgress(file, process_id);
                        var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                        if (up.runtime === 'html5' && chunk_size) {
                            progress.setChunkProgess(chunk_size);
                        }

                        if(is_remove > -1){
                            uploader.removeFile(file);
                            $('#'+file.id).remove();
                        }
                   }

                },
                'UploadProgress': function(up, file) {
                    if(process_id != '') {
                        var progress = new FileProgress(file, process_id);
                        var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                        progress.setProgress(file.percent + "%", file.speed, chunk_size);
                    }
                },
                'UploadComplete': function() {
                    // $('#success').show();
                },
                'FileUploaded': function(up, file, info) {
                    if(process_id != '') {
                        var progress = new FileProgress(file, process_id);
                        progress.setComplete(up, info.response,false);
                    }
                    complete_func(up, file, info);

                },
                'Error': function(up, err, errTip) {
                    // $('table').show();
                    console.log(err);
                    console.log(errTip);
                    BootstrapDialog.alert(errTip);
                    if(process_id != '') {
                        var progress = new FileProgress(err.file, process_id);
                        progress.setError();
                        progress.setStatus(errTip);
                    }
                } ,
                'Key': function(up, file) {
                    var key = "";
                    var time = (new Date()).valueOf();
                    var match = file.name.match(/.*\.(.*)?/);
                    this.origin_file_name=file.name;
                    var file_name=$.md5(file.name) +time +'.' + match[1];
                    //console.log('gen file_name:'+file_name);
                    return file_name;

                }
            }
        });

        $('#up_load').on('click', function(){
            if($(this).attr('flag') == new_flag){//保证文件是这次上传的
                uploader.start();
            }
        });
    });

};


function up_move(obj){
    //alert(val);
    var curr = obj.parent().index();
    var transfer;
    var curr_obj = obj.parent();
    console.log(curr_obj.index());
    if( curr_obj.index() == 1){
        return false;
    }
    var prev_obj = obj.parent().prev();
    transfer = prev_obj;
    prev_obj.remove();
    curr_obj.after(transfer);

}

function down_move(obj){
    var transfer;
    var curr_obj = obj.parent();
    var last = parseInt(curr_obj.parent().children().length) - 2;

    if( curr_obj.index() == last ){
        return false;
    }
    var next_obj = obj.parent().next();
    transfer = curr_obj;
    curr_obj.remove();
    next_obj.after(transfer);

}

function dele_file(obj){
    var curr_obj = obj.parent();
    curr_obj.remove();
}
