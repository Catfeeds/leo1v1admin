/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_salary_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        'seller_level':$('#id_seller_level').val(),
        'define_time':$('#id_define_time').val(),
    });
}
$(function(){
    Enum_map.append_option_list("seller_level",$("#id_seller_level"));
    $('#id_seller_level').val(g_args.seller_level);

    $("#id_add").on("click",function(){
        var seller_level= $("<select/>" );
        Enum_map.append_option_list("seller_level",seller_level);
        var define_date = $('<input type=text name=define_date >');
        var base_salary = $('<input type=text name=base_salary >');
        var sup_salary = $('<input type=text name=sup_salary >');
        var per_salary = $('<input type=text name=per_salary >');
        var arr=[
            ["销售等级" ,seller_level],
            ['定义时间',define_date],
            ["基本工资" ,base_salary],
            ['保密津贴',sup_salary],
            ['绩效工资',per_salary],
        ] ;
        define_date.datetimepicker( {
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
                $.do_ajax("/seller_level_goal/add_seller_level_goal_salary",{
                    "seller_level" : seller_level.val(),
                    "define_date"  : define_date.val(),
                    "base_salary"  : base_salary.val(),
                    "sup_salary"   : sup_salary.val(),
                    "per_salary"   : per_salary.val(),
                });
            }
        })
    });

    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var seller_level= $("<select disabled='disabled' />" );
        Enum_map.append_option_list("seller_level",seller_level);
        var define_date = $('<input type=text name=def_time disabled>');
        var base_salary = $('<input type=text name=base_salary >');
        var sup_salary = $('<input type=text name=sup_salary >');
        var per_salary = $('<input type=text name=per_salary >');
        seller_level.val(opt_data.seller_level);
        define_date.val(opt_data.define_date);
        base_salary.val(opt_data.base_salary);
        sup_salary.val(opt_data.sup_salary);
        per_salary.val(opt_data.per_salary);

        var arr=[
            ["销售等级" ,seller_level],
            ['定义时间',define_date],
            ["基本工资" ,base_salary],
            ['保密津贴',sup_salary],
            ['绩效工资',per_salary],
        ] ;
        define_date.datetimepicker( {
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
                $.do_ajax("/seller_level_goal/edit_seller_level_goal_salary",{
                    "seller_level" : seller_level.val(),
                    "define_date"  : define_date.val(),
                    "base_salary"  : base_salary.val(),
                    "sup_salary"   : sup_salary.val(),
                    "per_salary"   : per_salary.val(),
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
                    $.do_ajax("/seller_level_goal/del_seller_level_salary", {
                        "seller_level" : opt_data.seller_level,
                    });
                }
            })
    });


	  $('.opt-change').set_input_change_event(load_data);
});

