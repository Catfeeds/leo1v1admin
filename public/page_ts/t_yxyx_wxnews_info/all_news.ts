/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_wxnews_info-all_news.d.ts" />

$(function(){
    // Enum_map.append_option_list("pic_type", $(".pic_type"));
    // Enum_map.append_option_list("subject", $(".add_pic_subject"),true);
    // Enum_map.append_option_list("wxnew_type", $(".type"));
    $(".type").val(g_args.type);
    // var set_select_option_list=function(){
    //     Enum_map.append_child_option_list("wxnew_type", $(".type"));
    //     Enum_map.append_child_option_list("wxnew_type", $(".add_type"),true);
    //     $("body").on("change",".add_type",function(){
    //         Enum_map.append_child_option_list("wxnew_type", $(this),$(".add_type"),true);
    //     });
    // };
    // set_select_option_list();
    function load_data(val){
        $.reload_self_page({
            type : $(".type").val(),
        });
    }
    //筛选
    $(".type").on("change",function(){
        load_data(-1);
    });

    var do_add_or_update = function( opt_type, item ){
        var html_txt = $.dlg_get_html_by_class('dlg_add_new_info');
        html_txt=html_txt.
            replace(/\"id_upload_add\"/, "\"id_upload_add_tmp\"" ).
            replace(/\"id_container_add\"/, "\"id_container_add_tmp\"" )
        ;
        var html_node = $("<div></div>").html(html_txt);
        var pic_url = "";
        var pic_img = "";

        if (opt_type=="update") {
            pic_url=item.pic;
            pic_img="<img width=100 src=\""+pic_url+"\" />";
            html_node.find(".pic").html(pic_img);
            html_node.find(".add_type").val(item.type);
            // Enum_map.append_child_option_list("wxnew_type", html_node.find(".add_type"), true);
            html_node.find(".add_title").val(item.title);
            html_node.find(".add_des").val(item.des);
            html_node.find(".add_new_link").val(item.new_link);
        }

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
                custom_qiniu_upload ("id_upload_add_tmp","id_container_add_tmp",
                                     g_args.qiniu_upload_domain_url , true,
                                     function (up, info, file){
                                         var res = $.parseJSON(info);
                                         pic_url = g_args.qiniu_upload_domain_url + res.key;
                                         pic_img = "<img width=80 src=\""+pic_url+"\" />";
                                         html_node.find(".add_header_img").html(pic_img);
                                         html_node.find(".pic").html(pic_url);
                                     });
            },
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var title    = html_node.find(".add_title").val();
                        var des      = html_node.find(".add_des").val();
                        var pic      = html_node.find(".pic").val();
                        var new_link = html_node.find(".add_new_link").val();
                        var add_type = html_node.find(".add_type").val();
                        $.ajax({
                            type     : "post",
                            url      : "/t_yxyx_wxnews_info/add_new_info",
                            dataType : "json",
                            data : {
                                "title"     : title
                                ,"des"      : des
                                ,"pic"      : pic
                                ,"new_link" : new_link
                                ,"type"     : add_type
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
                },{
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });

    };

    $(".add_new_info").on("click",function(){
        do_add_or_update("add");
    });

    $(".opt-update-new_info").on("click",function(){
        var id=$(this).get_opt_data( "id" );
        $.ajax({
            type  :"post",
            url      :"/t_yxyx_wxnews_info/get_one_new",
            dataType :"json",
            data     :{"id":id},
            success: function(data){
                do_add_or_update("update", data.ret_info);
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
                        url      :"/t_yxyx_wxnews_info/del_new_info",
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


});
