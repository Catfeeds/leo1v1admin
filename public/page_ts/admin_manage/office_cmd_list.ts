/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-office_cmd_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }


    $('.opt-change').set_input_change_event(load_data);

    $("#id_add").on("click",function(){
        var $office_device_type=$("<select />");
        var $device_opt_type=$("<select />");
        var $device_id=$("<input/>");
        var $value=$("<select> <option value=24>24 </option>  <option value=26>26 </option>  <option value=28>28 </option>   </select>");
        Enum_map.append_option_list("office_device_type" , $office_device_type, true);
        Enum_map.append_option_list("device_opt_type" , $device_opt_type, true);
        $value.val(26 );
        var arr=[
            ["设备类型", $office_device_type],
            ["设备id", $device_id],
            ["开关", $device_opt_type],
            ["温度", $value ],
        ];

        $.show_key_value_table("新增操作", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/office_cmd_add",{
                    office_device_type : $office_device_type.val(),
                    device_opt_type : $device_opt_type.val(),
                    device_id: $device_id.val(),
                    value : $value.val(),
                });
            }
        });
    });


});
