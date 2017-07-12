$(function(){
    $("tr").each(function(){
        var grade     = $(this).find(".opt").data("grade");
        var html_node = "<a href=\"javascript:\;\" title=\"设置老师形象\" class=\"btn fa fa-heart opt-update-tea-clothes\"></a>";
        var html_str = "<a href=\"javascript:\;\" title=\"统一老师形象\" class=\"btn fa fa-heart opt-update-tea-clothes-all\"></a>";
        if(grade<103){
            $(this).find(".opt-del-lesson-info").before(html_str);
            $(this).find(".opt-student-list").after(html_node);
        }
    });

    $('.opt-update-tea-clothes').on('click',  function(){
        var teacherid = $(this).parent().data("teacherid");
        var lessonid  = $(this).parent().data("lessonid");
        if(teacherid==0){
            BootstrapDialog.alert("请先设置老师！");
        }else{
            do_ajax('/small_class/get_teacher_clothes', {
                'teacherid' : teacherid,
                'lessonid'  : lessonid
            },function(result){
                set_teacher_clothes(result.ret_info,lessonid,teacherid);
            });
        }
    });

    $('.opt-update-tea-clothes-all').on('click',  function(){
        var teacherid = $(this).parent().data("teacherid");
        var courseid  = $(this).parent().data("courseid");
        //var type = 'all';
        if(teacherid==0){
            BootstrapDialog.alert("请先设置老师！");
        }else{
            do_ajax('/small_class/get_teacher_clothes', {
                'teacherid' : teacherid
            },function(result){
                set_teacher_clothes(result.ret_info,courseid,teacherid,'all');
            });
        }
    });
    
    var set_teacher_clothes = function(data,lessonid,teacherid,all_type='') {
        var type=302;
        if(data.gender==1 || data.gender==0){
            type=301;
        }
        
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg_update_teacher_clothes'));
        ajax_set_select_box(
            html_node.find(".tea_pic"),"/small_class/get_teacher_clothes_list"
            ,{
                "type":type
            }
            ,data.clothes,true);
        BootstrapDialog.show({
	        title   : '修改老师形象',
	        message : html_node,
            onshown : function(){
                get_teacher_pic(data.clothes);
            },
	        buttons: [{
		        label  : '返回',
		        action : function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label    : '确认',
		        cssClass : 'btn-warning',
		        action   : function(dialog) {
                    var clothes = html_node.find('.tea_pic').val();
                    do_ajax('/small_class/set_teacher_clothes',{
                        'lessonid'  : lessonid,
                        'teacherid' : teacherid,
                        'clothes'   : clothes,
                        'all_type'  : all_type
                    },function(result){
			            dialog.close();
                    });
		        }
	        }]
        });
    };

    var get_teacher_pic = function(id){
        if(id!=0){
            do_ajax('/small_class/get_teacher_pic', {
                'id': id
            },function(result){
                var pic_url=result.ret_info;
                var pic_url_html="<img src=\""+pic_url+"\">";
                $(".show_tea_pic").html(pic_url_html);
            });
        }else{
            $(".show_tea_pic").empty();
        }
    };

    $('body').on('change','.tea_pic',function(){
        var id = $(this).val();
        get_teacher_pic(id);
    });

});
