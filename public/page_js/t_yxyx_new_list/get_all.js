/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_new_list-get_all.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page({
        });
    }
    var do_add_or_update = function( opt_type, item){
        var pic_url = "";
        var pic_img = "";

        if (opt_type == "update") {
            pic_url = item.new_pic;
            pic_img = "<img width=100 src=\""+pic_url+"\" />";
            $(".add_header_img").html(pic_img);
            $(".add_pic").html(pic_url);
            $(".add_title").val(item.new_title);
            $(".add_content").val(item.new_content);
        } else {
            $(".add_header_img").html("");
            $(".add_pic").html("");
            $(".add_title").val("");
            $(".add_content").val("");
        }

        if (opt_type == "update"){
            $("#myModalLabel").text("修改信息");
        }else{
            $("#myModalLabel").text("添加信息");
        }
        $(".submit").on("click", function(){
            var new_title   = $(".add_title").val();
            var new_content = $(".add_content").val();
            var new_pic     = $(".add_pic").text();
            if (opt_type == "update") {
                $.ajax({
                    type     : "post",
                    url      : "/t_yxyx_new_list/update_new_info",
                    dataType : "json",
                    data : {
                        "id"          : item.id
                        ,"new_title"  : new_title
                        ,"new_content": new_content
                        ,"new_pic"    : new_pic
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
                    url      : "/t_yxyx_new_list/add_new_info",
                    dataType : "json",
                    data : {
                        "new_title"   : new_title
                        ,"new_content": new_content
                        ,"new_pic"    : new_pic
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
        });

    };

    $(".add_new_info").on("click",function(){
        do_add_or_update("add");
    });

    $(".opt-update").on("click",function(){
        var id = $(this).get_opt_data( "id" );
        $.ajax({
            type     :"post",
            url      :"/t_yxyx_new_list/get_one_new",
            dataType :"json",
            data     :{"id":id},
            success  : function(data){
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
                        url      :"/t_yxyx_new_list/del_new_info",
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
