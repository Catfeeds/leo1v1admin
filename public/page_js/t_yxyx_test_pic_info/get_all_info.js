/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_test_pic_info-get_all_info.d.ts" />

$(function(){
    Enum_map.append_option_list("grade", $(".grade"));
    Enum_map.append_option_list("subject", $(".subject"));
    Enum_map.append_option_list("test_type", $(".test_type"));
    Enum_map.append_option_list("grade", $(".add_grade"), true);
    Enum_map.append_option_list("subject", $(".add_subject"), true);
    Enum_map.append_option_list("test_type", $(".add_test_type"), true);

    $(".grade").val(g_args.grade);
    $(".subject").val(g_args.subject);
    $(".test_type").val(g_args.test_type);
    function load_data(){
        $.reload_self_page({
            grade     : $(".grade").val(),
            subject   : $(".subject").val(),
            tset_type : $(".test_type").val(),
        });
    }
    //筛选
    $(".grade").on("change",function(){
        load_data();
    });
    $(".subject").on("change",function(){
        load_data();
    });

    $(".test_type").on("change",function(){
        load_data();
    });


    var pic_num = 0;
    var do_add_or_update = function( opt_type, item ,id){
        pic_num = 0;
        var html_txt = $.dlg_get_html_by_class('dlg_add_new');
        html_txt=html_txt.
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" ).
            replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" )
        ;
        if (opt_type == 'update') {
            html_txt=html_txt.
                replace(/\"add_header_img\"/, "\"update_header_img\"" ).
                replace(/\"add_pic\"/, "\"update_pic\"")
            ;
        }
        var html_node = $("<div></div>").html(html_txt);
        var pic_url = "";
        var pic_img = "";
        if (opt_type=="update") {
            html_node.find(".add_test_title").val(item.test_title);
            html_node.find(".add_test_des").val(item.test_des);
            html_node.find(".add_grade").val(item.grade);
            html_node.find(".add_subject").val(item.subject);
            html_node.find(".add_test_type").val(item.test_type);
            var pic_str = '';

            for (var i = 0; i < item.pic_arr.length; i++) {
                if (item.pic_arr[i] && item.pic_arr[i] != item.poster) {
                    pic_str += '<span onclick="set_poster(this)" class="btn" data_ip="'+item.pic_arr[i]
                        +'">设为封面</span><div class="add_header_img'+i+'"><img src="'
                        +item.pic_arr[i]+'" width="80px"></div><div class="add_pic'+i
                        +' order'+i+'" style="display:none">'+item.pic_arr[i]+'</div>';
                    pic_num++;
                } else if (item.pic_arr[i] && item.pic_arr[i] == item.poster) {
                    pic_str += '<span onclick="set_poster(this)" class="mark btn" data_ip="'+item.pic_arr[i]
                        +'">封面</span><div class="add_header_img"><img src="'+item.pic_arr[i]
                        +'" width="80px"></div><div class="add_pic order'+i+'" style="display:none">'
                        +item.poster+'</div>';
                    pic_num++;
                }
            }
            $('#id_container_add_tmp').append(pic_str);
            html_node.find("#id_container_add_tmp").after(pic_str);
        }
        var fun_str = "<span class='real_poster' style='display:none'></span><script> function set_poster(obj) { if($(obj).text()!= '封面'){ $('.real_poster').text($(obj).attr('data_ip')); $(obj).text('封面');$('.mark').text('设为封面'); $('.mark').removeClass('mark'); $(obj).addClass('mark');}} </script>";
        html_node.find("#id_container_add_tmp").after(fun_str);


        var title = "";
        if (opt_type=="update"){
            title="修改信息";
        }else{
            title="添加信息";
        }
        BootstrapDialog.show({
            title           : title,
            message         : html_node,
            closable        : true,
            closeByBackdrop : false,
            onshown         : function(dialog){
                if(pic_num < 10) {
                    custom_qiniu_upload ("id_upload_add_tmp","id_container_add_tmp",
                                         g_args.qiniu_upload_domain_url , true,
                                         function (up, info, file){
                                             var res = $.parseJSON(info);
                                             pic_url = g_args.qiniu_upload_domain_url + res.key;
                                             pic_img = "<img width=80 src=\""+pic_url+"\" />";
                                             if(opt_type != 'update') {
                                                 html_node.find(".add_header_img").html(pic_img);
                                                 html_node.find(".add_pic").html(pic_url);
                                             } else {
                                                 html_node.find(".update_header_img").html(pic_img);
                                                 html_node.find(".update_pic").html(pic_url);
                                                 pic_num++;
                                             }
                                             add_next_pic(html_node);
                                         });
                }
            },
            buttons        : [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var pic        = html_node.find(".add_pic").text();
                        var grade      = html_node.find(".add_grade").val();
                        var poster     = html_node.find(".add_pic").text();
                        var subject    = html_node.find(".add_subject").val();
                        var test_des   = html_node.find(".add_test_des").val();
                        var test_type  = html_node.find(".add_test_type").val();
                        var test_title = html_node.find(".add_test_title").val();
                        if (pic_num >1) {
                            for (var i = 0; i <= pic_num; i++) {
                                if (html_node.find('.add_pic'+i).text()) {
                                    pic =  pic+'|'+ html_node.find('.add_pic'+i).text();
                                }
                            }
                        }
                        if (opt_type == "update") {
                            pic = '';
                            for (var i = 0; i < 10; i++) {
                                if (html_node.find('.order'+i).text()) {
                                    pic =  pic+'|'+ html_node.find('.order'+i).text();
                                }
                            }
                        }
                        if ( opt_type == "update" && html_node.find(".update_pic").text() ){
                            pic = pic +'|'+ html_node.find(".update_pic").text();
                        }

                        if(html_node.find('.real_poster').text()) {
                            poster = html_node.find('.real_poster').text();
                        }
                        if (opt_type=="update") {
                            $.ajax({
                                type     : "post",
                                url      : "/t_yxyx_test_pic_info/update_test_info",
                                dataType : "json",
                                data : {
                                    "id"          : id
                                    ,"pic"        : pic
                                    ,"grade"      : grade
                                    ,"poster"     : poster
                                    ,"subject"    : subject
                                    ,"test_des"   : test_des
                                    ,"test_type"  : test_type
                                    ,"test_title" : test_title
                                },
                                success : function(result){
                                    if(result.ret==0){
                                        window.location.reload();
                                    }else{
                                        dialog.close();
                                    }
                                }
                            });

                        } else {
                            $.ajax({
                                type     : "post",
                                url      : "/t_yxyx_test_pic_info/add_test_info",
                                dataType : "json",
                                data : {
                                    "pic"        : pic
                                    ,"grade"      : grade
                                    ,"poster"     : poster
                                    ,"subject"    : subject
                                    ,"test_des"   : test_des
                                    ,"test_type"  : test_type
                                    ,"test_title" : test_title
                                },
                                success : function(result){
                                    if(result.ret==0){
                                        window.location.reload();
                                    }else{
                                        dialog.close();
                                    }
                                }
                            });
                        }
                    }
                },{
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });

    };

    $(".add_new").on("click",function(){
        do_add_or_update("add");
    });

    $(".opt-update-new_info").on("click",function(){
        var id=$(this).get_opt_data( "id" );
        $.ajax({
            type  :"post",
            url      :"/t_yxyx_test_pic_info/get_one_test",
            dataType :"json",
            data     :{"id":id},
            success: function(data){
                do_add_or_update("update", data.ret_info, id);
            }
        });
    });


    $(".opt-del").on("click",function(){
        var id=$(this).get_opt_data( "id" );
        BootstrapDialog.show({
            title: '删除信息',
            message : "确定删除?",
            closable: true,
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
                    $.ajax({
                        type     :"post",
                        url      :"/t_yxyx_test_pic_info/del_test_info",
                        dataType :"json",
                        data     :{"id":id},
                        success  : function(result){
                            window.location.reload();
                        }
                    });
                    dialog.close();
                }
            }, {
                label: '取消',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    });
    //多次添加图片
    function add_next_pic(html_node) {
        if (opt_type != "update") {
            pic_num++;
        }
        $('#id_container_add_tmp').empty();
        var new_input = '<input id="id_upload_add_tmp" value="已'+pic_num+'张图片" class="btn btn-primary add_pic_img" style="margin-bottom:5px;" type="button"/>';
        $('#id_container_add_tmp').append(new_input);
        if (pic_num < 10) {
            custom_qiniu_upload ("id_upload_add_tmp","id_container_add_tmp",
                                 g_args.qiniu_upload_domain_url , true,
                                 function (up, info, file){
                                     var res = $.parseJSON(info);
                                     pic_url = g_args.qiniu_upload_domain_url + res.key;
                                     pic_img = "<img width=80 src=\""+pic_url+"\" />";
                                     var new_header_img = '<div class="add_header_img'+pic_num+'">'+pic_img+'</div>';
                                     var new_pic = '<div class="add_pic'+pic_num+'" style="display:none">'+pic_url+'</div>';
                                     $("#id_container_add_tmp").parent().append(new_header_img);
                                     $("#id_container_add_tmp").parent().append(new_pic);
                                     $(".add_header_img"+pic_num).html(pic_img);
                                     $(".add_pic"+pic_num).html(pic_url);
                                     html_node = html_node+new_header_img+new_pic;
                                     add_next_pic();
                                 });
        }
    }

});
