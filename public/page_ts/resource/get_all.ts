/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-get_all.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        use_type     :	$('#id_use_type').val(),
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

    Enum_map.append_option_list("use_type", $("#id_use_type"),true);
    Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[1,2,3,4,5,6,7,9]);
    Enum_map.append_option_list("subject", $("#id_subject"),false, my_subject);
    Enum_map.append_option_list("grade", $("#id_grade"),false, my_grade);

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

    $('.opt-add').on('click', function(){
        add_resource();
    });

    var last_id  = 0, stu_hash = '', stu_link = '';

    var add_resource = function(){

        var id_use_type     = $("<select class=\"use\"/>");
        var id_resource_type = $("<select class=\"resource\"/>");
        var id_subject       = $("<select/>");
        var id_grade         = $("<select class=\"grade sel_flag\"/>");
        var id_tag_one       = $("<select class=\"tag_one sel_flag\"/>");
        var id_tag_two       = $("<select class=\"tag_two sel_flag\"/>");
        var id_tag_three     = $("<select class=\"tag_three sel_flag\"/>");
        var id_other_file    = $("<button class=\"btn\" id=\"id_other_file\">选择文件</button>");//其他
        var id_les_file      = $("<button class=\"btn\" id=\"id_les_file\">选择文件</button>");//课件
        var id_tea_file      = $("<button class=\"btn\" id=\"id_tea_file\">选择文件</button>");//老师
        var id_stu_file      = $("<button class=\"btn\" id=\"id_stu_file\">选择文件</button>");//学生

        var id_tag_four      = $("<select class=\"tag_four sel_flag\"/>");

        Enum_map.append_option_list("use_type",id_use_type,true);
        Enum_map.append_option_list("resource_type",id_resource_type,true,[1,2,3,4,5,6,7,9]);
        Enum_map.append_option_list("subject",id_subject,true,my_subject);
        Enum_map.append_option_list("grade",id_grade,true,my_grade);
        Enum_map.append_option_list("region_version",id_tag_one,true);
        // Enum_map.append_option_list("resource_type2",id_tag_two,true);
        Enum_map.append_option_list("resource_season",id_tag_three,true);

        Enum_map.append_option_list("resource_season",id_tag_four,true);

        id_use_type.val(g_args.use_type);
        id_resource_type.val(g_args.resource_type);
        id_subject.val(g_args.subject);
        id_grade.val(g_args.grade);

        var arr= [
            ["角色：", id_use_type],
            ["资源类型：", id_resource_type],
            ["科目：", id_subject],
            ["年级：", id_grade],
            ["教材版本：", id_tag_one],
            ["试听类型：", id_tag_two],
            ["难度类型：", id_tag_three],
            ["学科化标签：", id_tag_four],
            ["上传文件：", id_other_file],
            ["课件版：", id_les_file],
            ["老师版：", id_tea_file],
            ["学生版：", id_stu_file],
        ];

        $.show_key_value_table('新建', arr,{
            label    : '确认',
            cssClass : 'btn-info btn-mark',
            action   : function() {

                var file_num = $('.add_file').length;
                if(file_num < 2){
                    file_num = 1;
                }
                //应先判断信息完整性
                $.ajax({
                    type     : "post",
                    url      : "/resource/add_resource",
                    dataType : "json",
                    data : {
                        'use_type'     : id_use_type.val(),
                        'resource_type' : id_resource_type.val(),
                        'subject'       : id_subject.val(),
                        'grade'         : id_grade.val(),
                        'tag_one'       : id_tag_one.val(),
                        'tag_two'       : id_tag_two.val(),
                        'tag_three'     : id_tag_three.val(),
                        'tag_four'      : id_tag_four.val(),
                        'add_num'       : file_num,
                    } ,
                    success : function(result){
                        if(result.ret == 0){
                            console.log(result);
                            // window.location.reload();
                            last_id = result.resource_id;
                            $('#up_load').click();//开始上传

                        } else {
                            alert(result.info);
                        }
                    }
                });

            }
        },function(){

            $('.resource').change(function(){
                $('.sel_flag').empty();
                $('.sel_flag').val(0);
                $('.sel_flag').parent().parent().show();
                var type_id = $(this).val();
                change_tag(type_id);

            });

            if( $('.resource').val() <3 ){
                //课件版
                get_qiniu(false,false,'id_les_file',0);
                //老师版
                get_qiniu(false,false,'id_tea_file',1);
                //学生版
                get_qiniu(false,false,'id_stu_file',2);
            } else if($('.resource').val() == 6){
                 //老师版
                get_qiniu(false,false,'id_tea_file',1);
                //学生版
                get_qiniu(false,false,'id_stu_file',2);
            } else{
                //其他版本
                get_qiniu(true,false,'id_other_file',0,'add_file');
            }
        },false,600);
    };

    var change_tag = function(val){
        $('#id_stu_file').parent().parent().hide();
        if(val < 3){//1v1
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("region_version",$('.tag_one'),true);
            Enum_map.append_option_list("resource_type2",$('.tag_two'),true);
            Enum_map.append_option_list("resource_season",$('.tag_three'),true);
            $('.tag_one').parent().prev().text('教材版本：');
            $('.tag_two').parent().prev().text('资料类型：');
            $('.tag_three').parent().prev().text('春署秋寒：');
        } else if(val == 3){
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
            Enum_map.append_option_list("resource_free",$('.tag_one'),true);
            Enum_map.append_option_list("resource_diff_level",$('.tag_two'),true);
            $('.tag_one').parent().prev().text('试听类型：');
            $('.tag_two').parent().prev().text('难度类型：');
            $('.tag_three').parent().parent().hide();
        } else if (val == 4 || val == 5) {
            Enum_map.append_option_list("grade",$('.grade'),true, my_grade);
            Enum_map.append_option_list("region_version",$('.tag_one'),true);
            $('.tag_one').parent().prev().text('教材版本：');
            $('.tag_two').parent().parent().hide();
            $('.tag_three').parent().parent().hide();
        } else if (val == 6 ){
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
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
            Enum_map.append_option_list("grade",$('.grade'),true,my_grade);
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

    var get_qiniu = function(is_multi, is_auto_upload, btn_id,use_type=0,add_class){

        multi_upload_file(is_multi,is_auto_upload,btn_id,1,function(files){
            var name_str = '';
            $(files).each(function(i){
                name_str = name_str+'<br/><span class="'+add_class+'">'+files[i].name+'</span>';;
            });
            $('#'+btn_id).after(name_str);
        },function(up,file) {
            $('.close').click();
            $('.opt_process').show();
        },function(up, file, info) {
            var res = $.parseJSON(info.response);
            if( info.status == 200 && last_id >0 ){
                add_file(last_id, file, res, use_type);
                if(is_multi == true){
                    last_id = last_id -1;
                }
            }
        }, ["jpg","png"],'fsUploadProgress');

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
                    // last_id = result.resource_id;
                    // add_stu_hash(last_id,stu_hash,stu_link);
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
            if( confirm('确定要删除？') ){
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
                            // window.location.reload();
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
            ["角色：", obj.data('use_type_str')],
            ["资源类型：", obj.data('resource_type_str')],
            ["科目：", obj.data('subject_str')],
            ["年级：", obj.data('grade_str')],
            [obj.data('tag_one_name')+"：", obj.data('tag_one_str')],
            [obj.data('tag_two_name')+"：", obj.data('tag_two_str')],
            [obj.data('tag_three_name')+"：", obj.data('tag_three_str')],
            [obj.data('tag_four_name')+"：", obj.data('tag_four_str')],
            ["老师版：", obj.data('file_title')],
            ["文件大小：", obj.data('file_size')+'M'],
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
    $('.opt-change').set_input_change_event(load_data);
});
