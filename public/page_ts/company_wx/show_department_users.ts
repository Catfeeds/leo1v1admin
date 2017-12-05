/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/company_wx-role_list.d.ts" />

     var setting = {view: {showIcon: false},
         // check: {  
         //     enable: true,  
         //     chkStyle: "checkbox",  
         //     chkboxType: { "Y": "ps", "N": "ps" }  
         // }, 
         data: {
             simpleData: {
                 enable: true
             }
         },
         callback: {
             onClick: zTreeOnClick
         }
     };

function zTreeOnClick(event, treeId, treeNode) {
    return;
         // 处理
        // var userid= treeNode.userid;
        // var show_list=[];
        // if ($.get_action_str()=="manager_list_for_seller" ) {
        //     show_list=[57	, 38	, 74	, 77, 80	,];
        // }
        // var show_all_flag=($.get_action_str()=="manager_list");
        // show_all_flag = true;
        // var permission = treeNode.permission;
        // $.do_ajax("/authority/get_permission_list",{
        //     "permission" : permission
        // },function(response){
        //     var data_list   = [];
        //     var select_list = [];

        //     var perm = permission.split(",");

        //     $.each( response.data,function(){
        //         if (  show_all_flag || $.inArray(  parseInt( this["groupid"]),  show_list) != -1 ) {
        //             data_list.push([this["groupid"], this["group_name"]  ]);
        //         }

        //         for(var i=0; i<perm.length; i++) {
        //             if (perm[i] == this['groupid']) {
        //                 select_list.push (this["groupid"]) ;
        //             }
        //         }

        //     });
        //     $(this).admin_select_dlg({
        //         header_list     : [ "id","名称" ],
        //         data_list       : data_list,
        //         multi_selection : true,
        //         select_list     : select_list,
        //         onChange        : function( select_list,dlg) {
        //             $.do_ajax("/company_wx/set_permission",{
        //                 "userid": userid,
        //                 "groupid_list":JSON.stringify(select_list),
        //                 //"old_permission": permission,
        //             });
        //         }
        //     });
        // }) ;

     };

$(function(){
    $.fn.zTree.init($("#treeDemo"), setting, zNodes);

    // 刷新数据
    $('#id_flush_data').on("click", function() {
        $.do_ajax('/company_wx/flush_company_wx_data', {
        });

        // var timer1 = window.setInterval(function(){
        //             // 刷新日志
        // $.do_ajax('/company_wx/flush_company_wx_data_log', {
        //     //
        // }, function(res) {
        //     console.log(res.data);
        //     var len = res.data.length;
        //     console.log(res.data.length);
        //     if (len == 0) {
        //         window.clearInterval(timer1);
        //     }
        //     $.each(res.data,function() {
        //         $('#log-msg').append('<p>' + this + '</p>');
        //     });
        // });

        // },1000);
    });

        var timer1 = window.setInterval(function(){
                    // 刷新日志
        $.do_ajax('/company_wx/flush_company_wx_data_log', {
            //
        }, function(res) {
            console.log(res.data);
            var len = res.data.length;
            console.log(res.data.length);
            if (len == 0) {
                window.clearInterval(timer1);
            }
            $.each(res.data,function() {
                $('#log-msg').append('<p>' + this + '</p>');
            });
        });

        },1000);


});
