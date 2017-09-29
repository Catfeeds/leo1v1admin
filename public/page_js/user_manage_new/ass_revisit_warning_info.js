/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_revisit_warning_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type_config  :	$('#id_date_type_config').val(),
            date_type         :	$('#id_date_type').val(),
            opt_date_type     :	$('#id_opt_date_type').val(),
            start_time        :	$('#id_start_time').val(),
            end_time          :	$('#id_end_time').val(),
            is_warning_flag   :	$('#id_is_warning_flag').val(),
            seller_groupid_ex :	$('#id_seller_groupid_ex').val(),
            warning_type_flag :	$('#id_warning_type').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            init_warning();
            load_data();
        }
    });

    Enum_map.append_option_list("is_warning_flag", $("#id_is_warning_flag"));
    $('#id_is_warning_flag').val(g_args.is_warning_flag);
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex(g_adminid_right);

    $(".opt-warning-record").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_warning_deal_info  = $("<textarea />");
        var id_warning_deal_url = $("<div><input class=\"warning_deal_url\" id=\"warning_deal_url\" type=\"text\"readonly ><div ><span><a class=\"upload_gift_pic\" id=\"id_upload_warning_deal\" href=\"javascript:;\">上传</a></span><span><a style=\"margin-left:20px\" href=\"javascript:;\" id=\"id_del_warning_deal\">删除</a></span></div></div>");
        var id_is_warning_flag = $("<select><option value=\"1\">预警中</option><option value=\"2\">已解决</option></select>");
        id_warning_deal_info.val(opt_data.warning_deal_info);
        id_is_warning_flag.val(opt_data.is_warning_flag);
        var arr = [
            ["预警处理方案",  id_warning_deal_info ],
            ["相关图片上传",  id_warning_deal_url ],
            ["预警解决",  id_is_warning_flag ]
        ];

        id_warning_deal_url.find("#id_del_warning_deal").on("click",function(){
            id_warning_deal_url.find("#warning_deal_url").val("");
        });
        $.show_key_value_table("预警处置", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {

                $.do_ajax("/user_deal/set_revisit_warning_deal_info", {
                    userid : opt_data.userid,
                    revisit_time: opt_data.revisit_time,
                    warning_deal_url : id_warning_deal_url.find("#warning_deal_url").val(),
                    warning_deal_info: id_warning_deal_info.val(),
                    is_warning_flag:id_is_warning_flag.val()
                });
            }
        },function(){
            $.custom_upload_file('id_upload_warning_deal',true,function (up, info, file) {
                var res = $.parseJSON(info);

                $("#warning_deal_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif','rar','zip']);

        });




    });

    var init_noit_btn=function( id_name, title ,type) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
        btn.attr('data-warning', type);
    };

    init_noit_btn("warning-one", "预警1～5","warning-one" );
    init_noit_btn("warning-two", "预警5～7", "warning-two" );
    init_noit_btn("warning-three", "预警超时", "warning-three" );
    $(".opt-warning-type").on("click",function(){
        $('#id_warning_type').val( $(this).attr('data-warning') );
        load_data();
    });

    var init_warning = function() {
        $('#id_warning_type').val(-1);
    }
  $('.opt-change').set_input_change_event(init_warning);
  $('.opt-change').set_input_change_event(load_data);
});
