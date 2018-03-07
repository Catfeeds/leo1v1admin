/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test-testbb.d.ts" />
function load_data(){
  if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
//    order_by_str : g_args.order_by_str,
    account_role:	$('#id_account_role').val(),
    date_type_config:	$('#id_date_type_config').val(),
    date_type:	$('#id_date_type').val(),
    opt_date_type:	$('#id_opt_date_type').val(),
    start_time:	$('#id_start_time').val(),
    end_time:	$('#id_end_time').val(),
    nick_phone:	$('#id_nick_phone').val()
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
  $('#id_account_role').admin_set_select_field({
    "enum_type"    : "account_role",
    "field_name" : "account_role",
    "select_value" : g_args.account_role,
    "multi_select_flag"     : true,
    "onChange"     : load_data,
    "th_input_id"  : "th_account_role",
    "only_show_in_th_input"     : false,
    "btn_id_config"     : {},
  });
  $('#id_nick_phone').val(g_args.nick_phone);


  $('.opt-change').set_input_change_event(load_data);

   $(".opt-edit").on("click",function(){
       var opt_data=$(this).get_opt_data();
       alert(opt_data.phone );
       
       var $account_role=$("<select/>");
       Enum_map.append_option_list("account_role", $account_role,true);
       $account_role.val(opt_data.account_role);

       var arr=[
           ["account_role", $account_role] ,
       ];

      $.show_key_value_table("新增申请", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
          $.do_ajax("/test/testbb_post",{
              uid:  opt_data.uid,
              account_role:  $account_role.val(),
          });
      }
      });

   });

});
