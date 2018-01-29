/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-record_audio_server_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }


    setInterval(function(){
        $(".common-table").removeClass("table-striped");
    }, 50);


    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();

        BootstrapDialog.confirm("要删除"+opt_data.ip,function(val){
            if (val) {
                $.do_ajax("/user_manage_new/record_audio_server_del",{
                    "ip"   :opt_data.ip
                });
            }
        });

    });


  $('.opt-change').set_input_change_event(load_data);


    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var $config_userid=$("<input/>")  ;
        var $priority=$("<input/>")  ;
        var $desc=$("<input/>")  ;
        var $max_record_count =$("<input/>")  ;
        $config_userid.val(opt_data.config_userid);
        $priority.val(opt_data.priority);
        $max_record_count.val(opt_data.max_record_count);
        $desc.val(opt_data.desc);
      var arr=[
            ["权重", $priority ],
            ["最大同时记录数", $max_record_count ],
            ["配置userid", $config_userid ],
            ["说明", $desc ],
         ];
        $.show_key_value_table("配置",arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_manage_new/record_audio_server_set",{
                    "ip" : opt_data.ip,
                    "priority" : $priority.val(),
                    "max_record_count" : $max_record_count.val(),
                    "config_userid" : $config_userid.val(),
                    "desc" : $desc.val(),
                });
            }


        } );
    });
    $("#id_clear").on("click",function(){
        $(".opt-del").each( function(){
            if ($(this).closest("tr").hasClass("danger" )) {
                var opt_data=$(this).get_opt_data();
                $.do_ajax("/user_manage_new/record_audio_server_del",{
                    "ip"   :opt_data.ip
                });
            }
        } );

    });


});
