/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/o-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            id: g_args.id
        });
    }

    $("#id_open").on("click",function(){

        var $value=$("<select  >  <option value=20>20 </option>  <option value=22>22 </option>  <option value=24>24 </option> <option value=26>26 </option> <option value=28>28 </option> </select>");
        $value.val(24);
        $.show_key_value_table("设置温度",[
            [ "温度", $value  ] 
        ],{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/office_cmd_add",{
                    office_device_type : 1,
                    device_opt_type : 1 ,
                    device_id:  g_args.id,
                    "value"  : $value.val(),
                },function(){
                    BootstrapDialog.alert ("请等待10秒, 开机中.. ");
                });

            }
        } );

    });

    $("#id_open_ex").on("click",function(){
        var $value=$("<select  >  <option value=20>20 </option>  <option value=22>22 </option>  <option value=24>24 </option> <option value=26>26 </option> <option value=28>28 </option> </select>");
        $value.val(24);
        $.show_key_value_table("设置温度",[
            [ "温度", $value  ] 
        ],{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal2/office_cmd_add",{
                    office_device_type : 1,
                    device_opt_type : 2 ,
                    device_id:  g_args.id,
                    "value"  : $value.val(),
                },function(){
                    BootstrapDialog.alert ("重置遥控器,请等待30秒, 开机中.. ");
                });

            }
        } );

    });

    $("#id_close").on("click",function(){
        $.do_ajax("/ajax_deal2/office_cmd_add",{
            office_device_type : 1,
            device_opt_type : 0 ,
            device_id:  g_args.id
        },function(){
            BootstrapDialog.alert ("请等待10秒, 关机中.. ");
        });
    });
});
