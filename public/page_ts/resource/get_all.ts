/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-get_all.d.ts" />

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
        use_type     :	$('#id_use_type').val(),
        resource_type :	res_type,
        subject       :	$('#id_subject').val(),
        grade         :	$('#id_grade').val(),
        tag_one       :	$('#id_tag_one').val(),
        tag_two       :	$('#id_tag_two').val(),
        tag_three     :	$('#id_tag_three').val(),
        tag_four      :	$('#id_tag_four').val(),
        file_title    :	$('#id_file_title').val()
    });
}
$(function(){

    $(".opt-sub-tag").click(function(){
        window.open("/resource/sub_grade_book_tag");
    })

    //获取学科化标签
    var get_sub_grade_tag = function(subject,grade,booid,obj,opt_type){
        obj.empty();
        $.ajax({
            type     : "post",
            url      : "/resource/get_sub_grade_book_tag",
            dataType : "json",
            data : {
                'subject' : subject,
                'grade'   : grade,
                'bookid':booid
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
                        if(opt_type == 1){
                            var tag_str = '<option value="-1">全部</option>';
                        }else{
                            var tag_str = '';
                        }
       
                        $.each($(tag_info),function(i,item){                        
                            tag_str = tag_str + '<option value='+item.id+'>'+item.tag+'</option>';
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
    Enum_map.append_option_list("subject", $("#id_subject"),false, my_subject);
    Enum_map.append_option_list("grade", $("#id_grade"),false, my_grade);

    if(tag_one == 'region_version'){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"), false, book);
    } else if(tag_one != ''){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"), );
    }else{
        $("#id_tag_one").append('<option value="-1">全部</option>');
    }

    //测试
    if($("#id_tag_one").children().length < 2){
        var text_book = '<option value="1">人教版</option><option value="2">苏教版</option><option value="3">沪教版</option><option value="4">浙教版</option>';
        $("#id_tag_one").append(text_book);
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

    if(tag_five != ''){
        Enum_map.append_option_list(tag_five, $("#id_tag_five"));
    } else {
        $("#id_tag_five").append('<option value="-1">全部</option>');
    }

    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_tag_one').val(g_args.tag_one);

    if($('#id_resource_type').val() == 3 || $('#id_resource_type').val() == 1 ){
        get_sub_grade_tag($('#id_subject').val(), $('#id_grade').val(),$('#id_tag_one').val(),$('#id_tag_four'), 1);
    } else if($('#id_resource_type').val() == 6) {
        get_province($('#id_tag_two'));
    } else {
        $("#id_tag_four").append('<option value="-1">全部</option>');
    }
    $('#id_tag_two').val(g_args.tag_two);

    var city_num = $('#id_tag_two').val();
    if($('#id_resource_type').val() == 6 && city_num != -1){
        get_city($('#id_tag_three'), city_num);
    }
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

    $('.opt-add').on('click', function(){
        var timestamp = Date.parse(new Date());

        add_resource(timestamp);
    });

    var last_id  = 0, stu_hash = '', stu_link = '';

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
        }
        if(tag_three != ''){
            Enum_map.append_option_list(tag_three,id_tag_three,true);
        }
        if(tag_four != ''){
            Enum_map.append_option_list(tag_four,id_tag_four,true);
        }

        if(tag_five != ''){
            Enum_map.append_option_list(tag_five,id_tag_five,true);
        }

        id_use_type.val(g_args.use_type);
        id_resource_type.val(g_args.resource_type);
        id_subject.val(g_args.subject);
        id_grade.val(g_args.grade);

        var arr= [
            ["角色", id_use_type],
            ["资源类型", id_resource_type],
            ["科目", id_subject],
            ["年级", id_grade],
            [tag_one_name, id_tag_one],
            [tag_two_name, id_tag_two],
            [tag_three_name, id_tag_three],
            [tag_four_name, id_tag_four],
            [tag_five_name, id_tag_five],
            ["上传文件", id_other_file],
            ["上传文件", id_ff_file],
            ["课件版", id_les_file],
            ["老师版", id_tea_file],
            ["学生版", id_stu_file],
            ["额外的讲义", id_ex_file],
        ];

        $.show_key_value_table('新建', arr,{
            label    : '确认',
            cssClass : 'btn-info btn-mark',
            action   : function() {
                if(id_subject.val() == null || id_grade.val() == null || id_tag_one.val() == null){
                    alert('请完善信息!');
                } else {
                    //移除其他文件,计算文件数量
                    if(id_resource_type.val() <= 3){//1v1
                        $('.other_file,.ff_file').each(function(){
                            remove_id.push($(this).data('id'));
                            $(this).remove();
                        });
                        if( $('.les_file,.tea_file,.stu_file').length < 3){
                            alert('缺少上传文件!');
                            return false;
                        }
                        var file_num = 1;

                    } else if(id_resource_type.val() < 6 ){
                        $('.les_file,.tea_file,.stu_file,.other_file').each(function(){
                            remove_id.push($(this).data('id'));
                            $(this).remove();
                        });

                        var file_num = $('.ff_file').length;
                        if( file_num < 1){
                            alert('缺少上传文件!');
                            return false;
                        }
                    } else if(id_resource_type.val() == 6 ){
                        $('.les_file,.other_file').each(function(){
                            remove_id.push($(this).data('id'));
                            $(this).remove();
                        });
                        if( $('.tea_file,.stu_file').length < 2){
                            alert('缺少上传文件!');
                            return false;
                        }
                        var file_num = 1;

                    } else {
                        $('.les_file,.tea_file,.stu_file,.ex_file,.ff_file').each(function(){
                            remove_id.push($(this).data('id'));
                            $(this).remove();
                        });

                        var file_num = $('.other_file').length;
                        if( file_num < 1){
                            alert('缺少上传文件!');
                            return false;
                        }
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
                            if(result.ret == 0){
                                // window.location.reload();
                                last_id = result.resource_id;
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
                    get_book();
                }
                if( $('.resource').val() == 1 || $('.resource').val() == 3){
                    get_sub_grade_tag($('.subject').val(),$('.grade').val(),$('.tag_one').val(),$('.tag_four'));
                }
            });

            $('.tag_one').change(function(){
                if( $('.resource').val() == 1 || $('.resource').val() == 3){
                    get_sub_grade_tag($('.subject').val(),$('.grade').val(),$('.tag_one').val(),$('.tag_four'));
                }
            });

            if( $('.resource').val() == 2 ){
                $('#id_other_file,#id_ff_file').parent().parent().hide();
                get_book();
            } else if ($('.resource').val() ==3 || $('.resource').val() == 1){

                $('.subject').empty();
                Enum_map.append_option_list("subject",$('.subject'),true,[1,2,3,4,5]);

                $('#id_other_file,#id_ff_file').parent().parent().hide();
                get_book();
                get_sub_grade_tag($('.subject').val(),$('.grade').val(),$('.tag_one').val(),$('.tag_four'));

            }else if($('.resource').val() < 6){ //4,5
                $('#id_les_file,#id_other_file,#id_tea_file,#id_stu_file').parent().parent().hide();
                get_book();
            }else if($('.resource').val() == 6){
                $('#id_les_file,#id_other_file,#id_ff_file').parent().parent().hide();
                get_province($('.tag_two'), true);
                $('.tag_two').change(function(){
                    get_city($('.tag_three'), $(this).val(), true);
                });
                get_city($('.tag_three'), 110000, true);

            } else {
                $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file,#id_ff_file').parent().parent().hide();
                if($('.resource').val()==9){
                    get_book();
                }
            }
            //其他版本
            get_qiniu(new_flag,true,false,'id_other_file',0, 'other_file', 'pdf,PDF');
            //课件版
            get_qiniu(new_flag,false,false,'id_les_file',0, 'les_file', 'pdf,PDF');
            //老师版
            get_qiniu(new_flag,false,false,'id_tea_file',1, 'tea_file', 'pdf,PDF');
            //学生版
            get_qiniu(new_flag,false,false,'id_stu_file',2, 'stu_file', 'pdf,PDF');
            //额外讲义
            get_qiniu(new_flag,true,false,'id_ex_file',3, 'ex_file', 'pdf,PDF,mp3,mp4,MP3,MP4');
            //仅对resource_type = 4,5
            get_qiniu(new_flag,false,false,'id_ff_file',0, 'ff_file', 'pdf,PDF');

        },false,600);
    };

    var change_tag = function(val){
        $('#id_other_file,#id_tea_file,#id_stu_file,#id_les_file,#id_ex_file,#id_ff_file').parent().parent().hide();
        if(val == 2){//1v1
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_season",$('.tag_two'),true);

            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('春署秋寒');
            $('.tag_three').parent().parent().hide();
            $('.tag_four').parent().parent().hide();
            $('.tag_five').parent().parent().hide();
            $('#id_other_file,#id_ff_file').parent().parent().hide();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file').parent().parent().show();

        }else if( val == 1 ){
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_season",$('.tag_two'),true);

            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('春署秋寒');
            $('.tag_three').parent().parent().hide();
            $('.tag_five').parent().parent().hide();
            get_sub_grade_tag($('.subject').val(),$('.grade').val(),$('.tag_one').val(),$('.tag_four'));
            $('.tag_four').parent().prev().text('学科化标签');
            $('.tag_four').parent().parent().show();

            $('#id_other_file,#id_ff_file').parent().parent().hide();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file').parent().parent().show();

        }else if(val == 3){
            $('.subject').empty();
            Enum_map.append_option_list("subject",$('.subject'),true,[1,2,3,4,5]);
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_free",$('.tag_two'),true);
            Enum_map.append_option_list("resource_diff_level",$('.tag_three'),true);
            get_sub_grade_tag($('.subject').val(),$('.grade').val(),$('.tag_one').val(),$('.tag_four'));
            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('试听类型');
            $('.tag_three').parent().prev().text('难度类型');
            $('.tag_four').parent().prev().text('学科化标签');
            $('.tag_five').parent().parent().hide();
            $('#id_other_file,#id_ff_file').parent().parent().hide();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file').parent().parent().show();

       } else if (val == 4 || val == 5) {
            Enum_map.append_option_list("grade",$('.grade'),true, my_grade);
            Enum_map.append_option_list("resource_volume",$('.tag_five'),true,tag_five);
            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two,.tag_three,.tag_four').parent().parent().hide();
            $('.tag_five').parent().prev().text('上下册');
            $('.tag_five').parent().parent().show();
            $('#id_ff_file,#id_ex_file').parent().parent().show();
            $('#id_other_file,#id_les_file,#id_tea_file,#id_stu_file').parent().parent().hide();

        } else if (val == 6 ){
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_year",$('.tag_one'),true);
            Enum_map.append_option_list("resource_volume",$('.tag_five'),true,tag_five);
            get_province($('.tag_two'), true);
            $('.tag_two').change(function(){
                get_city($('.tag_three'), $(this).val(), true);
            });

            $('.tag_three').append('<option value="-2">请先选择省份</option>');

            $('.tag_one').next().remove();
            $('.tag_one').parent().prev().text('年份');
            $('.tag_two').parent().prev().text('省份');
            $('.tag_three').parent().prev().text('城市');
            $('.tag_four').parent().parent().hide();
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            $('.tag_five').parent().prev().text('上下册');
            $('.tag_five').parent().parent().show();
            $('#id_tea_file,#id_stu_file,#id_ex_file').parent().parent().show();
            $('#id_les_file,#id_other_file,#id_ff_file').parent().parent().hide();
        } else if (val == 7) {
            Enum_map.append_option_list("grade",$('.grade'),true,[100,200,300]);
            $('.tag_one').next().remove();
            $('.tag_one').parent().prev().text('一级知识点');
            $('.tag_two').parent().prev().text('二级知识点');
            $('.tag_three').parent().prev().text('三级知识点');
            $('.tag_four').parent().parent().hide();
            $('.tag_five').parent().parent().hide();
            $('#id_other_file').parent().parent().show();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file,#id_ff_file').parent().parent().hide();
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
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file,#id_ff_file').parent().parent().hide();

        } else if (val == 9){
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_train",$('.tag_two'),true);

            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('培训资料');
            $('.tag_three,.tag_four').parent().parent().hide();
            $('.tag_five').parent().parent().hide();
            $('#id_other_file').parent().parent().show();
            $('#id_les_file,#id_tea_file,#id_stu_file,#id_ex_file,#id_ff_file').parent().parent().hide();

        }
    };

    var get_book = function(){

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
                    $('.tag_one').empty();
                    $('.tag_one').next().remove();
                    var agree_book = result.book;
                    if(agree_book.length == 0) {
                        $('.tag_one').after('<p style="color:red;">该资源类型、科目、年级下暂无开放的教材版本!</p>');
                    } else {
                        Enum_map.append_option_list("region_version",$('.tag_one'),true,agree_book);
                    }
                } else {
                    alert(result.info);
                }
            }
        });
    }

    $('.opt-del').on('click', function(){
        do_del();
    });

    var test_func = function(){
        return remove_id;
    }

    var get_qiniu = function(flag,is_multi, is_auto_upload, btn_id,use_type=0,add_class,allow_str){

        multi_upload_file(flag, is_multi, is_auto_upload, btn_id, 0,
                          function(files){
                              var name_str = '';
                              if (!is_multi){
                                  remove_id.push($('.'+add_class).data('id'));
                                  $('.'+add_class).prev().remove();
                                  $('.'+add_class).remove();
                              }
                              $(files).each(function(i){
                                  name_str = name_str+'<br/><span data-id='+files[i].id+' class='
                                      +add_class+' >'+files[i].name+'</span>';
                              });
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
                              if( info.status == 200 && last_id >0 ){
                                  add_file(last_id, file, res, use_type);
                                  if( btn_id == 'id_other_file'){
                                      last_id = last_id -1;
                                  }
                              }
                          },
                          allow_str,'fsUploadProgress'
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
                    // window.location.reload();
                } else {
                    alert(result.info);
                }
            }
        });
    };

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
        },'',false,600);
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

});
