/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_month_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        date_type_config:	$('#id_date_type_config').val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        adminid: $('#id_seller_adminid_new').val(),
    });
}
$(function(){
    $('#id_seller_adminid_new').val(g_args.adminid);
    $.admin_select_user(
        $('#id_seller_adminid_new'),
        "admin", load_data ,false, {
            "main_type": 2,
            select_btn_config: [
                {
                    "label": "[是]",
                    "value": -2
                }, {
                    "label": "[不是]",
                    "value": 0
                }]
        }
    );
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
    $("#id_add").on("click",function(){
        var seller_adminid = $("<input type=text name=adminid id='seller_adminid' >");
        var seller_level= $("<select  />" );
        Enum_map.append_option_list("seller_level",seller_level);
        var month_date = $('<input type=text name=define_date >');
        var arr=[
            ["销售" ,seller_adminid],
            ['定级月份',month_date],
            ["定级等级" ,seller_level],
        ];
        seller_adminid.click(function(){
            $.admin_select_user(
                $('#seller_adminid'),
                "admin", function(){},false, {
                    "main_type": 2,
                    select_btn_config: [
                        {
                            "label": "[是]",
                            "value": -2
                        }, {
                            "label": "[不是]",
                            "value": 0
                        }]
                }
            );
        })
        month_date.datetimepicker( {
            lang:'ch',
            timepicker:false,
            format: "Y-m-d",
            onChangeDateTime :function(){
            }
        });
        $.show_key_value_table("新增数据", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if(seller_adminid.val()<=0){
                    alert('请选择销售!');
                    return;
                }
                if(month_date.val()<=0){
                    alert('请选择定级时间!');
                    return;
                }
                if(seller_level.val()<=0){
                    alert('请选择定级级别!');
                    return;
                }
                $.do_ajax("/seller_level_goal/add_seller_level_month",{
                    "adminid"      : seller_adminid.val(),
                    "month_date"   : month_date.val(),
                    "seller_level" : seller_level.val(),
                });
            }
        })
    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var adminid = $('<input type=text name=adminid disabled>');
        var month_date = $('<input type=text name=month_time disabled>');
        var seller_level= $("<select />" );
        Enum_map.append_option_list("seller_level",seller_level);
        adminid.val(opt_data.account);
        month_date.val(opt_data.month_date);
        seller_level.val(opt_data.seller_level);

        var arr=[
            ['销售',adminid],
            ['定级时间',month_date],
            ["定级级别" ,seller_level],
        ] ;
        month_date.datetimepicker( {
            lang:'ch',
            timepicker:false,
            format: "Y-m-d",
            onChangeDateTime :function(){
            }
        });
        $.show_key_value_table("修改数据", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/seller_level_goal/edit_seller_level_month",{
                    "id"  : opt_data.id,
                    "seller_level" : seller_level.val(),
                });
            }
        })
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除该数据吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/seller_level_goal/del_seller_level_month", {
                        "id" : opt_data.id,
                    });
                }
            })
    });

    $('.opt-change').set_input_change_event(load_data);
});
