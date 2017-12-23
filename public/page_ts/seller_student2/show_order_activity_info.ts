/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student2-show_order_activity_info.d.ts" />

$(function(){
    Enum_map.append_option_list("open_flag", $("#id_open_flag"));
    Enum_map.append_option_list("can_disable_flag", $("#id_can_disable_flag"));
    Enum_map.append_option_list("boolean", $("#id_spec_need_flg"));
    Enum_map.append_option_list("order_activity_discount_type", $("#id_discount_type"));

    $("#id_open_flag").val(g_args.id_open_flag);
    $("#id_can_disable_flag").val(g_args.id_can_disable_flag);
    $("#id_spec_need_flg").val(g_args.id_spec_need_flg);
    $("#id_discount_type").val(g_args.id_discount_type);

    $('.opt-change').set_input_change_event(load_data);

    //添加活动
    $('#id_add_activity').on('click',function(){
        var id_title = $("<input style='width:100%'/>");
        var id_id = $('<input onkeypress="keyPressCheck(this)" onkeyup="keyUpCheck(this)" />');
        var arr=[
            ["活动标题", id_title ],
            ["活动ID", id_id ],
        ];
        $.show_key_value_table("添加活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var id = id_id.val();
                var title = id_title.val();
                var data = {
                    'title':title,
                    'id':id
                }
                if(!title){
                    BootstrapDialog.alert("活动标题必填");
                    return false;
                }   
                if(id && id_id.val().length > 10){
                    BootstrapDialog.alert("活动id最长为10位");
                    return false;

                }
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/add_order_activity",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        console.log(res);
                        BootstrapDialog.alert(res.msg);
                        if( res.status == 200){
                            window.location.reload();
                        }
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
            '/seller_student2/get_order_activity?id='+ opt_data.id +"&return=all"
        );
    });

    //当前有效活动
    $('#current_activity').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/seller_student2/get_current_activity'
        );
    });
  
});
    
function load_data(){

    var data = {
        id_open_flag   : $("#id_open_flag").val(),
        id_can_disable_flag    : $("#id_can_disable_flag").val(),
        id_spec_need_flg    : $("#id_spec_need_flg").val(),
        id_discount_type    : $("#id_discount_type").val(),
    };

    //$.do_ajax("/seller_student2/show_order_activity_info",data,function(){});

    $.reload_self_page(data);
}

function keyPressCheck(ob) {
    if (!ob.value.match(/^[\+\-]?\d*?\.?\d*?$/)) ob.value = ob.t_value; else ob.t_value = ob.value; if (ob.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/)) ob.o_value = ob.value;
}
function keyUpCheck(ob) {
    if (!ob.value.match(/^[\+\-]?\d*?\.?\d*?$/)) ob.value = ob.t_value; else ob.t_value = ob.value; if (ob.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/)) ob.o_value = ob.value;
}
