/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student2-get_current_commom_activity.d.ts" />


$(function(){
    Enum_map.append_option_list("open_flag", $("#id_open_flag"),false,[1,2]);
    $("#id_open_flag").val(g_args.id_open_flag);

    $('.opt-change').set_input_change_event(load_data);

    //编辑活动
    $('.edit_max_count').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var id_max_count = $("<input/>");
        id_max_count.val(opt_data.max_count);

        var id_diff_max_count = $("<span/>");
        id_diff_max_count.text(opt_data.diff_max_count);


        var arr=[
            ["活动ID", opt_data.id ],
            ["活动标题", opt_data.title ],
            ["活动合同数", id_max_count ],
            ["预期最大合同数", id_diff_max_count ],
        ];
        $.show_key_value_table("编辑合同数", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var max_count = parseInt(id_max_count.val());
                var diff_max_count = parseInt(id_diff_max_count.text());
                var data = {
                    'id':opt_data.id,
                    'max_count':max_count,
                }
                if(max_count > diff_max_count){
                    BootstrapDialog.alert("活动合同数不能大于预期最大合同数");
                    return false;
                }   
               
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_current_commom_activity",
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

    //打开活动页面
    $('.act-look').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open(
            '/seller_student_new2/show_order_activity_info?order_activity_type='+opt_data.id
        );
    });

});
    
function load_data(){

    var data = {
        id_open_flag   : $("#id_open_flag").val(),
    };

    $.reload_self_page(data);
}
