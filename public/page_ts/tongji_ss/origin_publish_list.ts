/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-origin_publish_list.d.ts" />

function load_data(){
    $.reload_self_page ( {
			  origin_level:	$('#id_origin_level').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val()
    });
}

$(function(){


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


	  $('#id_origin_level').val(g_args.origin_level);
	  $.enum_multi_select( $('#id_origin_level'), 'origin_level', function(){load_data();} )

    $('.opt-change').set_input_change_event(load_data);
    $(".common-table").table_group_level_4_init();
    $(".common-table").table_group_level_();



        var link_css=        {
            color: "#3c8dbc",
            cursor:"pointer"
        };
    $(".opt-link").css(link_css);

    $(".opt-link").on("click",function(){
        var opt_type=$(this).data("opt_type");
        var $tr=$(this).parent();
        var opt_div=$tr.find("td:last> div");
        var key1=  opt_div.data("key1") ;
        var key2=  opt_div.data("key2") ;
        var key3=  opt_div.data("key3") ;
        var key4= opt_div.data("old_key4") ;

        $(this).admin_select_dlg_ajax({
            "opt_type" : "list", // or "list"
            "url"      : "/ss_deal2/get_origin_phone_list_js",
            //其他参数
            "args_ex" : {
                "opt_type_str" : opt_type,
                "start_time"   : g_args.start_time,
                "end_time"     : g_args.end_time,
                "origin_level" : g_args.origin_level ,
                "key1"         : key1,
                "key2"         : key2,
                "key3"         : key3,
                "key4"         : key4,
            },
            //字段列表
            'field_list' : [
                {
                    title:"报名时间",
                    render:function(val,item) {return item.add_time;}
                },{
                    title:"报名渠道",
                    render:function(val,item) {return item.origin;}
                },{
                    title:"手机号",
                    render:function(val,item) {return item.phone;}
                },{
                    title:"操作",
                    render:function(val,item) {
                        if (!g_args.origin_ex || g_args.origin_ex=="BD" ) {
                            var $div = $("<button class=\"btn btn-danger\"> 删除 </button>") ;
                            $div.on("click",function(){
                                BootstrapDialog.confirm(
                                    "要删除" + item.phone,function(val) {
                                        if (val) {
                                            $.do_ajax( "/ss_deal2/seller_student_del", {
                                                "userid" : item.userid
                                            });
                                        }
                                    });

                            });
                            return $div ;
                        }
                    }
                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });

    });

});
