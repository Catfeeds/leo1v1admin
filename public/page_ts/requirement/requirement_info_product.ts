/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info_product.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:   $('#id_date_type_config').val(),
            date_type:  $('#id_date_type').val(),
            opt_date_type:  $('#id_opt_date_type').val(),
            start_time: $('#id_start_time').val(),
            end_time:   $('#id_end_time').val(),
            name             : $('#id_name').val(),
            priority         : $('#id_priority').val(),
            significance     : $('#id_significance').val(),
            status           : $('#id_status').val(),
            product_status   : $('#id_product_status').val(),
            development_status: $('#id_development_status').val(),
            test_status      : $('#id_test_status').val(),
        });
    }

    Enum_map.append_option_list("require_class",$("#id_name"));
    Enum_map.append_option_list("require_priority",$("#id_priority"));
    Enum_map.append_option_list("require_significance",$("#id_significance"));
    Enum_map.append_option_list("require_status",$("#id_status"),false,[2,3,4,5]);
    Enum_map.append_option_list("require_product_status",$("#id_product_status"),false,[0,1,2,3,4]);
    Enum_map.append_option_list("require_development_status",$("#id_development_status"),false,[0,1,2,3,4]);
    Enum_map.append_option_list("require_test_status",$("#id_test_status"),false,[0,1,2,3,4]);

    $("#id_name").val(g_args.name);
    $("#id_priority").val(g_args.priority);
    $("#id_significance").val(g_args.significance);
    $("#id_status").val(g_args.status);
    $("#id_product_status").val(g_args.product_status);
    $("#id_development_status").val(g_args.development_status);
    $("#id_test_status").val(g_args.test_status);



    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $(".opt-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">预览 </a>   </div>"); //内容截图
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        $upload_link.attr('href',opt_data.product_solution);

        var arr = [
            ["解决方案", $upload_div],
        ];
        $.show_key_value_table("提交解决方案", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/product_re_edit",{
                    'id'                  : opt_data.id,
                    'product_solution'    : $upload_link.attr('href'),
                });
            }
        },function(){
            $.custom_upload_file(
                "id_upload_from_url" ,
                true,function( up, info, file ){
                    var res = $.parseJSON(info);
                    var url=res.key;
                    $.do_ajax("/common_new/get_qiniu_download",{
                        "file_url" :res.key ,
                        "public_flag" :1,
                    }, function(resp){
                        $upload_link.attr("href", resp.url);
                    })
                },null,
                ["png","jpg","zip","rar","gz","pdf","doc","xls","xlsx"] );
        });
    });
    $(".opt-re-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">预览 </a>   </div>"); //内容截图
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        $upload_link.attr('href',opt_data.product_solution);

        var arr = [
            ["解决方案", $upload_div],
        ];
        $.show_key_value_table("提交解决方案", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/product_re_edit",{
                    'id'                  : opt_data.id,
                    'product_solution'    : $upload_link.attr('href'),
                });
            }
        },function(){
            $.custom_upload_file(
                "id_upload_from_url" ,
                true,function( up, info, file ){
                    var res = $.parseJSON(info);
                    var url=res.key;
                    $.do_ajax("/common_new/get_qiniu_download",{
                        "file_url" :res.key ,
                        "public_flag" :1,
                    }, function(resp){
                        $upload_link.attr("href", resp.url);
                    })
                },null,
                ["png","jpg","zip","rar","gz","pdf","doc","xls","xlsx"] );
        });
    });
    $(".opt-deal").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要处理提交人是["+opt_data.create_admin_nick+"]的需求吗?",function(val){
            if(val){
                $.do_ajax("/requirement/product_deal",{
                    "id" : opt_data.id
                });
            }
        });
    });
    $(".opt-reject").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var product_reject         = $("<textarea />"); //驳回原因
        var arr = [
            ["驳回原因",    product_reject],
        ];
        $.show_key_value_table("驳回需求请求", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/product_reject",{
                    "id"           : opt_data.id,
                    'product_reject'       : product_reject.val(),
                });
            }
        },function(){
        });
    });

    $(".opt-delete").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要删除提交人是["+opt_data.create_admin_nick+"]的需求吗?",function(val){
            if(val){
                $.do_ajax("/requirement/product_delete",{
                    "id" : opt_data.id
                });
            }
        });
    });
    $(".opt-do").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("开始处理提交人是["+opt_data.create_admin_nick+"]的需求吗?",function(val){
            if(val){
                $.do_ajax("/requirement/product_do",{
                    "id" : opt_data.id
                });
            }
        });
    });
    $(".opt-add").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">预览 </a>   </div>"); //内容截图
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        $upload_link.attr('href',opt_data.file_url);

        
        var arr = [
            ["解决方案", $upload_div],
        ];
        $.show_key_value_table("提交解决方案", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/product_add",{
                    'id'                  : opt_data.id,
                    'product_solution'    : $upload_link.attr('href'),
                });
            }
        },function(){
        	$.custom_upload_file(
                "id_upload_from_url" ,
                true,function( up, info, file ){
                    var res = $.parseJSON(info);
                    var url=res.key;
                    $.do_ajax("/common_new/get_qiniu_download",{
                        "file_url" :res.key ,
                        "public_flag" :1,
                    }, function(resp){
                        $upload_link.attr("href", resp.url);
                    })
                },null,
                ["png","jpg","zip","rar","gz","pdf","doc","xls","xlsx"] );
        });
    });




	$('.opt-change').set_input_change_event(load_data);
});
