/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info_product_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config:   $('#id_date_type_config').val(),

            date_type               : $('#id_date_type').val(),
            opt_date_type           : $('#id_opt_date_type').val(),
            start_time              : $('#id_start_time').val(),
            end_time                : $('#id_end_time').val(),

            priority         : $('#id_priority').val(),
            product_status   : $('#id_product_status').val(),
            id_productid        : $('#id_productid').val(),
        });
    }

    Enum_map.append_option_list("require_priority",$("#id_priority"));
    Enum_map.append_option_list("require_product_status",$("#id_product_status"),false,[0,1,2,3,4]);


    $("#id_priority").val(g_args.priority);
    $("#id_productid").val(g_args.id_productid);
    $("#id_product_status").val(g_args.product_status);


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });




    $(".opt-re-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var name              = $("<input />");  //需求名称
        var priority          = $("<select />");  //优先级
        var expect_time       = $("<input />");  //期望时间
        var statement         = $("<textarea />"); //需求描述
        var notes             = $("<textarea />"); //需求来源
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\""+opt_data.content_pic+"\" target=\"_blank\" id=\"id_pre_look\"> </a>   </div>");
        //内容截图:
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;
        var product_operator = $("<select id='id_productid'> <option value=\"448\">夏宏东</option> <option value=\"919\">邓晓玲</option>  <option value=\"1118\">孙瞿</option> <option value=\"1167\">杨磊</option><option value=\"974\">付玉文</option> <option value=\"871\">邓春燕</option>/>");//产品经理
        $upload_link.attr('href',opt_data.content_pic);
        if(opt_data.content_pic != ''){
            $upload_link.html("查看");
        }

        var product_status    = $("<select />");//处理状态
        var forecast_time     = $("<input />"); //预估时间
        var product_comment   = $("<textarea />");//备注
        expect_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });
        forecast_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });


        Enum_map.append_option_list("require_priority", priority, true);
        Enum_map.append_option_list("require_product_status",product_status,true);
        name.val(opt_data.name);
        priority.val(opt_data.priority);
        expect_time.val(opt_data.expect_time);
        statement.val(opt_data.statement);
        notes.val(opt_data.notes);
        product_status.val(opt_data.product_status);
        forecast_time.val(opt_data.forecast_time);
        product_comment.val(opt_data.product_comment);
        product_operator.val(opt_data.product_operator);
        var arr = [
            ["需求名称", name],
            ["优先级", priority],
            ["期望时间", expect_time],
            ["需求描述", statement],
            ["需求来源",    notes],
            ["附件", $upload_div],
            ["产品经理",product_operator],
            ["处理状态",product_status],
            ["预估时间",forecast_time],
            ["备注",product_comment],
        ];
        $.show_key_value_table("需求详情", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                $.do_ajax("/requirement/re_edit_requirement_info_new_b2",{
                    "id"             : opt_data.id,
                    "name"           : name.val(),
                    'priority'       : priority.val(),
                    'expect_time'    : expect_time.val(),
                    'statement'      : statement.val(),
                    'content_pic'    : $upload_link.attr('href'),
                    'notes'          : notes.val(),
                    'product_operator':product_operator.val(),
                    'product_status' : product_status.val(),
                    'forecast_time'  : forecast_time.val(),
                    'product_comment': product_comment.val(),
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
                        $upload_link.html("查看");
                    })
                },null,
                ["png","jpg","zip","rar","gz","pdf","doc","xls","xlsx"] );
        });
    });
    $(".opt-deal").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要完成提交人是["+opt_data.create_admin_nick+"]的需求吗?",function(val){
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
