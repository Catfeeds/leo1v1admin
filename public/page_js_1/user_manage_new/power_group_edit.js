$(function(){
    $("#id_groupid").val(g_args.groupid);

    var reload_data=function() {
        reload_self_page( {
            "groupid": $("#id_groupid").val()
        }) ;
    };
    $(".opt-change").on("change",function(){
        reload_data();
    });

    $("#id_submit_power").on("click",function(){
        
        
    });

    $("#id_add_user").on("click",function(){
        admin_select_user($("#id_add_user"), "admin",function(val){
            do_ajax("/user_manage_new/opt_accont_group",{
                "uid" : val ,
                "groupid" : g_args.groupid ,
                "opt_type" :"add"
            });

        });
    });




    
    $(".opt-del-account").on("click",function(){
	    
        do_ajax("/user_manage_new/opt_accont_group",{
            "uid" : $(this).get_opt_data("uid"),
            "groupid" : g_args.groupid ,
            "opt_type" :"del"
        });
    });

    $("#id_show_power").on("click",function(){
        $(this).closest("table").find(" tbody tr ").hide();
        var $checked_list=$(".icheckbox_minimal.checked");
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

    });

    
    $("#id_submit_power").on("click",function(){

        var $checked_list=$(".icheckbox_minimal.checked");
        
        var power_list=[];
        $.each($checked_list,function(){
            var powerid=$(this).parent().data("powerid");
            if (powerid) {
                power_list.push(powerid);
            }
        });
       var power_list_str=power_list.join(",");
        do_ajax("/user_manage_new/set_group_power", {
            "groupid" :g_args.groupid,
            "power_list_str" : power_list_str
        });

    });
    
    $("#id_del_group").on("click",function(){
        BootstrapDialog.confirm("要删除当前角色?!",function(ret){
            if (ret){
                do_ajax( "/user_manage_new/power_group_del",{
                    groupid: g_args.groupid
                });
            }
        });
	    //
    });
    $("#id_add_group").on("click",function(){
        BootstrapDialog.confirm("要新增角色?!",function(ret){
            if (ret){
                do_ajax( "/user_manage_new/power_group_add",{
                });
            }
        });
	    //
    });

    $("#id_edit_group").on("click",function(){
        
        var v=$("#id_groupid").find("option:selected").text(); 
        show_input("修改角色名",  v, function(val){
            val=$.trim(val);
            if (!val) {
                alert("名称不能为空");
            }else{
                do_ajax( "/user_manage_new/power_group_set_name",{
                    "groupid" : g_args.groupid,
                    "group_name" : val
                });
            }
        });
    });


    

});
