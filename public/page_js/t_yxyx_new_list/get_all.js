
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_new_list-get_all.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page({
        });
    }

    var do_add_or_update = function( opt_type, item ,id){
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
            html_node.find(".add_header_img").html(pic_img);
            html_node.find(".add_pic").html(pic_url);
            html_node.find(".add_title").val(item.title);
            html_node.find(".add_des").val(item.des);
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
                                         html_node.find(".add_pic").html(pic_url);
                                     });
            },
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var title    = html_node.find(".add_title").val();
                        var des      = html_node.find(".add_des").val();
                        var pic      = html_node.find(".add_pic").text();
                        if (opt_type=="update") {
                            $.ajax({
                                type     : "post",
                                url      : "/t_yxyx_wxnews_info/update_new_info",
                                dataType : "json",
                                data : {
                                    "id"        : id
                                    ,"title"     : title
                                    ,"des"      : des
                                    ,"pic"      : pic
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
                                url      : "/t_yxyx_wxnews_info/add_new_info",
                                dataType : "json",
                                data : {
                                    "title"     : title
                                    ,"des"      : des
                                    ,"pic"      : pic
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

/* HTML ...
*/
