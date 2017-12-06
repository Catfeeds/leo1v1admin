/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info_new.d.ts" />

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
    Enum_map.append_option_list("require_product_status",$("#id_product_status"),false,[1,2,3,4]);
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

    $("#id_add_requirement_info").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var name              = $("<textarea />");  //需求名称
        var priority          = $("<select id='id_priority'><option value=\"3\">高</option> <option value=\"2\">中</option><option value=\"1\">低</option>/>");  //优先级
        var expect_time       = $("<input />");  //期望时间
        var statement         = $("<textarea />"); //需求描述
        var notes             = $("<textarea />"); //需求来源
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\""+opt_data.content_pic+"\" target=\"_blank\" id=\"id_pre_look\"> </a>   </div>");
        //内容截图:
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;
        var product_operator = $("<select id='id_productid'> <option value=\"448\">夏宏东</option> <option value=\"919\">邓晓玲</option>  <option value=\"1118\">孙瞿</option> <option value=\"1167\">杨磊</option><option value=\"974\">付玉文</option> <option value=\"871\">邓春燕</option>/>");//产品经理

        $upload_link.attr('href',"");

        expect_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });


        var arr = [
            ["<font color='red'>*</font>需求名称", name],
            ["<font color='red'>*</font>优先级", priority],
            ["<font color='red'>*</font>期望完成时间", expect_time],
            ["<font color='red'>*</font>需求描述", statement],
            ["需求来源",    notes],
            ["附件", $upload_div],
            ["<font color='red'>*</font>产品经理",product_operator]
        ];
        $.show_key_value_table("添加需求信息", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                if(name.val() == ''){
                    alert("请输入需求名称");
                    return;
                }
                if(expect_time.val() == ''){
                    alert("请选择期望日期");
                    return;
                }
                var expect_date = new Date(expect_time.val());
                var today_date       = new Date();
                var today = today_date.getFullYear()+'-';
                var today = today + (today_date.getMonth()+1);
                var today = today+'-'+today_date.getDate(); 
                var today = new Date(today);
                if(expect_date < today){
                    alert("期望时间不能在当前时间范围之前");
                    return;
                }
                if(statement.val() == ''){
                    alert("请输入需求描述");
                    return;
                }
                
                $.do_ajax("/requirement/add_requirement_info_new",{
                    "name"           : name.val(),
                    'priority'       : priority.val(),
                    'expect_time'    : expect_time.val(),
                    'statement'      : statement.val(),
                    'content_pic'    : $upload_link.attr('href'),
                    'notes'          : notes.val(),
                    'product_operator':product_operator.val(),
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
              ["png","jpg","zip","rar","gz","pdf","doc","docx","xls","xlsx","xps","wps","tif","xlsm","csv","ppt","pptx","txt","vsdxx","vsd","xmind"] );
        });
    });

    $(".opt-re-edit").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var name              = $("<textarea />");  //需求名称
        var priority          = $("<select id='id_priority'><option value=\"3\">高</option> <option value=\"2\">中</option><option value=\"1\">低</option>/>");  //优先级
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

        expect_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });

        name.val(opt_data.product_name);
        priority.val(opt_data.priority);
        expect_time.val(opt_data.expect_time_b);
        statement.val(opt_data.statement);
        notes.val(opt_data.notes);

        var arr = [
            ["<font color='red'>*</font>需求名称", name],
            ["<font color='red'>*</font>优先级", priority],
            ["<font color='red'>*</font>期望完成时间", expect_time],
            ["<font color='red'>*</font>需求描述", statement],
            ["需求来源",    notes],
            ["附件", $upload_div],
            ["<font color='red'>*</font>产品经理",product_operator]
        ];
        $.show_key_value_table("重新提交开发需求", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                if(expect_time.val() == ''){
                    alert("请选择期望日期");
                    return;
                }
                if(name.val() == ''){
                    alert("请输入需求名称");
                    return;
                }
                if(statement.val() == ''){
                    alert("请输入需求描述");
                    return;
                }
                var expect_date = new Date(expect_time.val());
                var today_date       = new Date();
                var today = today_date.getFullYear()+'-';
                var today = today + (today_date.getMonth()+1);
                var today = today+'-'+today_date.getDate(); 
                var today = new Date(today);
                if(expect_date < today){
                    alert("期望时间不能在当前时间范围之前");
                    return;
                }

                $.do_ajax("/requirement/re_edit_requirement_info_new",{
                    "id"             : opt_data.id,
                    "name"           : name.val(),
                    'priority'       : priority.val(),
                    'expect_time'    : expect_time.val(),
                    'statement'      : statement.val(),
                    'content_pic'    : $upload_link.attr('href'),
                    'notes'          : notes.val(),
                    'product_operator':product_operator.val(),
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
