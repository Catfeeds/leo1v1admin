/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_bacon-test_ajax_more.d.ts" />


$(function(){
    //编辑活动
    $('#test_ajax_more').on('click',function(){


        var id_max_count = $("<input id='test_ajax' style='width:80%'/>");

        var arr=[
            ["活动合同数", id_max_count ],
        ];
        $.show_key_value_table("编辑合同数", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var max_count = id_max_count.val();
                var data = {
                    'max_count':max_count,
                }

               
                $.ajax({
                    type     :"post",
                    url      :"/seller_student2/update_current_commom_activity",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        BootstrapDialog.alert(res.msg);
                        window.location.reload();
                    }
                });
            }
        },function(){

            id_max_count.admin_select_dlg_ajax_more({
                "opt_type" : "select", // or "list"
                "url"      : "/user_manage/get_user_list",
                //其他参数
                "args_ex" : {
                    //type  :  "teacher"
                },
       
                select_primary_field   : "id",   //要拿出来的值
                select_display         : "nick",
                select_no_select_value : 0,
                select_no_select_title : "[未设置]",
                select_have_id_arr : [60024,60029,60080],

                //字段列表
                'field_list' :[
                    {
                    title:"userid",
                    width :50,
                    field_name:"id"
                },{
                    title:"性别",
                    render:function(val,item) {
                        return item.gender;
                    }
                },{
                    title:"昵称",
                    //width :50,
                    render:function(val,item) {
                        return item.nick;
                    }
                },{
                    title:"电话",
                    field_name:"phone"
                }

                ] ,
                //查询列表
                filter_list:[
                    [
                    {
                        size_class: "col-md-4" ,
                        title :"性别",
                        type  : "select" ,
                        'arg_name' :  "gender"  ,
                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部" 
                        },{
                            value :  1 ,
                            text :  "男" 
                        },{
                            value :  2 ,
                            text :  "女" 
                        }]
                    },{
                        size_class : "col-md-8" ,
                        title      : "姓名/电话",
                        'arg_name' : "nick_phone"  ,
                        type       : "input" 
                    }

                ] 
                ],
                "auto_close"       : true,
                //选择
                "onChange"         : null,
                //加载数据后，其它的设置
                "onLoadData"       : null

            });

        },false,800)

    })



    $('#test_forbid').click(function(){
        var permission =  "55,59,69";
        var role = 1;
        var uid = 218;
        $.do_ajax("/user_power/get_permission_list",{
            "permission" : permission,
            "account_role":role
        },function(response){
            var data_list   = [];
            var select_list = [];
            var forbid_arr = [];
            var permit = "";
            var forbid = "";
            $.each( response.data,function(){
                data_list.push([this["groupid"],this["account_role_str"], this["group_name"]]);
                
                if (this["has_power"]) {
                    select_list.push (this["groupid"]) ;
                };
                if( this['forbid'] == 1 ){
                    forbid_arr.push (this["groupid"]) ;                    
                }
                if( permit == '' && this['forbid'] == 0 ){                    
                    permit = this["account_role_str"];                                            
                }
            });
            var forbid_title = "该用户属角色:"+permit+",只有该角色下的权限可以添加或者移除,其他角色下的权限只有超级管理员可以编辑";
            //console.log(forbid_arr);
            $(this).admin_select_dlg_forbid({
                header_list     : [ "id","角色","名称" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                forbid_arr      : forbid_arr,
                forbid_title    : forbid_title,
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/authority/set_permission",{
                        "uid": uid,
                        "groupid_list":JSON.stringify(select_list),
                        "old_permission": permission,
                    });
                }
            });
        }) ;
 
    })

    $("#opt-test-paper").on("click",function(){
        
        var opt_data  = $(this).get_opt_data();
        var user_id = 542755;
        var phone = 18021110908;
        $("<div></div>").admin_select_dlg_ajax_second({
            "opt_type" : "select", // or "list"
            "url"      : "/test_paper/get_papers",
            //其他参数
            "args_ex" : {
                //type  :  "teacher"
            },
            title : "选择生成的链接",
            btn_title : "生成链接",
            is_lru_show : false,
            select_primary_field   : "paper_id",   //要拿出来的值
            select_display         : "paper_id",
            select_no_select_value : -1,
            select_no_select_title : "[全部]",
            width:600,
            //字段列表
             'field_list' :[
                 {
                 title:"测试卷名称",
                 width:400,
                 render:function(val,item) {

                     var paper_url = "https://ks.wjx.top/jq/" + item.paper_id + ".aspx?sojumpparm="+item.paper_id+"-"+user_id+"-"+phone;
                     return "<a href='"+paper_url+"' target='_blank'>" + item.paper_name + "</a>";
                 }
             },
                {
                title:"科目",
                width:200,
                render:function(val,item) {
                    return item.subject_str;
                }
            },
                {
                title:"年级",
                width:200,
                render:function(val,item) {
                    return item.grade_str;
                }
            },
                {
                title:"教材版本",
                width:200,
                render:function(val,item) {
                    return item.book_str;
                }
            }
            ] ,
            //查询列表
            filter_list:[[
                {
                size_class: "col-md-4 paper_subject" ,
                title :"科目",
                type  : "select" ,
                'arg_name' :  "subject",
                select_option_list: [],
                default_selected : "2",
            },{
                size_class: "col-md-4 paper_grade" ,
                title :"年级",
                type  : "select" ,
                'arg_name' :  "grade"  ,
                select_option_list: [],
                default_selected : "103",
            }
                 ]],
             "auto_close"       : false,
             "onChange"         : function(require_id,row_data){
                 if(!row_data){
                     BootstrapDialog.alert("请选择试卷！");
                     return false;
                 }
                 var paper = "<div class='paper_info'>"
                 paper += "<div><span class='paper_font'>评测卷名称</span><span>"+row_data.paper_name+"</span></div>";
                 var paper_url = "https://ks.wjx.top/jq/" + row_data.paper_id + ".aspx?sojumpparm="+row_data.paper_id+"-"+user_id+"-"+phone;
                 paper += "<div><span class='paper_font'>评测卷链接</span><span><a href='"+paper_url+"' target='_blank'>"+paper_url+"</a></span></div>";
                 paper += "<div><span class='paper_font'>友情提示</span><span>请微信扫一扫下面的二维码，转发给家长</span></div>";
                      

                 paper += "</div>";
                 var dlg= BootstrapDialog.show({
                     title: "测评卷链接 -> 测评卷二维码 ",
                     message : paper,
                     buttons: [{
                         label: '返回',
                         cssClass: 'btn-warning',
                         action: function(dialog) {
                             dialog.close();
                         }
                     }],
                     onshown: function(){
                        
                     }

                 });
                 dlg.getModalDialog().css("width", "730px");
             },
             "onLoadData"       : function(require_id,data){
             },
            "onshown"       : function(dlg){
                var hehe = $(dlg).find(".paper_subject select").html();
                // var dede = $(dlg).html();
                //console.log(dlg.$modalBody[0].find(".paper_subject select"));
                Enum_map.append_option_list("subject",  $(dlg).find(".paper_subject select"),false,[1,2,3,4,5,6,7,8,9,10,11]);
                $(dlg).find(".paper_subject select").val(2);
                Enum_map.append_option_list("grade",  $(dlg).find(".paper_grade select"));
                $(dlg).find(".paper_grade select").val(103);

                console.log(hehe);
                console.log(dlg);
            }

         });
    });

});
    
