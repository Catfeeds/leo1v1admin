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
        add_or_update();
    });

    var add_or_update = function(id_str = ''){
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

        if(id_str == '') {
            var title = '新建';
        } else {
            var title = '移动';
            id_file.hide();
        }

        $.show_key_value_table(title, arr,{
            label    : '确认',
            cssClass : 'btn-info hide move',
            action   : function() {
                $.ajax({
                    type     : "post",
                    url      : "/resource/add_resource",
                    dataType : "json",
                    data : {
                        'id_str'        : id_str,
                        'user_type'     : id_user_type.val(),
                        'resource_type' : id_resource_type.val(),
                        'subject'       : id_subject.val(),
                        'grade'         : id_grade.val(),
                        'tag_one'       : id_tag_one.val(),
                        'tag_two'       : id_tag_two.val(),
                        'tag_three'     : id_tag_three.val(),
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
        },function(){
            $('.resource').change(function(){
                $('.sel_flag').empty();
                $('.sel_flag').val(0);
                $('.sel_flag').parent().parent().show();
                change_tag($(this).val());
            });

            if(id_str == '') {//移动，没必要new七牛
                multi_upload_file('id_file',1,function(up,file) {
                    $('.close').click();
                    $('.opt_process').show();
                },function(up, file, info) {

                    var res = $.parseJSON(info.response);
                    if( info.status == 200){
                        $.ajax({
                            type     : "post",
                            url      : "/resource/add_resource",
                            dataType : "json",
                            data : {
                                'user_type'     : id_user_type.val(),
                                'resource_type' : id_resource_type.val(),
                                'subject'       : id_subject.val(),
                                'grade'         : id_grade.val(),
                                'tag_one'       : id_tag_one.val(),
                                'tag_two'       : id_tag_two.val(),
                                'tag_three'     : id_tag_three.val(),
                                'file_title'    : file.name,
                                'file_type'     : file.type,
                                'file_size'     : file.size,
                                'file_hash'     : res.hash,
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

            } else {
                $('.move').removeClass('hide');
            }
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

    $('.opt-move').on('click', function(){
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
            add_or_update(id_info);
        }
    });

    $('.opt-del').on('click', function(){
        do_del();
    });

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

    var re_upload = function(resource_id){

        multi_upload_file('upload_flag',1,function(up,file) {
            $('.opt_process').show();
        },function(up, file, info) {
            var res = $.parseJSON(info.response);
            if( info.status == 200){
                $.ajax({
                    type     : "post",
                    url      : "/resource/reupload_resource",
                    dataType : "json",
                    data : {
                        'resource_id'     : resource_id,
                        // 'file_title'    : file.name,
                        'file_type'     : file.type,
                        'file_size'     : file.size,
                        'file_hash'     : res.hash,
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

    //右键自定义
    var options = {items:[
        // {header: '右键功能菜单'},
        // {divider: true},
        {text: '重命名', onclick: function() {
            var data_obj = $(this).parent().parent();
            data_obj.hide();
            re_name(data_obj);
        }},
        {text: '上传新版本', id:'upload_flag'},
        {text: '复制', onclick: function() {
            var data_obj = $(this).parent().parent();
            data_obj.hide();

        }},
        {text: '移动', onclick: function() {
            var data_obj = $(this).parent().parent();
            data_obj.hide();
            // add_or_update(data_obj.data['resource_id']);
            add_or_update(data_obj);
        }},

        {text: '删除', onclick: function() {
            do_del();
        }},
        {text: '下载', onclick: function() {

            var data_obj = $(this).parent().parent();
        }},
        {text: '操作记录', onclick: function() {

            var data_obj = $(this).parent().parent();
        }},
        // {divider: true},
        // {text: '更多...', href: '#'}
    ],before:function(){
        var resource_id = $(this).attr('resource_id');
        re_upload(resource_id);
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

    $('.right-menu').click(function(){
        $('#contextify-menu').hide();
    });

    $('.opt-change').set_input_change_event(load_data);
});
