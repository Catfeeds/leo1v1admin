/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/order_refund_confirm_config-refund_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            key1:	$('#id_key1').val(),
            key2:	$('#id_key2').val(),
            key3:	$('#id_key3').val(),
        });
    }


    $('#id_key1').val(g_args.key1);
    $('#id_key2').val(g_args.key2);
    $('#id_key3').val(g_args.key3);

    $('.opt-change').set_input_change_event(load_data);


    $("#submit-refund").on("click", function(){
        var id_value = $("<input/>");
        var key1_text= $("#id_key1").find("option:selected").text();
        var key2_text= $("#id_key2").find("option:selected").text();
        var key3_text= $("#id_key3").find("option:selected").text();


        var key1= $("#id_key1").val();
        var key2= $("#id_key2").val();
        var key3= $("#id_key3").val();

        var arr = [
        ];
        if (key1==-1) {
            arr.push(["部门" , id_value]);
        } else if (key2==-1 ) {
            arr.push(["部门" , key1_text ]);
            arr.push(["一级原因" ,  id_value ]);
        } else if (key3 == -1) {
            arr.push(["部门",key1_text]);
            arr.push(["二级原因", id_value]);
        } else {
            arr.push(["部门",key1_text]);
            arr.push(["三级原因", id_value]);
        }



        $.show_key_value_table("填写退款原因", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/order_refund_confirm_config/deal_refund_info', {
                    "key1"  : key1,
                    "key2"  : key2,
                    "key3"  : key3,
                    "value" : id_value.val()
                });
            }
        },function(){
        });
    });



    
    $(".opt-del").on("click",function(){
        var id = $(this).attr("id-item");
        $.do_ajax('/order_refund_confirm_config/delete_refund_info', {
            "id"  : id,
        });

    });


});
