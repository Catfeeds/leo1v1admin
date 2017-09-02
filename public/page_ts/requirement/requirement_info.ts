/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info.d.ts" />

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
    
    $(".opt-detail").on("click",function(){
        var opt_data = $(this).get_opt_data();
        console.log(opt_data);
        var now_status = opt_data.status;
        if(now_status == 1){
            alert(1);
        }else if(now_status == 2){
            alert(2);
        }else if(now_status == 3){
            alert(3);
        }else if(now_status == 4){
            alert(4);
        }else if(now_status == 5){
            alert(5);
        }

    });


    $("#id_add_requirement_info").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var name              = $("<select />");  //产品名称
        var priority          = $("<select />");  //优先级
        var significance      = $("<select />");  //目前影响
        var expect_time       = $("<input />");  //期望时间
        var statement         = $("<textarea  placeholder='【需求故事】作为……  我希望…… 以便…… 【验收标准】1、……2、……3、……' />"); //需求说明
        var notes             = $("<textarea  />"); //备注
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">预览 </a>   </div>"); //内容截图
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        $upload_link.attr('href',opt_data.file_url);

        expect_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });
        Enum_map.append_option_list("require_class",name, true);
        Enum_map.append_option_list("require_priority", priority, true);
        Enum_map.append_option_list("require_significance",significance,true);

        var arr = [
            ["产品名称", name],
            ["优先级", priority],
            ["目前影响", significance],
            ["期望时间", expect_time],
            ["需求说明", statement],
            ["需求附件", $upload_div],
            ["备注",    notes],
        ];
        $.show_key_value_table("添加开发需求", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/add_requirement_info",{
                    "name"           : name.val(),
                    'priority'       : priority.val(),
                    'significance'   : significance.val(),
                    'expect_time'    : expect_time.val(),
                    'statement'      : statement.val(),
                    'content_pic'    : $upload_link.attr('href'),
                    'notes'          : notes.val(),
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

    $(".opt-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var name              = $("<select />");  //产品名称
        var priority          = $("<select />");  //优先级
        var significance      = $("<select />");  //目前影响
        var expect_time       = $("<input />");  //期望时间
        var statement         = $("<textarea />"); //需求说明
        var notes             = $("<textarea />"); //备注
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">预览 </a>   </div>"); //内容截图
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        $upload_link.attr('href',opt_data.content_pic);

        expect_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });
        Enum_map.append_option_list("require_class",name, true);
        Enum_map.append_option_list("require_priority", priority, true);
        Enum_map.append_option_list("require_significance",significance,true);
        name.val(opt_data.name);
        priority.val(opt_data.priority);
        significance.val(opt_data.significance);
        expect_time.val(opt_data.expect_time);
        statement.val(opt_data.statement);
        notes.val(opt_data.notes);

        var arr = [
            ["产品名称", name],
            ["优先级", priority],
            ["目前影响", significance],
            ["期望时间", expect_time],
            ["需求说明", statement],
            ["需求附件", $upload_div],
            ["备注",    notes],
        ];
        $.show_key_value_table("重新提交开发需求", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/re_edit_requirement_info",{
                    "id"             : opt_data.id,
                    "name"           : name.val(),
                    'priority'       : priority.val(),
                    'significance'   : significance.val(),
                    'expect_time'    : expect_time.val(),
                    'statement'      : statement.val(),
                    'content_pic'    : $upload_link.attr('href'),
                    'notes'          : notes.val(),
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
        var name              = $("<select />");  //产品名称
        var priority          = $("<select />");  //优先级
        var significance      = $("<select />");  //目前影响
        var expect_time       = $("<input />");  //期望时间
        var statement         = $("<textarea />"); //需求说明
        var notes             = $("<textarea />"); //备注
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">预览 </a>   </div>"); //内容截图
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;

        $upload_link.attr('href',opt_data.content_pic);

        expect_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });
        Enum_map.append_option_list("require_class",name, true);
        Enum_map.append_option_list("require_priority", priority, true);
        Enum_map.append_option_list("require_significance",significance,true);
        name.val(opt_data.name);
        priority.val(opt_data.priority);
        significance.val(opt_data.significance);
        expect_time.val(opt_data.expect_time);
        statement.val(opt_data.statement);
        notes.val(opt_data.notes);

        var arr = [
            ["产品名称", name],
            ["优先级", priority],
            ["目前影响", significance],
            ["期望时间", expect_time],
            ["需求说明", statement],
            ["需求附件", $upload_div],
            ["备注",    notes],
        ];
        $.show_key_value_table("重新提交开发需求", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/re_edit_requirement_info",{
                    "id"             : opt_data.id,
                    "name"           : name.val(),
                    'priority'       : priority.val(),
                    'significance'   : significance.val(),
                    'expect_time'    : expect_time.val(),
                    'statement'      : statement.val(),
                    'content_pic'    : $upload_link.attr('href'),
                    'notes'          : notes.val(),
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
    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要删除需求编号是["+opt_data.id+"]的需求吗?",function(val){
            if(val){
                $.do_ajax("/requirement/requirement_del",{
                    "id" : opt_data.id
                });
            }
        });
    });
    $(".content_show").each(function(){
        var content = $(this).data("content");
        var len = content.length;
        if(len >=10){
            var con = content.substr(0,9)+"...";
        }else{
             con = content;
        }
        $(this).html(con);

        $(this).mouseover(function(){

          $(this).html(content);

        });
        $(this).mouseout(function(){
            $(this).html(con);
        });

    });
	$('.opt-change').set_input_change_event(load_data);
});
