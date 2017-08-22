/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/requirement-requirement_info.d.ts" />

$(function(){
	  function load_data(){
	      $.reload_self_page ( {
	    	});
	  }
	
   
    $("#id_add_requirement_info").on("click", function(){
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

        $upload_link.attr('href',opt_data.file_url);

        expect_time.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            "onChangeDateTime" : function() {
            }
        });
        Enum_map.append_option_list("require_class",name, true,);
        Enum_map.append_option_list("require_priority", priority, true);
        Enum_map.append_option_list("require_significance",significance,true);

        var arr = [
            ["产品名称", name],
            ["优先级", priority],
            ["目前影响", significance],
            ["期望时间", expect_time],
            ["需求说明", statement],
            ["内容截图", $upload_div],
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
                ["png","jpg","zip","rar","gz","pdf","doc"] );
        });
    });

	$('.opt-change').set_input_change_event(load_data);
});
