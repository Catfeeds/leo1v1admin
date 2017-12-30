/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-power_group_edit.d.ts" />

$(function(){


    $("input[type=\"checkbox\"]").iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });
    var reload_data=function() {
        $.reload_self_page( {
			      show_flag:	$('#id_show_flag').val(),
            "groupid": $("#id_groupid").val()
        }) ;
    };
	  $('#id_show_flag').val(g_args.show_flag);
    $("#id_groupid").val(g_args.groupid);
    $(".opt-change").on("change",function(){
        reload_data();
    });


    $("#id_add_user").on("click",function(){
        $.admin_select_user($("#id_add_user"), "admin",function(val){
            $.do_ajax("/user_manage_new/opt_accont_group",{
                "uid" : val ,
                "groupid" : g_args.groupid ,
                "opt_type" :"add"
            });

        });
    });

    $(".opt-del-account").on("click",function(){
        $.do_ajax("/user_manage_new/opt_accont_group",{
            "uid" : $(this).get_opt_data("uid"),
            "groupid" : g_args.groupid ,
            "opt_type" :"del"
        });
    });

    $("#id_show_power").on("click",function(){
        $(this).closest("table").find(" tbody tr ").hide();
        var $checked_list=$(".icheckbox_flat-green.checked");
        $.each($checked_list,function(){
            var $item = $(this);
            var $td   = $item.parent();
            var level = $td.data("level");
            $td.parent().show();
            var class_list=$td.attr("class").split(" ") ;
            $.each(class_list, function(i,class_name){
                if (class_name.match("n_1_")) {
                    $(".l_1."+class_name).parent().show();
                }
                if (class_name.match("n_2_")) {
                    $(".l_2."+class_name).parent().show();
                }
            });

        });

    });



    $("#id_show_all_power").on("click",function(){
        var $this=$(this);
        var show_flag=$this.data("show_flag");
        if (show_flag) {
            $(".l_2").parent().hide();
            $(".l_3").parent().hide();
        }else{
            $(this).closest("table").find("tr").show();
        }
        $this.data("show_flag",!show_flag);
    });

    $(".opt-select").on("click",function(){
        var $this=$(this);
        var key=$this.data("key");


        var show_flag=$this.data("show_flag");
        if (show_flag) {
            $("."+key).parent().hide();
        }else{
            var next_level=$this.data("level")+1;
            $("."+key+ ".l_"+next_level ).parent().show();

        }
        $this.parent().show();

        $this.data("show_flag",!show_flag);
    });
    $(".l_2").parent().hide();
    $(".l_3").parent().hide();
    var link_css=        {
            color: "#3c8dbc",
            cursor:"pointer"
    };

    $(".l_folder").css(link_css);

    $(".iCheck-helper" ).on("click",function(){
        var $this=$(this);
        var check_flag=$(this).parent().hasClass("checked");
        var key=$this.closest ("tr").find(".opt-select").data("key");



        if (check_flag) {


            $("."+key).parent().find("input"). iCheck('check');
        }else{
            $("."+key).parent().find("input"). iCheck('uncheck');
        }

        reset_check_status();


    });


    $("#id_submit_power").on("click",function(){

        var $checked_list=$(".icheckbox_flat-green.checked");

        var power_list=[];
        $.each($checked_list,function(){
            var powerid=$(this).parent().data("powerid");
            if (powerid) {
                power_list.push(powerid);
            }
        });

       var power_list_str=power_list.join(",");
        $.do_ajax("/user_manage_new/set_group_power", {
            "groupid" :g_args.groupid,
            "power_list_str" : power_list_str
        },function(){
            $.do_ajax("/user_deal/reload_account_power",{});
        });

    });

    $("#id_del_group").on("click",function(){
        BootstrapDialog.confirm("要删除当前角色?!",function(ret){
            if (ret){
                $.do_ajax( "/user_manage_new/power_group_del",{
                    groupid: g_args.groupid
                });
            }
        });
      //
    });
    $("#id_add_group").on("click",function(){
        BootstrapDialog.confirm("要新增角色?!",function(ret){
            if (ret){
                $.do_ajax( "/user_manage_new/power_group_add",{
                });
            }
        });
    });

    $("#id_edit_group").on("click",function(){
        var v=$("#id_groupid").find("option:selected").text();

        $.show_input("修改角色名",  v, function(val){
            val=$.trim(val);
            if (!val) {
                alert("名称不能为空");
            }else{
                $.do_ajax( "/user_manage_new/power_group_set_name",{
                    "groupid" : g_args.groupid,
                    "group_name" : val
                });
            }
        });
    });

    function reset_check_status(){

        var arr=[];

        var folder=  $(".l_folder");
        folder.find("input").parent().removeClass("has_same");

        folder.each(function(){
            arr.unshift(this );
        });
        $.each(arr, function(){
            var item=$(this);
            var key=item.data("key");
            var sub_list=$("."+key);
            var find_check=false;
            var find_uncheck=false;
            var first_flag=false;
            sub_list.each(function(){
                if (!first_flag   ) { //first is self
                    first_flag=true;
                }else{
                    if (!find_check || !find_uncheck ){
                        var v=$(this).find("input").iCheckValue();
                        if (v) {
                            find_check=true;
                        }else{
                            find_uncheck=true;
                        }
                    }
                }
            });

            if ( find_check && find_uncheck ) {
                item.find("input").parent().addClass("has_same");
            }else if ( find_check ) {
                item.find("input").iCheck("check");
            }else if ( find_uncheck ) {
                item.find("input").iCheck("uncheck");

            }
        });
    }
    reset_check_status();

    $(".opt-set-power-user-list").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var powerid  = opt_data["powerid"];
        $.do_ajax("/user_manage_new/get_group_list_by_powerid",{
            "powerid" : powerid
        },function(response){
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["groupid"], this["group_name"]  ]);
                if (this["has_power"]) {
                    select_list.push (this["groupid"]) ;
                }
            });

            var screen_height=window.screen.availHeight-300;        
            $(this).admin_select_dlg({
                header_list     : [ "id","名称" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                div_style       : {"height":screen_height,"overflow":"auto"},
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/user_manage_new/set_power_with_groupid_list",{
                        powerid: powerid,
                        groupid_list:JSON.stringify(select_list)
                    },function(){
                        dlg.close();
                    });
                }
            });
        }) ;
    });


    $("#id_reload_power").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/user_deal/set_reload_power_time",{});
    });




});
