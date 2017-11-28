/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-show_order_activity_info.d.ts" />

$(function(){
    Enum_map.append_option_list("open_flag", $("#id_open_flag"),false,[1,2]);
    $("#id_open_flag").val(g_args.id_open_flag);

    $('.opt-change').set_input_change_event(load_data);

    //编辑活动
    $('.act-edit').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var id_power_value = $("<input/>");
        id_power_value.val(opt_data.power_value); 
        var arr=[
            ["活动ID", opt_data.id ],
            ["活动标题", opt_data.title ],
            ["活动力度", id_power_value ],
        ];
        $.show_key_value_table("编辑活动", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var power_value = id_power_value.val();
                var data = {
                    'id':opt_data.id,
                    'power_value':power_value
                }
                if(!power_value){
                    BootstrapDialog.alert("活动力度必填");
                    return false;
                }   
               
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_power_value",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        BootstrapDialog.alert(res.msg);
                        window.location.reload();
                    }
                });
            }
        })

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
    };

    $.reload_self_page(data);
}
