$(function(){
    $("#id_teacherid").val(g_args.teacherid);

    $("#id_teacherid").admin_select_user({
        "type"   : "teacher",
        "onChange": function(){
            load_data();
        }
    });


	$(".opt-change").on("change",function(){
		load_data();
	});

    function load_data(){

        reload_self_page({
            teacherid       : $("#id_teacherid").val()
        });
    }



    $("#id_add_closest").on("click", function(){
        var id_degree       = $("<select/>");
        var id_grade        = $("<select/>");
        var id_subject      = $("<select/>");
        var id_introduction = $("<input>");
        var id_teacher      = $("<input>");
        

        Enum_map.append_option_list("degree", id_degree,true);
        Enum_map.append_option_list("grade", id_grade,true);
        Enum_map.append_option_list("subject", id_subject,true);
        
        var arr = [
            [ "程度",  id_degree] ,
            [ "老师",  id_teacher] ,
            [ "年级",  id_grade] ,
            [ "科目",  id_subject] ,
            [ "说明",  id_introduction] ,
        ];
        show_key_value_table("新增老师信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var degree       = id_degree.val();
                var teacher      = id_teacher.val();
                var grade        = id_grade.val();
                var subject      = id_subject.val();
                var introduction = id_introduction .val();
                if (degree == 0) {
                    BootstrapDialog.alert("老师程度不能为（无）信息无效");
                    return;
                }
                if (!teacher) {
                    BootstrapDialog.alert('老师不可以为空');
                    return;
                }
                

                $.ajax({
                    url  : '/human_resource/add_teacher',
                    type : 'POST',
                    data : {
                        'grade'        : grade,
                        'subject'      : subject,
                        'degree'       : degree,
                        'teacher'      : teacher,
                        'introduction' : introduction
                    },
                    dataType: 'json',
                    success:   ajax_default_deal_func
                });
			    dialog.close();
            }
        });

        id_teacher.admin_select_dlg_ajax({
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
                    size_class: "col-md-8" ,
                    title :"姓名/电话",
                    'arg_name' :  "nick_phone"  ,
                    type  : "input" 
                }

            ] 
        ],
        "auto_close"       : true,
        //选择
        "onChange"         : null,
        //加载数据后，其它的设置
        "onLoadData"       : null

        });
 
       

    });

    $(".opt-edit-info").on("click", function(){
        var teacherid       =$(this).get_opt_data("teacherid");
        do_ajax("/human_resource/get_simple_teacher_info",{
            "teacherid" : teacherid
        },function(result){
            var data=result.data;
            var id_degree       = $("<select/>");
            var id_grade        = $("<select/>");
            var id_subject      = $("<select/>");
            var id_introduction = $("<input>");
            
            Enum_map.append_option_list("degree", id_degree,true);
            Enum_map.append_option_list("grade", id_grade,true);
            Enum_map.append_option_list("subject", id_subject,true);
            
            var arr = [
                [ "程度",  id_degree] ,
                [ "年级",  id_grade] ,
                [ "科目",  id_subject] ,
                [ "说明",  id_introduction] ,
            ];

            id_degree.val(data.degree);
            id_grade.val(data.grade);
            id_subject.val(data.subject);
            id_introduction.val(data.introduction);
            show_key_value_table("编辑老师信息", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action : function(dialog) {
                    var degree       = id_degree.val();
                    var grade        = id_grade.val();
                    var subject      = id_subject.val();
                    var introduction = id_introduction .val();
                    if (degree == 0) {
                        BootstrapDialog.alert("老师程度不能为（无）信息无效");
                        return;
                    }
                    $.ajax({
                        url  : '/human_resource/edit_teacher',
                        type : 'POST',
                        data : {
                            'teacherid'    : teacherid,
                            'grade'        : grade,
                            'subject'      : subject,
                            'degree'       : degree,
                            'introduction' : introduction
                        },
                        dataType: 'json',
                        success:   ajax_default_deal_func
                    });
			        dialog.close();
                }
            });

                       
        });

        

    });
//删除        
	$(".done_t").on("click", function(){
        var teacherid       =$(this).get_opt_data("teacherid");
        BootstrapDialog.show({
            title: '系统提示',
            message : '确认从教师相关信息',
            closable: true, 
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-danger',
                    action: function(dialog) {
		                $.ajax({
			                type     :"post",
			                url      :"/human_resource/delete_tea_closest",
			                dataType :"json",
			                data     :{'teacherid':teacherid},
			                success  : function(result){
                                if(result['ret'] != 0){
                                    alert(result['info']);
                                }else{
                                    window.location.reload();
                                }
			                }
		                });
                        dialog.close();
                    }
                },
                {
                    label: '返回',
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        dialog.close();
                    }
                },
                
            ]
        });
	});

 













});
