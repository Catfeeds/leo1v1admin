/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/company_wx-all_users.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){
    $('.opt-leader').on('click', function() {
        var id = $(this).parent().parent().find('.id').text();
        var permission = $(this).parent().parent().find('.leader_power').text();
        var show_all_flag = true;
        update_permission(permission, show_all_flag, id, 1);

    });

    $('.opt-not-leader').on('click', function() {
        var id = $(this).parent().parent().find('.id').text();
        var permission = $(this).parent().parent().find('.no_leader_power').text();
        var show_all_flag = true;
        update_permission(permission, show_all_flag, id, 2);
    });

	  $('.opt-change').set_input_change_event(load_data);

    function update_permission(permission, show_all_flag, id, status) {
        $.do_ajax("/authority/get_permission_list",{
            "permission" : permission
        },function(response){
            var data_list   = [];
            var select_list = [];

            var perm = permission.split(",");

            $.each( response.data,function(){
                if (  show_all_flag || $.inArray(  parseInt( this["groupid"]),  show_list) != -1 ) {
                    data_list.push([this["groupid"], this["group_name"]  ]);
                }

                for(var i=0; i<perm.length; i++) {
                    if (parseInt(perm[i]) == this['groupid']) {
                        select_list.push (this["groupid"]) ;
                    }
                }

            });
            $(this).admin_select_dlg({
                header_list     : [ "id","名称" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/company_wx/set_permission",{
                        "id": id,
                        "status": status,
                        "groupid_list":JSON.stringify(select_list),
                        "old_permission": permission,
                    });
                }
            });
        }) ;

    }
});
