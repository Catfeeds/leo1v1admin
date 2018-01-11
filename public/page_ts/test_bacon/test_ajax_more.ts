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

});
    
