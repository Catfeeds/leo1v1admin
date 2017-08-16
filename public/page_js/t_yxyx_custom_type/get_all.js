/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_custom_type-get_all.d.ts" />
$(function(){
    var do_add_or_update = function( opt_type, item ){
        var html_txt = $.dlg_get_html_by_class('dlg_add_type');
        var html_node = $("<div></div>").html(html_txt);
        if (opt_type=="update") {
            html_node.find(".add_type").val(item.type_name);
        }

        var title = "";
        if (opt_type=="update"){
            title="修改标签";
        }else{
            title="添加标签";
        }
        BootstrapDialog.show({
            title           : title,
            message         : html_node,
            closable        : true,
            closeByBackdrop : false,
            onshown         : function(dialog){

            },
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        if (opt_type=="update") {
                            var custom_type_id =  item.custom_type_id;
                        }
                        var type_name = html_node.find(".add_type").val();
                        $.ajax({
                            type     : "post",
                            url      : "/t_yxyx_custom_type/add_type",
                            dataType : "json",
                            data : {
                                "custom_type_id" : custom_type_id
                                ,"type_name"     : type_name
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

    $(".add_new_type").on("click",function(){
        do_add_or_update("add");
    });

    $(".opt-update").on("click",function(){
        var custom_type_id = $(this).get_opt_data( "id" );
        $.ajax({
            type     :"post",
            url      :"/t_yxyx_custom_type/get_one_type",
            dataType :"json",
            data     :{"custom_type_id":custom_type_id},
            success: function(data){
                do_add_or_update("update", data.ret_info);
            }
        });
    });


    $(".opt-del").on("click",function(){
        var custom_type_id = $(this).get_opt_data( "id" );
        BootstrapDialog.show({
            title: '删除标签',
            message : "确定删除?",
            closable: true,
            buttons: [{
                label: '确认',
                cssClass: 'btn-primary',
                action: function(dialog){
                    $.ajax({
                        type     :"post",
                        url      :"/t_yxyx_custom_type/del_type",
                        dataType :"json",
                        data     :{"custom_type_id":custom_type_id},
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
