/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-specialty.d.ts" />


$(function(){

    Enum_map.append_option_list("grade", $("#id_grade"));
    Enum_map.append_option_list( "subject", $("#id_subject"));
    $("#id_teacherid").val(g_args.teacherid);
	$("#id_grade").val(g_args.grade);
	$("#id_subject").val(g_args.subject);
    $.admin_select_user ( $("#id_teacherid"),"teacher",function(id){
            load_data();
    });



	$(".opt-change").on("change",function(){
		load_data();
	});

    function load_data(){

        $.reload_self_page({
            grade           : $("#id_grade").val(),
            teacherid       : $("#id_teacherid").val(),
            subject         : $("#id_subject").val()
        });
    }

    var get_item_val=function(obj,name,value){
        var id_val=$("#id_"+name).val();
        console.log(id_val);
        if(id_val!=value){
            console.log(1);
            obj.val(id_val);
        }
    }

    $("#id_add_closest").on("click", function(){
        var id_degree       = $("<select/>");
        var id_grade        = $("<select/>");
        var id_grade_ex     = $("<select/>");
        var id_subject      = $("<select/>");
        var id_introduction = $("<input>");
        var id_teacher      = $("<input>");

        Enum_map.append_option_list("degree", id_degree,true);
        Enum_map.append_option_list("grade", id_grade,true);
        Enum_map.append_option_list("grade", id_grade_ex,true);
        Enum_map.append_option_list("subject", id_subject,true);

        get_item_val(id_teacher,"teacherid",-1);
        get_item_val(id_subject,"subject",-1);
        get_item_val(id_grade,"grade",-1);

        id_degree.val(1);
        var arr = [
            [ "老师",  id_teacher] ,
            [ "科目",  id_subject] ,
            [ "年级1",  id_grade] ,
            [ "--",  "如果老师擅长年级有多个,如:1,2,3年级。<br>"
              +"则年级1选择小一,年级2选择小三即可。<br>"
              +"如果只擅长一个年级,就不用管年级2选项"] ,
            [ "年级2",  id_grade_ex] ,
            [ "程度",  id_degree] ,
            [ "说明",  id_introduction] ,
        ];

        id_grade.on("change",function(){
            id_grade_ex.val($(this).val());
        });

        $.show_key_value_table("新增老师特长", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var degree       = id_degree.val();
                var teacher      = id_teacher.val();
                var grade        = id_grade.val();
                var grade_ex     = id_grade_ex.val();
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
                if(grade_ex<grade){
                    BootstrapDialog.alert("老师擅长年级2不能小于年级1");
                    return;
                }
                

                $.do_ajax('/human_resource/add_teacher',{
                    'grade'        : grade,
                    'grade_ex'     : grade_ex,
                    'subject'      : subject,
                    'degree'       : degree,
                    'teacher'      : teacher,
                    'introduction' : introduction
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
    });

    $(".opt-edit-info").on("click", function(){
        var teacherid = $(this).get_opt_data("teacherid");
        var grade     = $(this).get_opt_data("grade");
        var subject   = $(this).get_opt_data("subject");

        var id_degree       = $("<select/>");
        var id_introduction = $("<input>");
        
        Enum_map.append_option_list("degree", id_degree,true);
        
        id_degree.val($(this).get_opt_data("degree"));
        id_introduction.val($(this).get_opt_data("introduction"));
        var arr = [
            [ "年级",  $(this).get_opt_data("grade_str") ] ,
            [ "科目",   $(this).get_opt_data("subject_str") ] ,
            [ "程度",  id_degree] ,
            [ "说明",  id_introduction] ,
        ];

        $.show_key_value_table("编辑老师信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var degree       = id_degree.val();
                var introduction = id_introduction .val();

                $.do_ajax('/human_resource/edit_teacher',
                          {
                              'teacherid'    : teacherid,
                              'grade'        : grade,
                              'subject'      : subject,
                              'degree'       : degree,
                              'introduction' : introduction
                          });
			    dialog.close();
            }
        });
    });

    //删除        
	$(".done_t").on("click", function(){
        var teacherid = $(this).get_opt_data("teacherid");
        var subject   = $(this).get_opt_data("subject");
        var grade     = $(this).get_opt_data("grade");
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
			                data     :{
                                'grade':grade,
                                'teacherid':teacherid,
                                'subject':subject
                            },
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
