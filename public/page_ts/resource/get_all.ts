/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-get_all.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        user_type     :	$('#id_user_type').val(),
        resource_type :	$('#id_resource_type').val(),
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

    Enum_map.append_option_list("user_type", $("#id_user_type"),true);
    Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[1,2,3,4,5,6,7,9]);
    Enum_map.append_option_list("subject", $("#id_subject"));
    Enum_map.append_option_list("grade", $("#id_grade"));
    if(tag_one != ''){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"));
    }
    if(tag_two != ''){
        Enum_map.append_option_list(tag_two, $("#id_tag_two"));
    }
    if(tag_three != ''){
        Enum_map.append_option_list(tag_three, $("#id_tag_three"));
    }
    if(tag_four != ''){
        Enum_map.append_option_list(tag_four, $("#id_tag_four"));
    }

    $('#id_user_type').val(g_args.user_type);
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

    $('.opt-add').on('click', function(){
        add_resource();
    });

    var last_id  = 0;
    var stu_hash = '';
    var stu_link = '';

    var add_resource = function(){

        var id_user_type     = $("<select class=\"user\"/>");
        var id_resource_type = $("<select class=\"resource\"/>");
        var id_subject       = $("<select/>");
        var id_grade         = $("<select class=\"grade sel_flag\"/>");
        var id_tag_one       = $("<select class=\"tag_one sel_flag\"/>");
        var id_tag_two       = $("<select class=\"tag_two sel_flag\"/>");
        var id_tag_three     = $("<select class=\"tag_three sel_flag\"/>");
        var id_les_file      = $("<button class=\"btn\" id=\"id_les_file\">选择文件</button>");//课件
        var id_tea_file      = $("<button class=\"btn\" id=\"id_tea_file\">选择文件</button>");//老师
        var id_stu_file      = $("<button class=\"btn\" id=\"id_stu_file\">选择文件</button>");//学生

        var id_tag_four      = $("<select class=\"tag_four sel_flag\"/>");

        Enum_map.append_option_list("user_type",id_user_type,true);
        Enum_map.append_option_list("resource_type",id_resource_type,true,[1,2,3,4,5,6,7,9]);
        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("grade",id_grade,true);
        Enum_map.append_option_list("region_version",id_tag_one,true);
        Enum_map.append_option_list("resource_type2",id_tag_two,true);
        Enum_map.append_option_list("resource_season",id_tag_three,true);

        Enum_map.append_option_list("resource_season",id_tag_four,true);

        id_user_type.val(g_args.user_type);
        id_resource_type.val(g_args.resource_type);
        id_subject.val(g_args.subject);
        id_grade.val(g_args.grade);

        var arr= [
            ["角色：", id_user_type],
            ["资源类型：", id_resource_type],
            ["科目：", id_subject],
            ["年级：", id_grade],
            ["教材版本：", id_tag_one],
            ["资料类型：", id_tag_two],
            ["春署秋寒：", id_tag_three],
            ["春署秋寒：", id_tag_four],
            ["课件版：", id_les_file],
            ["老师版：", id_tea_file],
            ["学生版：", id_stu_file],
        ];

        $.show_key_value_table('新建', arr,{
            label    : '确认',
            cssClass : 'btn-info btn-mark',
            action   : function() {

                //应先判断信息完整性
                $.ajax({
                    type     : "post",
                    url      : "/resource/add_resource",
                    dataType : "json",
                    data : {
                        'use_type'     : id_user_type.val(),
                        'resource_type' : id_resource_type.val(),
                        'subject'       : id_subject.val(),
                        'grade'         : id_grade.val(),
                        'tag_one'       : id_tag_one.val(),
                        'tag_two'       : id_tag_two.val(),
                        'tag_three'     : id_tag_three.val(),
                        'tag_four'      : id_tag_four.val(),
                    } ,
                    success : function(result){
                        if(result.ret == 0){
                            console.log(result);
                            // window.location.reload();
                            last_id = result.resource_id;
                            $('#up_load').click();//开始上传
                            // add_stu_hash(last_id,stu_hash,stu_link);
                        } else {
                            alert(result.info);
                        }
                    }
                });
            }
        },function(){

            $('#id_stu_file').parent().parent().hide();
            $('.resource').change(function(){
                $('.sel_flag').empty();
                $('.sel_flag').val(0);
                $('.sel_flag').parent().parent().show();
                change_tag($(this).val());
            });

            $('.tag_two').change(function(){
                if( $('.resource').val()<3 && $(this).val() == 2){
                    console.log(1);
                    $('#id_stu_file').parent().parent().show();
                } else {
                    console.log(2);
                    $('#id_stu_file').parent().parent().hide();
                }
            });

            //课件版
            multi_upload_file(true,false,'id_les_file',1,function(files){
                var name_str = '';
                $(files).each(function(i){
                    name_str = name_str+'<br/><span>'+files[i].name+'</span>';;
                });
                $('#id_les_file').after(name_str);
            },function(up,file) {

                $('.close').click();
                $('.opt_process').show();
            },function(up, file, info) {
                var res = $.parseJSON(info.response);
                if( info.status == 200 && last_id >0 ){
                    add_file(last_id, file, res, 0);
                }
            }, ["jpg","png"],'fsUploadProgress');

            //老师版
            multi_upload_file(false,false,'id_tea_file',1,function(files){
                var name_str = '';
                $(files).each(function(i){
                    name_str = name_str+'<br/><span>'+files[i].name+'</span>';;
                });
                $('#id_tea_file').after(name_str);
            },function(){},function(up, file, info) {
                var res = $.parseJSON(info.response);
                if( info.status == 200 && last_id >0 ){
                    add_file(last_id, file, res, 1);
                }
            }, ["jpg","png"],'fsUploadProgress');

            //学生版
            multi_upload_file(false,false,'id_stu_file',1,function(files){
                var name_str = '';
                $(files).each(function(i){
                    name_str = name_str+'<br/><span>'+files[i].name+'</span>';;
                });
                $('#id_stu_file').after(name_str);
            },function(){},function(up, file, info) {
                var res = $.parseJSON(info.response);
                if( info.status == 200 && last_id >0 ){
                    add_file(last_id, file, res, 2);
                }
            }, ["jpg","png"],'fsUploadProgress');

        },false,600);
    };

    var change_tag = function(val){
        $('#id_stu_file').parent().parent().hide();
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
            Enum_map.append_option_list("resource_diff_level",$('.tag_two'),true);
            $('.tag_one').parent().prev().text('试听类型：');
            $('.tag_two').parent().prev().text('难度类型：');
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
            $('#id_stu_file').parent().parent().show();
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
        do_del();
    });

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
                    // last_id = result.resource_id;
                    // add_stu_hash(last_id,stu_hash,stu_link);
                } else {
                    alert(result.info);
                }
            }
        });
    }

    var do_del = function(){
        var id_list = [];
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                id_list.push( $(this).data('id') );
            }
        });

        if(id_list.length == 0) {
            BootstrapDialog.alert('请先选择文件！');
        } else {
            var id_info = JSON.stringify(id_list);
            if( confirm('确定要删除？') ){
                $.ajax({
                    type    : "post",
                    url     : "/resource/del_resource",
                    data    : "id_str="+id_info,
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
            ["文件名称：", id_file_title],
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

    var re_upload = function(resource_id,file_id, file_use_type){

        multi_upload_file(false,true,'upload_flag',1,'',function(up,file) {
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
        }, ["jpg","png"],'fsUploadProgress');

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
                'resource_id'   :obj.data('resource_id'),
                'file_use_type' :obj.data('file_use_type'),
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
            },{
                title:"文件大小",
                render:function(val,item) {
                    return $(item.file_size );
                }
            }] ,
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
            ["角色：", obj.data('user_type_str')],
            ["资源类型：", obj.data('resource_type_str')],
            ["科目：", obj.data('subject_str')],
            ["年级：", obj.data('grade_str')],
            [obj.data('tag_one_name')+"：", obj.data('tag_one_str')],
            [obj.data('tag_two_name')+"：", obj.data('tag_two_str')],
            [obj.data('tag_three_name')+"：", obj.data('tag_three_str')],
            ["老师版：", obj.data('file_title')],
        ];

        $.show_key_value_table('文件详情', arr,false,function(){},false,600);
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
        var file_use_type = $(this).attr('file_use_type');
        re_upload(resource_id, file_id, file_use_type);
        //选中标记
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ( resource_id == $(this).data('id') ) {
                $item.iCheck("check");
            }else{
                $item.iCheck("uncheck");
            }
        } );

    }};
    $('.right-menu').contextify(options);

    $('body').click(function(){
        menu_hide();
    });

    $('#id_resource_type').change(function(){
        $('#id_tag_one').val(-1);
        $('#id_tag_two').val(-1);
        $('#id_tag_three').val(-1);
    });
    $('.opt-change').set_input_change_event(load_data);
});
