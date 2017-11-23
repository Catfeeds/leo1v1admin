/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-show_order_activity_info.d.ts" />

$(function(){
    Enum_map.append_option_list("open_flag", $("#id_open_flag"));
    Enum_map.append_option_list("can_disable_flag", $("#id_can_disable_flag"));
    Enum_map.append_option_list("contract_type", $("#id_contract_type"));
    Enum_map.append_option_list("period_flag", $("#id_period_flag"));

    $("#id_open_flag").val(g_args.id_open_flag);
    $("#id_can_disable_flag").val(g_args.id_can_disable_flag);
    $("#id_contract_type").val(g_args.id_contract_type);
    $("#id_period_flag").val(g_args.id_period_flag);

    $('.opt-change').set_input_change_event(load_data);

    //添加活动
    $('#id_add_activity').on('click',function(){
        var id_title = $("<input/>");
        var arr=[
            ["活动标题", id_title ],
        ];
        $.show_key_value_table("添加活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                var title = id_title.val();
                var data = {
                    'title':title
                }
                if(!title){
                    BootstrapDialog.alert("活动标题必填");
                    return false;
                }
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/add_order_activity",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
                    }
                });
            }
        })

    })
    
    //删除活动
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var id = opt_data.id;
        var title = "你确定删除本活动,标题为" + opt_data.title + "？";
        var data = {
            'id':id
        };

        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/seller_student2/dele_order_activity",data);
            }
        });

    })

    //进入编辑页面
    $('.opt-set').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/seller_student2/get_order_activity?id='+ opt_data.id +"&return_url="+ encodeURIComponent(window.location.href)
        );
    });

      
});
    
function load_data(){

    var data = {
        id_open_flag   : $("#id_open_flag").val(),
        id_can_disable_flag    : $("#id_can_disable_flag").val(),
        id_contract_type       : $("#id_contract_type").val(),
        id_period_flag   : $("#id_period_flag").val(),
    };

    //$.do_ajax("/seller_student2/show_order_activity_info",data,function(){});

    $.reload_self_page(data);
}

