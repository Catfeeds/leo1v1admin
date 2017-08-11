/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/customer_service-intended_user_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }




    $('.opt-change').set_input_change_event(load_data);
   // alert(111);

    $("#id_add_indended_user_info").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var phone             = $("<input />");  //联系电话
        var child_realname    = $("<input />");  //孩子姓名
        var parent_realname   = $("<input />");  //家长姓名
        var relation_ship     = $("<select/>");  //关系
        var region            = $("<input />");  //地区
        var grade             = $("<select/>");  //年级
        var free_subject      = $("<select/>");  //试听科目
        var region_version    = $("<select/>");  //教材版本
        var notes             = $("<textarea />"); //备注

        Enum_map.append_option_list("relation_ship",relation_ship, true,);
        Enum_map.append_option_list("grade", grade, true);
        Enum_map.append_option_list("subject",free_subject,true);
        Enum_map.append_option_list("region_version",region_version,true);

        var arr = [
            ["联系电话", phone],
            ["孩子姓名", child_realname],
            ["家长姓名", parent_realname],
            ["关系",    relation_ship],
            ["地区",    region],
            ["年级",    grade],
            ["试听科目", free_subject],
            ["教材版本", region_version],
            ["备注",    notes],
        ];
        $.show_key_value_table("增加意向用户记录", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
             	if(phone.val() === ''){
                    alert("请输入联系方式");
                    return;
                }
                if(child_realname.val() === ''){
                    alert("请输入孩子姓名");
                    return;
                }
                if(parent_realname.val() === ''){
                    alert("请输入家长姓名");
                    return;
                }
                if(relation_ship.val() <= 0){
                    alert("请选择关系");
                    return;
                }
                if(region.val() === ''){
                    alert("请输入地区");
                    return;
                }
                if(grade.val() <= 0){
                    alert("请选择年级");
                    return;
                }
                if(free_subject.val() <= 0){
                    alert("请选择试听科目");
                    return;
                }
              
                if(region_version.val() <= 0){
                    alert("请选择教材版本");
                    return;
                }
                $.do_ajax("/ajax_deal2/add_intended_user_info",{
                    "phone"          : phone.val(),
                    'child_realname' : child_realname.val(),
                    'parent_realname': parent_realname.val(),
                    'relation_ship'  : relation_ship.val(),
                    'region'         : region.val(),
                    'grade'          : grade.val(),
                    'free_subject'   : free_subject.val(),
                    'region_version' : region_version.val(),
                    'notes'          : notes.val(),
                });
            }
        },function(){
        });
    });

    $(".opt-reset").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax("/ajax_deal2/reset_intended_user_info",{
            "phone"          : opt_data.phone,
            'child_realname' : opt_data.child_realname,
            'grade'          : opt_data.grade,
            'free_subject'   : opt_data.free_subject,
            'region_version' : opt_data.region_version,
            'notes'          : opt_data.notes,
        });
 
    });

    //alert(222);
    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm("要删除孩子是["+opt_data.child_realname+"]的意向信息吗?",function(val){
            if(val){
                $.do_ajax("/ajax_deal2/del_intended_user_info",{
                    "id" : opt_data.id
                });
            }
        });
    });
    //alert(333);

    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var phone             = $("<input />");  //联系电话
        var child_realname    = $("<input />");  //孩子姓名
        var parent_realname   = $("<input />");  //家长姓名
        var relation_ship     = $("<select/>");  //关系
        var region            = $("<input />");  //地区
        var grade             = $("<select/>");  //年级
        var free_subject      = $("<select/>");  //试听科目
        var region_version    = $("<select/>");  //教材版本
        var notes             = $("<textarea />"); //备注

        Enum_map.append_option_list("relation_ship",relation_ship, true,);
        Enum_map.append_option_list("grade", grade, true);
        Enum_map.append_option_list("subject",free_subject,true);
        Enum_map.append_option_list("region_version",region_version,true);


        phone.val(opt_data.phone);
        child_realname.val(opt_data.child_realname);
        parent_realname .val(opt_data.parent_realname);
        relation_ship.val(opt_data.relation_ship);
        region.val(opt_data.region);
        grade.val(opt_data.grade);
        free_subject.val(opt_data.free_subject);
        region_version.val(opt_data.region_version);
        notes.val(opt_data.notes);


        var arr = [
            ["联系电话", phone],
            ["孩子姓名", child_realname],
            ["家长姓名", parent_realname],
            ["关系",    relation_ship],
            ["地区",    region],
            ["年级",    grade],
            ["试听科目", free_subject],
            ["教材版本", region_version],
            ["备注",    notes],
        ];

        $.show_key_value_table("修改意向用户记录", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
             	if(phone.val() === ''){
                    alert("请输入联系方式");
                    return;
                }
                if(child_realname.val() === ''){
                    alert("请输入孩子姓名");
                    return;
                }
                if(parent_realname.val() === ''){
                    alert("请输入家长姓名");
                    return;
                }
                if(relation_ship.val() <= 0){
                    alert("请选择关系");
                    return;
                }
                if(region.val() === ''){
                    alert("请输入地区");
                    return;
                }
                if(grade.val() <= 0){
                    alert("请选择年级");
                    return;
                }
                if(free_subject.val() <= 0){
                    alert("请选择试听科目");
                    return;
                }
              
                if(region_version.val() <= 0){
                    alert("请选择教材版本");
                    return;
                }
                $.do_ajax("/ajax_deal2/edit_intended_user_info",{
                 	  "id"             : opt_data.id,
                    "phone"          : phone.val(),
                    'child_realname' : child_realname.val(),
                    'parent_realname': parent_realname.val(),
                    'relation_ship'  : relation_ship.val(),
                    'region'         : region.val(),
                    'grade'          : grade.val(),
                    'free_subject'   : free_subject.val(),
                    'region_version' : region_version.val(),
                    'notes'          : notes.val(),
                });
            }
        },function(){
        });
    }) ;
    //alert(444);

});
