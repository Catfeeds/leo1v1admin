/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-tea_resource.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        order_by_str : g_args.order_by_str,
        cur_dir:	$('#id_cur_dir').val()
    });
}
$(function(){

    var remove_id = [], grade_info = [101,102,103,104,105,106,201,202,203,204,205,206,301,302,303], all_dir = {},sel_dir = [];

    var test_func = function(){
        return remove_id;
    }

    var alert_func = function(ret){
        if(ret.ret == 0){
            BootstrapDialog.alert("操作成功！");
            setTimeout(function(){
                window.location.reload();
            },1000);
        } else {
            BootstrapDialog.alert(ret.info);
        }
    }

    $('.opt-add-dir').on('click', function(){
        var dir_name = $("<input/>");
        var arr=[
            ["文件夹名称", dir_name  ]
        ];
        $.show_key_value_table("新建文件夹", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/teacher_info/tea_edit_dir", {
                    "name"   : dir_name.val(),
                    "type"   : 'add',
                    "dir_id" : cur_dir_id
                },function(ret){
                    alert_func(ret);
                });
            }
        } );

    });

    $('.opt-add-file').on('click',function(){
        var timestamp = Date.parse(new Date());
        add_resource(timestamp);
    });

    $('.opt-del').on('click', function(){
        var id_list = [];
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                var del_info = $(this).attr('is_dir')+'|'+$(this).data('id');
                id_list.push( del_info );
            }
        });

        if(id_list.length == 0) {
            BootstrapDialog.alert('请先选择文件！');
        } else {

            var id_info  = JSON.stringify(id_list);
            if( confirm('确定要删除？') ){
                do_ajax("/teacher_info/del_dir_or_file",
                        {"id_info"  : id_info},
                        function(ret){
                            alert_func(ret);
                        });
            }
        }

    });

    $('.opt-move').on('click', function(){
        sel_dir = [];
        var id_list = [];
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                var del_info = $(this).attr('is_dir')+'|'+$(this).data('id');
                id_list.push( del_info );
                if($(this).attr('is_dir') == -1){
                    sel_dir.push($(this).data('id'));
                }
            }
        });

        if(id_list.length == 0) {
            BootstrapDialog.alert('请先选择文件！');
        } else {

            var id_info  = JSON.stringify(id_list);
            var arr = [];

            $.show_key_value_table('移动至', arr,{
                label    : '确认',
                cssClass : 'btn-info ',
                action   : function() {
                    if( $('.modal-body .select').attr('son_id') == undefined ){
                        var move_to = $('.modal-body .opt-dir').last().attr('dir_id');
                    } else {
                        var move_to = $('.modal-body .select').attr('son_id');
                    }
                    if(move_to != undefined){
                        do_ajax('/teacher_info/move_dir_or_file',{
                            'move_to' : move_to,
                            'id_info' : id_info,
                        },function(ret){
                            alert_func(ret);
                        });
                    }
                }
            },function(){

                $('.modal-body table td').text('');
                $('.modal-body table td').first().remove();
                $('.modal-body table td').first().append('<a class="opt-dir"></a>');
                $('.modal-body table tr').last().after('<tr><td class="son-dir"></td></tr>');
                do_ajax('/teacher_info/get_all_dir_js',{},function(ret){
                    if(ret.ret==0){
                        all_dir = $.parseJSON(ret.dir_list);
                        var son_dir = '';
                        var top_dir = '<a class="opt-dir" dir_id="0">我的资料 /</a>';
                        refush_dir(0, top_dir);
                    }
                });
            },false,600);
        }

    });

    var refush_dir = function(pid, str){
        $('.modal-body .opt-dir').last().after(str);
        $('[son_id]').removeClass('select color-red');
        var son_dir = '';
        $.each(all_dir, function(i,dir){
            var cur_id = parseInt(dir.dir_id);
            if( dir.pid == $('.opt-dir').last().attr('dir_id') && $.inArray(cur_id, sel_dir) == -1 ){
                son_dir = son_dir + '<a son_id="'+dir.dir_id+'">'+dir.name+'</a></br>';
            }
        });

        $('.son-dir').empty();
        $('.son-dir').append(son_dir);
        $('.modal-body a').css('cursor', 'pointer');

        $('[son_id]').on('click',function(){
            $('[son_id]').removeClass('select color-red');
            $(this).addClass('select color-red');
        });
        $('[son_id]').on('dblclick',function(){
            var this_dir = $(this).attr('son_id');
            var str = '<a class="opt-dir" dir_id="'+this_dir+'">'+$(this).text()+' /</a>';
            refush_dir(this_dir, str);
        });
        $('.opt-dir').unbind('click');
        $('.opt-dir').each(function(){
            $(this).on('click',function(){
                $(this).nextAll().remove();
                $(this).remove();
                var this_dir = $(this).attr('dir_id');
                var str = '<a class="opt-dir" dir_id="'+this_dir+'">'+$(this).text()+'</a>';
                refush_dir(this_dir, str);
            });
        });
    }

    $('#id_cur_dir').val(g_args.cur_dir);

    $("#id_select_all").on("click",function(){
        $(".opt-select-item").iCheck("check");
    });

    var down_file = function(){
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                var id = $(this).data('id');
                do_ajax('/teacher_info/tea_look_resource',{'tea_res_id':id},function(ret){
                    if(ret.ret == 0){
                        $.wopen(ret.url);
                        // $('.look-pdf').show();
                        // PDFObject.embed(ret.url, ".look-pdf");
                    } else {
                        BootstrapDialog.alert(ret.info);
                    }
                });
            }
        });

    };


    var add_resource = function(new_flag){

        var id_resource_type = $("<select class=\"resource\"/>");
        var id_subject       = $("<select class=\"subject\"/>");
        var id_grade         = $("<select class=\"grade sel_flag\"/>");

        var id_tag_one       = $("<select class=\"tag_one sel_flag\"/>");
        var id_tag_two       = $("<select class=\"tag_two sel_flag\"/>");
        var id_tag_three     = $("<select class=\"tag_three sel_flag\"/>");
        var id_tag_four      = $("<select class=\"tag_four sel_flag\"/>");
        var id_tea_file      = $("<button class=\"btn\" id=\"id_tea_file\">选择文件</button>");

        Enum_map.append_option_list("resource_type",id_resource_type,true,[1,2,3,4,5,6]);
        Enum_map.append_option_list("subject",id_subject,true);
        // Enum_map.append_option_list("grade",id_grade,true,grade_info);
        Enum_map.append_option_list("grade",id_grade,true);

        Enum_map.append_option_list("region_version", id_tag_one,true );
        Enum_map.append_option_list("resource_season",id_tag_two,true);

        var arr= [
            ["资源类型", id_resource_type],
            ["科目", id_subject],
            ["年级", id_grade],
            ["教材版本", id_tag_one],
            ["春暑秋寒", id_tag_two],
            ["", id_tag_three],
            ["", id_tag_four],
            ["选择文件",id_tea_file],
        ];

        $.show_key_value_table('上传文件', arr,{
            label    : '确认',
            cssClass : 'btn-info btn-mark',
            action   : function() {
                // if(id_subject.val() <= 0  || id_grade.val() <= 0 || id_tag_one.val() <= 0){
                //     alert('请完善信息!');
                //     return false;
                // } else {
                    var file_num = $('.tea_file').length;
                    if( file_num < 1){
                        alert('请选择上传文件!');
                        return false;
                    }
                    $('#up_load').attr('flag', new_flag);//开始上传
                    $('#up_load').click();//开始上传
                // }
            }
        },function(){
            $('.resource,.tag_two,.tag_three,.tag_four').parent().parent().hide();

            $('#id_tea_file').after('<span style="padding-left:5px;">提示：文件大小不超过15M</span>');
            $('.sel_flag').each(function(){
                if($(this).parent().prev().text() == ''){
                    $(this).parent().parent().hide();
                }
            });

            $('.resource').change(function(){
                $('.sel_flag').empty();
                $('.sel_flag').val(0);
                $('.sel_flag').parent().parent().show();
                var type_id = $(this).val();
                change_tag(type_id);

            });

            multi_upload_file(new_flag,false,false,"id_tea_file",0,function(files){
                var name_str = '';
                remove_id.push($('.tea_file').data('id'));
                $('.tea_file').prev().remove();
                $('.tea_file').remove();

                $(files).each(function(i){
                    name_str = name_str+'<br/><span data-id='+files[i].id+' class="tea_file" >'+files[i].name+'</span>';;
                });
                $('#id_tea_file').after(name_str);
                return test_func();

            },function(up,file) {
                //判断不上传的文件
                $('.close').click();
                $('.opt_process').show();

                return $.inArray(file.id, test_func());

            },function(up, file, info) {

                var res = $.parseJSON(info.response);
                if( info.status == 200 ){
                    do_ajax("/teacher_info/tea_edit_file", {
                        'type'          : 'add',
                        'resource_type' : id_resource_type.val(),
                        'subject'       : id_subject.val(),
                        'grade'         : id_grade.val(),
                        'tag_one'       : id_tag_one.val(),
                        'tag_two'       : id_tag_two.val(),
                        'tag_three'     : id_tag_three.val(),
                        'tag_four'      : id_tag_four.val(),
                        'file_title'    : file.name,
                        'file_type'     : file.type,
                        'file_size'     : file.size,
                        'file_hash'     : res.hash,
                        'file_link'     : res.key,
                        'dir_id'        : cur_dir_id,
                    },function(ret){
                        alert_func(ret);
                    });

                }
            }, 'mp4,pdf,mp3,MP3,MP4,PDF','fsUploadProgress');

        },false,600);
    };

    var change_tag = function(val){
        if(val < 3){//1v1
            Enum_map.append_option_list("subject", $(".subject"),true);
            Enum_map.append_option_list("grade",$('.grade'),true,grade_info);
            Enum_map.append_option_list("region_version",$('.tag_one'),true);
            Enum_map.append_option_list("resource_season",$('.tag_two'),true);

            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('春署秋寒');
            $('.tag_three').parent().parent().hide();
            $('.tag_four').parent().parent().hide();
        } else if(val == 3){
            $('.subject').empty();
            Enum_map.append_option_list("subject",$('.subject'),true,[1,2,3,4,5]);
            Enum_map.append_option_list("grade",$('.grade'),true,grade_info);
            Enum_map.append_option_list("region_version",$('.tag_one'),true);
            Enum_map.append_option_list("resource_free",$('.tag_two'),true);
            Enum_map.append_option_list("resource_diff_level",$('.tag_three'),true);
            get_sub_grade_tag($('.subject').val(), $('.grade').val(), $('.tag_four'));
            $('.subject,.grade').change(function(){
                get_sub_grade_tag($('.subject').val(),$('.grade').val(),$('.tag_four'));
            });
            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two').parent().prev().text('试听类型');
            $('.tag_three').parent().prev().text('难度类型');
            $('.tag_four').parent().prev().text('学科化标签');
       } else if (val == 4 || val == 5) {
            Enum_map.append_option_list("subject", $(".subject"),true);
           Enum_map.append_option_list("grade",$('.grade'),true,grade_info);
            Enum_map.append_option_list("region_version",$('.tag_one'),true);
            $('.tag_one').parent().prev().text('教材版本');
            $('.tag_two,.tag_three,.tag_four').parent().parent().hide();
        } else if (val == 6 ){
            Enum_map.append_option_list("subject", $(".subject"),true);
            Enum_map.append_option_list("grade",$('.grade'),true,grade_info);
            Enum_map.append_option_list("resource_year",$('.tag_one'),true);

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
        }
    };

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

    var sel_tr = {};
    var re_name = function(obj){
        var id_new_name = $("<input />");
        id_new_name.val(obj.data('file_title'));
        var arr= [
            ["文件名称", id_new_name],
        ];

        $.show_key_value_table('重命名', arr,{
            label    : '确认',
            cssClass : 'btn-info',
            action   : function() {
                do_ajax("/teacher_info/rename_dir_or_file",{
                    'file_id'  : obj.data('file_id'),
                    'id'       : obj.data('tea_res_id'),
                    'new_name' : id_new_name.val(),
                } , function(ret){
                    alert_func(ret);
                });
            }
        },'',false,600);
    };

    var re_upload = function(obj){

        multi_upload_file('',false,true,'upload_flag',0,function(){},function(up,file) {
            $('.opt_process').show();
        },function(up, file, info) {
            var res = $.parseJSON(info.response);
            if( info.status == 200){
                do_ajax('/teacher_info/tea_file_reupload',{
                    'file_title' : file.name,
                    'file_type'  : file.type,
                    'file_size'  : file.size,
                    'file_hash'  : res.hash,
                    'file_link'  : res.key,
                    'tea_res_id' : obj.data('tea_res_id'),
                }, function(ret){
                    alert_func(ret);
                });
            }
        }, 'mp4,pdf,mp3,MP3,MP4,PDF','fsUploadProgress');
    };

    //右键自定义
    var options = {items:[
        {text: '重命名', onclick: function() {
            $('#contextify-menu').hide();
            re_name(sel_tr);
        }},
        {text: '上传新版本',onclick: function() {
            $('#contextify-menu').hide();
        },class: 'hide', id:'upload_flag'},
        {text: '删除', onclick: function() {
            $('#contextify-menu').hide();
            $('.opt-del').click();
        }},
        {text: '下载', onclick: function() {
            $('#contextify-menu').hide();
            down_file();
        }, class:'hide', id:'down_flag'},
        {text: '移动', onclick: function() {
            $('#contextify-menu').hide();
            $('.opt-move').click();
        }},
    ],before:function(one){
        sel_tr = $(one);
        var id = $(one).data('tea_res_id');
        var is_dir = $(one).data('file_id');
        if(is_dir == 0){
            re_upload($(one));
        }

        //选中标记
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ( is_dir == $(this).attr('is_dir') && id == $(this).data('id')) {
                $item.iCheck("check");
            }else{
                $item.iCheck("uncheck");
            }
        } );

    },onshow:function(two){
        var is_dir = $(two).data('file_id');
        if(is_dir == 0){
            $('#upload_flag,#down_flag').removeClass('hide');
        }
    }};
    $('.right-menu').contextify(options);

    $('body').click(function(){
        $('#contextify-menu').hide();
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

    $('.opt-look').on('click',function(){
        var id = $(this).data('file_id');
        do_ajax('/teacher_info/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            //console.log(ret);
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

    $('.opt-look_new').on('click',function(){
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
                    console.log(ret.url);
                    var arr_url = ret.url.split("?");
                    var pdf = GetUrlRelativePath(ret.url);
                    var app = arr_url[1];
                    var pdf_name = pdf.split(".");
                    pdf_name = pdf_name[0];
                    var type = 0;
                    if(ret.url.indexOf("7tszue.com2.z0.glb.qiniucdn.com")!=-1){
                        type = 4;
                    }
                    if(ret.url.indexOf("ebtest.qiniudn.com")!=-1){
                        type = 3;
                    }
                    if(ret.url.indexOf("teacher-doc.leo1v1.com")!=-1){
                        type = 2;
                    }

                    $.wopen("/teacher_info/look?"+app+"&url="+pdf_name+"&type="+type);
                    return false;
                }
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    });

    function GetUrlRelativePath(url){
　　　　var arrUrl = url.split("//");

　　　　var start = arrUrl[1].indexOf("/");
　　　　var relUrl = arrUrl[1].substring(start);

　　　　if(relUrl.indexOf("?") != -1){
　　　　　　relUrl = relUrl.split("?")[0];
　　　　}
　　　　return relUrl;
　　 }

    $('body').on('click', function(){
        $('.look-pdf').hide().children().children().empty();
    });

    $('.opt-change').set_input_change_event(load_data);
});
