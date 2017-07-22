/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/appoint-index2.d.ts" />

$(function(){
    Enum_map.append_option_list( "contract_type_ex", $("#id_type"));
    Enum_map.append_option_list("subject", $("#id_subject_list"),true);
    Enum_map.append_option_list("grade", $("#id_grade_list"),true);

    $("#id_type").val(g_args.package_type);   
    $("tr").each(function(){
        var type= $(this).find(".opt").data("package_type");
        var html_small = "<a class='btn fa fa-group opt-set_small_class' href='javascript:;' title='添加小班课'></a>";
        var html_open  = "<a class='btn fa fa-video-camera opt-set_open_class' href='javascript:;' title='添加公开课'></a>";
        if(type==3001){
            $(this).find(".opt-package-outline").after(html_small);
        }else if(type==1001){
            $(this).find(".opt-package-outline").after(html_open);
        }
    });
    
    function load_data(){
        reload_self_page({
            package_type : $("#id_type").val()
        });
	}
    $(".stu_sel").on("change",function(){
		var type= $("#id_type").val();
		load_data(type);
	});	
    //字符串处理
    $("#opt-package-list").find(".grade").each(function(){
        var $re = $(this).html().replace(/,/g,",<br/>"); 
        $(this).html($re); 
    });
    $('#opt-package-list').on('click', '.opt-package-buy', function(){
        var packageid    = $(this).parent().data('packageid');
        dp_get_package_user_bought(packageid);
    });
    var dp_get_package_user_bought = function(packageid){
        
    };

    $('#opt-package-list').on('click', '.opt-package-open-courseid', function(){
        var packageid    = $(this).parent().data('packageid');
        dp_get_package_open_courseid(packageid);
    });
    var dp_get_package_open_courseid = function(packageid){
        $.getJSON('/appoint/get_open_courseid', {
            'packageid': packageid 
        }, function(result){
            console.log(result);
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
                dp_set_package_open_courseid(packageid, result['open_courseid']);   
            }
        });
    };

    var dp_set_package_open_courseid = function(packageid, open_courseid){
        var id_open_courseid = $('<input type="text" readonly="readonly">');
        var arr = [
            [ "公开课id",  id_open_courseid],
        ];

        id_open_courseid.val( open_courseid );
        id_open_courseid.on('click', function(){
            $(this).addClass('time_to_choose_open');
            dp_current_open_course();   
        });
        show_key_value_table("修改公开课id", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var open_courseid = id_open_courseid.val();

                if (parseInt(open_courseid) <= 0 || isNaN(parseInt(open_courseid)) ) {
                    BootstrapDialog.alert('请检查公开课id');
                    return;
                }

                $.getJSON('/appoint/set_open_courseid',{
                    'open_courseid': open_courseid, 'packageid': packageid
                }, function(result){
                    BootstrapDialog.alert(result['info']);
                    if (result['ret'] == 0) {
			            dialog.close();
                    }
                });
            }
        });
    };

    var dp_current_open_course = function(){
        $.getJSON('/appoint/get_open_classes', {
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
                return;
            }
            
            var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-choose-open-class'));
            if (result['open_list'].length != 0 && result['open_list'] != null) {
                for(var i=0; i<result['open_list'].length; i++) {
                    var open_str = '<tr class="open_record"><td class="courseid">'+
                            result['open_list'][i]['courseid'] + '</td><td>'+
                            result['open_list'][i]['course_name'] + '</td><td>'+
                            result['open_list'][i]['teacher_nick'] + '</td><td>'+
                            '</td></tr>';
                    html_node.find(".open_class_list").append(open_str);
                }
            }

            BootstrapDialog.show({
	            title: "选择公开课",
	            message : function(dialog) {
                    html_node.find('.open_class_list').on('click', '.open_record', function(){
                        html_node.find('.open_record').removeClass('warning');
                        $(this).addClass('warning');
                    });

                    return html_node;
                },
	            buttons: [{
		            label: '返回',
		            action: function(dialog) {
			            dialog.close();
		            }
	            }, {
		            label: '确认',
		            csopenlass: 'btn-warning',
		            action: function(dialog) {
                        var courseid = html_node.find('.warning').children('.courseid').text();
                        $('body').find('.time_to_choose_open').val(courseid);
                        $('body').find('.time_to_choose_open').removeClass('time_to_choose_open');
			            dialog.close();
		            }
	            }]
            });

        });
    };

    $('#opt-package-list').on('click', '.opt-package-users', function(){
        var packageid    = $(this).parent().data('packageid');
        dp_get_package_users(packageid);
    });

    var dp_get_package_users = function(packageid) {
        
        $.getJSON('/appoint/get_package_user_info', {
            'packageid': packageid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
               dp_set_package_users(packageid, result['package_info']);
            }
        });
    };

    var dp_set_package_users = function(packageid, package_info){
        var id_package_deadline = $("<input>");
        var id_user_total       = $("<input>");
        var id_user_buy        = $("<input>");

        var arr = [
            [ "截止时间",  id_package_deadline] ,
            [ "课程名额",  id_user_total] ,
            [ "已买名额",  id_user_buy] ,
        ];

        id_package_deadline.val( package_info['package_deadline']);
        id_user_total.val(package_info['user_total']);
        id_user_buy.val(package_info['user_buy']);

        id_package_deadline.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
        });


        show_key_value_table("修改最后截止时间和课程人数", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var package_deadline = id_package_deadline.val();
                var user_total       = id_user_total.val();
                var user_buy        = id_user_buy.val();

                if (!package_deadline|| !user_total ) {
                    BootstrapDialog.alert('请检查是否已经全部输入');
                    return;
                }

                if (parseInt(user_total) <= 0 || isNaN(parseInt(user_total)) ) {
                    BootstrapDialog.alert('请检查学生数');
                    return;
                }


                $.ajax({
                    url: '/appoint/set_package_user_info',
                    type: 'POST',
                    data: {
                        'package_deadline': package_deadline, 'user_total': user_total,
                        'user_buy': user_buy, 'packageid': packageid
                    },
                    dataType: 'json',
                    success:   function(result){
                        BootstrapDialog.alert(result['info']);
                        if (result['ret'] == 0) {
			                dialog.close();
                        }
                    }
                });
            }
        });
    };

    $('#opt-package-list').on('click', '.opt-package-price', function(){
        var packageid    = $(this).parent().data('packageid');
        dp_get_package_price(packageid);
    });

    var dp_get_package_price = function(packageid) {
        $.getJSON('/appoint/get_package_price', {
            'packageid': packageid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
               dp_set_package_price(packageid, result['package_info']);
            }
        });
    };
    var dp_set_package_price = function(packageid, package_info){
        var id_original_price = $("<input>");
        var id_package_start  = $("<input>");
        var id_package_end    = $("<input>");
        var id_current_price  = $("<input>");
        var id_effect_start   = $("<input>");
        var id_effect_end     = $("<input>");

        var arr                = [
            [ "原始价格",      id_original_price ] ,
            [ "有效起始时间",  id_package_start ] ,
            [ "有效结束时间",  id_package_end ] ,
            [ "活动价格",      id_current_price ] ,
            [ "活动开始时间",  id_effect_start ] ,
            [ "活动结束时间",  id_effect_end ] ,
        ];

        id_original_price.val( package_info['original_price']);
        id_package_start.val( package_info['package_start']);
        id_package_end.val( package_info['package_end']);
        id_current_price.val( package_info['current_price']);
        id_effect_start.val( package_info['effect_start']);
        id_effect_end.val( package_info['effect_end']);

        id_package_start.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
        });
        id_package_end.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
        });
        id_effect_start.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
        });
        id_effect_end.datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
        });


        show_key_value_table("新增小班课", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var original_price = id_original_price.val();
                var package_start  = id_package_start.val();
                var package_end    = id_package_end.val();
                var current_price  = id_current_price.val();
                var effect_start   = id_effect_start.val();
                var effect_end     = id_effect_end.val();
                if (!package_start || !package_end || !effect_start || !effect_end) {
                    BootstrapDialog.alert('请检查时间是否已经全部输入');
                    return;
                }

                if (parseInt(original_price) <= 0 || isNaN(parseInt(original_price)) ||
                    parseInt(original_price) <= 0 || isNaN(parseInt(original_price)) ) {
                    BootstrapDialog.alert('请检查价格');
                    return;
                }


                $.ajax({
                    url: '/appoint/set_package_price',
                    type: 'POST',
                    data: {
                        'original_price': original_price, 'package_start': package_start, 'package_end': package_end,
                        'current_price': current_price, 'effect_start': effect_start, 'effect_end': effect_end,
                        'packageid': packageid
                    },
                    dataType: 'json',
                    success:   function(result){
                        BootstrapDialog.alert(result['info']);
                        if (result['ret'] == 0) {
			                dialog.close();
                        }
                    }
                });
            }
        });
    };


    $('#opt-package-list').on('click', '.opt-package-tag-type', function(){
        var packageid   = $(this).parent().data('packageid');
        var id_tag_type = $("<select> "
                          +"<option value =0>无 </option>"
                          +"<option value =1>折扣 </option>"
                          +"<option value =2>推荐</option>"
                          +"<option value =3>热门</option>"
                          +" </select>");
        var arr = [
            [ "标签",  id_tag_type]
        ];

        show_key_value_table("设置标签", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax ("/appoint/set_tag_type",{
                    'packageid': packageid,
                    'tag_type': id_tag_type.val() 
                },function(ret){
                    alert("succ");
                });
            }
        });
    });

    $('#opt-package-list').on('click', '.opt-package-delete', function(){
        var packageid = $(this).parent().data('packageid');
        
        BootstrapDialog.show({
	        title   : '删除当前课程包',
	        message : '删除当前课程包',
	        buttons : [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    $.getJSON('/appoint/del_package_record', {
                        'packageid': packageid
                    }, function(result){
                        BootstrapDialog.alert(result['info']);
                    });
                    
			        dialog.close();
		        }
	        }]
        });
    });
    
    $('#opt-package-list').on('click', '.opt-package-class', function(){
        var packageid = $(this).parent().data('packageid');
        dp_get_package_class(packageid);
    });

    var dp_get_package_class = function(packageid){
        $.getJSON('/appoint/get_small_class', {
            'packageid': packageid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
                dp_set_package_class(packageid, result['sc_list']);       
            }
        });
    };

    var dp_set_package_class = function(packageid, sc_list){
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-package-sc'));
        var sc_str    = '';
        if (sc_list != null && sc_list.length != 0) {
            for(var i=0; i<sc_list.length; i++) {
                sc_str += '<tr><td>小班课</td>'+
                    '<td class="sc sc_confirmed">'+sc_list[i]+'</td><td>'+
                    '<button class="btn btn-warning fa fa-close form-control delete_current_sc " ></button>' +
                    '</td></tr>';
            }
        }
        html_node.find('.package_small_class').append(sc_str);

        BootstrapDialog.show({
	        title: "设置课程包大纲",
	        message : function(dialog){
                html_node.find('.package_small_class').on('click',  '.add_package_sc', function(){
                    $(this).parents('tr').after('<tr><td>小班课</td>'+
                            '<td class="sc"><input type="text" readonly="readonly" class="form-control" /></td><td>'+
                            '<button class="btn btn-warning fa fa-check form-control confirm_current_sc" ></button>' +
                            '</td></tr>');
                });

                html_node.find('.package_small_class').on('click',  'input', function(){
                    $(this).addClass('time_to_choose_sc');
                    show_current_small_class();
                });

                html_node.find('.package_small_class').on('click', '.confirm_current_sc', function(){
                    var package_tag = $(this).parents('tr').children('.sc').children('input').val();
                    $(this).parents('tr').children('.sc').empty();
                    $(this).parents('tr').children('.sc').text(package_tag);
                    $(this).parents('tr').children('.sc').addClass('sc_confirmed');
                    $(this).addClass('delete_current_sc');
                    $(this).addClass('fa-close');
                    $(this).removeClass('confirm_current_sc');
                    $(this).removeClass('fa-check');
                });

                html_node.find('.package_small_class').on('click', '.delete_current_sc', function(){
                    $(this).parents('tr').remove();
                });

                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var package_sc = '';
                    html_node.find('.sc_confirmed').each(function(){
                        package_sc += $(this).text() + ',';
                    });

                    $.getJSON('/appoint/set_small_class', {
                        'packageid': packageid, 'small_classes': package_sc
                    }, function(result){
                        BootstrapDialog.alert(result['info']);
                    });

			        dialog.close();
		        }
	        }]
        });

    };

    var show_current_small_class = function(){
        $.getJSON('/appoint/get_small_classes', {
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
                return;
            }
            
            var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-choose-small-class'));
            if (result['sc_list'].length != 0) {
                for(var i=0; i<result['sc_list'].length; i++) {
                    var sc_str = '<tr class="sc_record"><td class="courseid">'+
                            result['sc_list'][i]['courseid'] + '</td><td>'+
                            result['sc_list'][i]['course_name'] + '</td><td>'+
                            result['sc_list'][i]['teacher_nick'] + '</td><td>'+
                            result['sc_list'][i]['lesson_time'] + '</td><td>'+
                            result['sc_list'][i]['lesson_open'] + '</td><td>'+
                            '</td></tr>';
                    html_node.find(".small_class_list").append(sc_str);
                }
            }

            BootstrapDialog.show({
	            title: "选择小班",
	            message : function(dialog) {
                    html_node.find('.small_class_list').on('click', '.sc_record', function(){
                        html_node.find('.sc_record').removeClass('warning');
                        $(this).addClass('warning');
                    });

                    return html_node;
                },
	            buttons: [{
		            label: '返回',
		            action: function(dialog) {
			            dialog.close();
		            }
	            }, {
		            label: '确认',
		            cssClass: 'btn-warning',
		            action: function(dialog) {
                        var courseid = html_node.find('.warning').children('.courseid').text();
                        $('body').find('.time_to_choose_sc').val(courseid);
                        $('body').find('.time_to_choose_sc').removeClass('time_to_choose_sc');
			            dialog.close();
		            }
	            }]
            });

        });
    };

    $('#opt-package-list').on('click', '.opt-package-edit', function(){
        var packageid    = $(this).parent().data('packageid');

        dp_get_package_simple_info(packageid);
    });

    var dp_get_package_simple_info = function(packageid) {
        $.getJSON('/appoint/get_package_simple_info', {
            'packageid': packageid
        }, function(result){
            if (result['ret'] == 0 ) {
                dp_set_package_simple_info(packageid, result['package_info']);
            } else {
                BootstrapDialog.alert(result['info']);
            }
        });
    };

    var set_tag_color = function(tag_color) {
        
        var $tag_color = $('<div></div>').append('<option value="#F5A623" style="font-color:#F5A623">橙色</option>'+
            '<option value="#61BF57" style="font-color:#61BF57">绿色</option>'+
            '<option value="#0BCEFF" style="font-color:#0BCEFF">蓝色</option>'+
            '<option value="#FF3451" style="font-color:#FF3451">红色</option>');
        $tag_color.find('option').each(function(){
            if ($(this).val() == tag_color) {
                $(this).attr('selected', 'selected');
            }
        });
        return $tag_color.html();
    };

    var set_grade_selected = function(grade){
        var $grade = $('<div></div>').append('<input type="checkbox" value="101">小一'+
                    '<input type="checkbox" value="102">小二'+
                    '<input type="checkbox" value="103">小三'+
                    '<input type="checkbox" value="104">小四'+
                    '<input type="checkbox" value="105">小五'+
                    '<input type="checkbox" value="106">小六'+
                    '<br>'+
                    '<input type="checkbox" value="201">初一'+
                    '<input type="checkbox" value="202">初二'+
                    '<input type="checkbox" value="203">初三'+
                    '<input type="checkbox" value="301">高一'+
                    '<input type="checkbox" value="302">高二'+
                    '<input type="checkbox" value="303">高三');

        $grade.find('input').each(function(){
            for(var i =0; i<grade.length; i++) {
                if ($(this).val() == grade[i]) {
                    $(this).attr('checked', 'checked');
                }
            }
        });

        return $grade.html();
    };

    function dp_set_package_simple_info(packageid, package_info) {
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-package-simple-info'));
        if (package_info != undefined) {
            html_node.find('.package_name').val(package_info['package_name']);
            html_node.find('.package_intro').val(package_info['package_intro']);
            html_node.find('.package_target').val(package_info['package_target']);
            html_node.find('.suit_student').val(package_info['suit_student']);
            html_node.find('.subject').val(package_info['subject']);
            html_node.find('.lesson_total').val(package_info['lesson_total']);
            html_node.find('.grade').html(set_grade_selected(package_info['grade']) );
            if (package_info['package_tags'] != null) {
                var tag_str = '';
                for(var i=0; i<package_info['package_tags'].length; i++) {
                    tag_str = '<tr><td></td><td class="tag tags_confirmed">' +
                        package_info['package_tags'][i][0] +'</td>'+
                        '<td class="tag_color"><select class="form-control package_tag_color" >'+
                        set_tag_color(package_info['package_tags'][i][1]) + 
                        '</select></td><td>' +
                        '<button class="btn btn-warning fa fa-close form-control delete_package_tag" ></button>' +
                        '</td></tr>';
                    html_node.find('.package_info_edit').append(tag_str);
                }
            }
        }

        BootstrapDialog.show({
	        title: "修改基本信息",
	        message :  function(dialog) {
                html_node.find('.package_info_edit').on('click', '.add_package_tag', function(){
                    $(this).parents('tr').after('<tr><td></td><td class="tag">' +
                                                '<input class="form-control" type="text" />' +
                                                '</td><td class="tag_color"><select class="form-control package_tag_color" >'+
                                                '<option value="#F5A623" style="font-color:#F5A623">橙色</option>'+
                                                '<option value="#61BF57" style="font-color:#61BF57">绿色</option>'+
                                                '<option value="#0BCEFF" style="font-color:#0BCEFF">蓝色</option>'+
                                                '<option value="#FF3451" style="font-color:#FF3451">红色</option>' +
                                                '</select></td><td>' +
                                                '<button class="btn btn-warning fa fa-check form-control confirm_package_tag" ></button>' +
                                                '</td></tr>');
                });

                html_node.find('.package_info_edit').on('click', '.confirm_package_tag', function(){
                    var package_tag = $(this).parents('tr').children('.tag').children('input').val();
                    $(this).parents('tr').children('.tag').empty();
                    $(this).parents('tr').children('.tag').text(package_tag);
                    $(this).parents('tr').children('.tag').addClass('tags_confirmed');

                    $(this).addClass('delete_package_tag');
                    $(this).addClass('fa-close');
                    $(this).removeClass('confirm_package_tag');
                    $(this).removeClass('fa-check');
                });

                html_node.find('.package_info_edit').on('click', '.delete_package_tag', function(){
                    $(this).parents('tr').remove();
                });

                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var package_name   = html_node.find('.package_name').val();
                    var package_intro  = html_node.find('.package_intro').val();
                    var package_target = html_node.find('.package_target').val();
                    var suit_student   = html_node.find('.suit_student').val();
                    var subject        = html_node.find('.subject').val();
                    var lesson_total   = html_node.find('.lesson_total').val();
                    var grade = '';
                    html_node.find('.grade').children('input:checked').each(function(){
                        grade += $(this).val() + ',';
                    });

                    var package_tags  = '';
                    html_node.find('.tags_confirmed').each(function(){
                        package_tags += $(this).text() + '|' +
                            $(this).siblings('.tag_color').children('.package_tag_color').val() + ',';
                    });

                    if (!package_name || !package_intro || !suit_student || !subject
                        || !lesson_total || !grade || !package_tags || !package_target) {
                        BootstrapDialog.alert('请检查所有参数后提交');
                        return;
                    }
                    
                    $.getJSON('/appoint/set_package_simple_info', {
                        'packageid'      : packageid,
                        'package_name'   : package_name,
                        'package_intro'  : package_intro,
                        'suit_student'   : suit_student,
                        'subject'        : subject,
                        'lesson_total'   : lesson_total,
                        'grade'          : grade,
                        'package_tags'   : package_tags,
                        'package_target' : package_target
                    }, function(result){
                        BootstrapDialog.alert(result['info']);
                    });

			        dialog.close();
		        }
	        }]
        });
    };

    $('#opt-package-list').on('click', '.opt-package-intro', function(){
        var packageid    = $(this).parent().data('packageid');
        dp_get_package_feature(packageid);
    });

    var dp_get_package_feature = function(packageid){
        $.getJSON('/appoint/get_package_feature', {
            'packageid': packageid
        }, function(result) {
            dp_set_package_feature(packageid, result['package_info']);
        });
    };

    var dp_set_package_feature = function(packageid, package_info) {

        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-package-feature'));

        var package_feature_str = '';
        if (package_info['package_feature'].length != 0) {
            for(var i=0; i<package_info['package_feature'].length; i++) {
                package_feature_str += '<tr><td>课次内容</td>'+
                    '<td class="feature feature_confirmed">'+package_info['package_feature'][i]+'</td><td>'+
                    '<button class="btn btn-warning fa fa-close form-control delete_current_feature " ></button>' +
                    '</td><td></td></tr>';
            }
        }
        html_node.find('.add_package_feature').parents('tr').after(package_feature_str);

        var package_teachers_str = '';
        var set_current_teacher_tag = function(tag_name) {
            var $tags = $('<div></div>').append('<option value="HG">黄冈</option>'+
                '<option value="GX">公校</option>'+
                '<option value="JG">机构</option>'+
                '<option value="LEO">理优</option>'+
                '<option value="TEACH">名师</option>');
            $tags.find('option').each(function(){
                if ($(this).val() == tag_name ) {
                    $(this).attr('selected', 'selected');
                }
            });
            return $tags.html();
        };
        if (package_info['package_teachers'].length != 0 && package_info['package_teachers'][0].length != 0) {

            for (var j=0; j<package_info['package_teachers'].length; j++) {
                package_teachers_str += '<tr class="teacher_info"><td>添加老师</td><td>'+
                    '<input type="text" class="form-control teacherid opt-select-teacher" readonly="readonly" value="'+
                    package_info['package_teachers'][j][0]+'" />'+
                    '</td><td><select class="form-control teacher_tag" >'+
                    set_current_teacher_tag(package_info['package_teachers'][j][1]) +
                    '</select></td><td>'+
                    '<button class="btn btn-warning fa fa-close form-control delete_current_teacher " ></button>' +
                    '</td></tr>';

            }
        }

        html_node.find('.add_package_teacher').parents('tr').after(package_teachers_str);

        BootstrapDialog.show({
	        title: "设置简介",
	        message : function(dialog) {
                html_node.find('.package_feature_info').on('click',  '.add_package_feature', function(){
                    $(this).parents('tr').after('<tr><td>课程特色标签</td>'+
                            '<td class="feature"><input type="text" class="form-control" /></td><td>'+
                            '<button class="btn btn-warning fa fa-check form-control confirm_current_feature" ></button>' +
                            '</td><td></td></tr>');
                });

                html_node.find('.package_feature_info').on('click', '.confirm_current_feature', function(){
                    var package_feature = $(this).parents('tr').children('.feature').children('input').val();
                    $(this).parents('tr').children('.feature').empty();
                    $(this).parents('tr').children('.feature').text(package_feature);
                    $(this).parents('tr').children('.feature').addClass('feature_confirmed');
                    $(this).addClass('delete_current_feature');
                    $(this).addClass('fa-close');
                    $(this).removeClass('confirm_current_feature');
                    $(this).removeClass('fa-check');
                });

                html_node.find('.package_feature_info').on('click', '.delete_current_feature', function(){
                    $(this).parents('tr').remove();
                });

                html_node.find('.package_feature_info').on('click', '.add_package_teacher', function(){
                    $(this).parents('tr').after('<tr class="teacher_info"><td>添加老师</td><td>'+
                                                '<input type="text" class="form-control teacherid opt-select-teacher" readonly="readonly" />'+
                                                '</td><td><select class="form-control teacher_tag" >'+
                                                '<option value="HG">黄冈</option>'+
                                                '<option value="GX">公校</option>'+
                                                '<option value="JG">机构</option>'+
                                                '<option value="LEO">理优</option>'+
                                                '<option value="TEACH">名师</option>'+
                                                '</select></td><td>'+
                                                '<button class="btn btn-warning fa fa-close form-control delete_current_teacher " ></button>' +
                                                '</td></tr>');
                });
                html_node.find('.package_feature_info').on('click', '.delete_current_teacher', function(){
                    $(this).parents('tr').remove();
                });

	            html_node.find('.package_feature_info').on('click', '.opt-select-teacher',function(){
                    $(this).admin_select_user({
                        "type":"teacher",
                        "show_select_flag":true
                    });
                });

                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    
                    var package_feature  = '';
                    html_node.find('.feature_confirmed').each(function(){
                        package_feature += $(this).text() + ',';
                    });
                    var package_teachers = '';
                    html_node.find('.teacher_info').each(function(){
                        package_teachers += $(this).find('.teacherid').val() +
                            '|' + $(this).find('.teacher_tag').val() + ',';
                    });

                    $.getJSON('/appoint/set_package_feature', {
                        'packageid': packageid, 'package_feature': package_feature,
                        'package_teachers': package_teachers
                    }, function(result){
                       BootstrapDialog.alert(result['info']);
                    });
			        dialog.close();
		        }
	        }]
        });
        
    };

    $('#opt-package-list').on('click', '.opt-package-outline', function(){
        var packageid    = $(this).parent().data('packageid');
        var package_type = $(this).parent().data('type');

        dp_get_package_outline(packageid, package_type);
    });

    var dp_get_package_outline = function(packageid, package_type) {
        $.getJSON('/appoint/get_package_outline', {
            'packageid': packageid
        }, function(result){
            if (result['ret'] == 0) {
                dp_set_package_outline(packageid, package_type, result['package_outline']);
            } else {
                dp_set_package_outline(packageid, package_type);
            }
        });
    };

    function dp_set_package_outline(packageid, package_type, package_outlines) {

        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-package-outline'));
        var outline_str = '';
        if (package_outlines.length != 0) {
            for (var i=0; i<package_outlines.length; i++) {
                outline_str += '<tr><td>课次内容</td>'+
                            '<td class="outline outline_confirmed">'+package_outlines[i]+'</td><td>'+
                            '<button class="btn btn-warning fa fa-close form-control delete_current_outline " ></button>' +
                    '</td></tr>';
            }
        }
        html_node.find('.package_outline_set').append(outline_str);

        BootstrapDialog.show({
	        title: "设置课程包大纲",
	        message : function(dialog){
                html_node.find('.package_outline_set').on('click',  '.add_package_outline', function(){
                    $(this).parents('tr').after('<tr><td>课次内容</td>'+
                        '<td class="outline"><input type="text" class="form-control" /></td><td>'+
                        '<button class="btn btn-warning fa fa-check form-control confirm_current_outline" ></button>' +
                        '</td></tr>');
                });

                html_node.find('.package_outline_set').on('click', '.confirm_current_outline', function(){
                    var package_tag = $(this).parents('tr').children('.outline').children('input').val();
                    $(this).parents('tr').children('.outline').empty();
                    $(this).parents('tr').children('.outline').text(package_tag);
                    $(this).parents('tr').children('.outline').addClass('outline_confirmed');
                    $(this).addClass('delete_current_outline');
                    $(this).addClass('fa-close');
                    $(this).removeClass('confirm_current_outline');
                    $(this).removeClass('fa-check');
                });

                html_node.find('.package_outline_set').on('click', '.delete_current_outline', function(){
                    $(this).parents('tr').remove();
                });

                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var package_outline = '';
                    html_node.find('.outline_confirmed').each(function(){
                        package_outline += $(this).text() + ',';
                    });

                    $.getJSON('/appoint/set_package_outline', {
                        'packageid': packageid, 'package_outline': package_outline
                    }, function(result){
                        BootstrapDialog.alert(result['info']);
                    });
			        dialog.close();
		        }
	        }]
        });
    };

    $('#opt-package-list').on('click', '.opt-package-pic', function(){
        var packageid    = $(this).parent().data('packageid');
        dp_get_package_pic(packageid);
    });

    var dp_get_package_pic = function(packageid){
        $.getJSON('/appoint/get_package_pic', {
            'packageid': packageid
        }, function(result){
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            } else {
                dp_set_package_pic(packageid, result['package_pic']);
            }
        });
    };
    var dp_set_package_pic = function(packageid, package_pic) {

        var html_node= $('<div></div>').html(dlg_get_html_by_class('dlg_modify_package_pic'));
        html_node.find('.package_pic').attr('src', package_pic);
        html_node.find('.upload_package_pic').attr('id', 'opt-upload-package-pic');
        html_node.find('.upload_package_pic').parent().attr('id', 'opt-upload-package-pic-parent');

        BootstrapDialog.show({
	        title: "更改课程包图片",
	        message : function(dialog){

                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    var package_pic = html_node.find('.package_pic').attr('src');
                    $.getJSON('/appoint/set_package_pic', {
                        'packageid': packageid, 'package_pic': package_pic
                    }, function(result){
                        BootstrapDialog.alert(result['info']);
                    });
			        dialog.close();
		        }
	        }]
        });
        var th = setTimeout(function(){
            custom_upload('opt-upload-package-pic', 'opt-upload-package-pic-parent', g_args.qiniu_upload_domain_url, set_modify_package_pic);  
            clearTimeout(th);
        }, 1000);
    };

    var set_modify_package_pic = function(domain, info) {
        var res = $.parseJSON(info);
        alert(res.key);
        $(".bootstrap-dialog-body .package_pic").attr('src', domain + res.key);
    };

    $('#opt-add-new-course').on('click', function(){
        var html_node = $("<div></div>").html(dlg_get_html_by_class('dlg-add-new-course'));
        html_node.find('.opt-time-picker').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
        });
        html_node.find('span').css('width', '150px');
        BootstrapDialog.show({
	        title: "新增课程包",
	        message : function(dialog) {
                html_node.find('.package_info_add').on('click', '.add_package_tag', function(){
                    $(this).parents('tr').after('<tr><td></td><td class="tag">' +
                            '<input class="form-control" type="text" />' +
                            '</td><td>' +
                            '<button class="btn btn-warning fa fa-check form-control confirm_package_tag" ></button>' +
                            '</td></tr>');
                });

                html_node.find('.package_info_add').on('click', '.confirm_package_tag', function(){
                    var package_tag = $(this).parents('tr').children('.tag').children('input').val();
                    $(this).parents('tr').children('.tag').empty();
                    $(this).parents('tr').children('.tag').text(package_tag);
                    $(this).parents('tr').children('.tag').addClass('tags_confirmed');
                    $(this).addClass('delete_package_tag');
                    $(this).addClass('fa-close');
                    $(this).removeClass('confirm_package_tag');
                    $(this).removeClass('fa-check');
                });

                html_node.find('.package_info_add').on('click', '.delete_package_tag', function(){
                    $(this).parents('tr').remove();
                });

                return html_node;
            },
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {

                    var package_name = html_node.find('.package_name').val();
                    var package_type = html_node.find('.package_type').val();
                    var package_tags = '';
                    html_node.find('.tags_confirmed').each(function(){
                        package_tags += $(this).text() + ',';
                    });

                    $.ajax({
                        url: '/appoint/add_course',
                        type:'POST',
                        data: {
                            'package_name': package_name, 'package_type': package_type,
                            'package_tags': package_tags 
                        },
                        dataType: 'json',
                        success: function(result) {
                            BootstrapDialog.alert(result['info']);
                        }
                    });
                    
			        dialog.close();
		        }
	        }]
        });
    });

    var custom_upload = function(btn_id, containerid, domain, compelete_func){
        var uploader = Qiniu.uploader({
		    runtimes: 'html5, flash, html4',
		    browse_button: btn_id , //choose files id
		    uptoken_url: '/upload/pub_token',
		    domain: domain,
		    container: containerid,
		    drop_element: containerid,
		    max_file_size: '30mb',
		    dragdrop: true,
		    flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
		    chunk_size: '4mb',
		    unique_names: false,
		    save_key: false,
		    auto_start: true,
		    init: {
			    'FilesAdded': function(up, files) {
				    plupload.each(files, function(file) {
                        var progress = new FileProgress(file, 'process_info');
                        console.log('waiting...');
                    });
			    },
			    'BeforeUpload': function(up, file) {
				    console.log('before uplaod the file');
			    },
			    'UploadProgress': function(up,file) {
				    var progress = new FileProgress(file, 'process_info');
                    progress.setProgress(file.percent + "%", up.total.bytesPerSec, btn_id);
				    console.log('upload progress');
			    },
			    'UploadComplete': function() {
                    // $("#"+btn_id).siblings('div').remove();
				    console.log('success');
			    },
			    'FileUploaded' : function(up, file, info) {
				    console.log('Things below are from FileUploaded');
                    compelete_func(domain, info);
                    // var res = $.parseJSON(info);
                    // $(".bootstrap-dialog-body .gift_url").val(domain + res.key);
                    // $(".bootstrap-dialog-body .preview_gift_pic").attr("href", domain + res.key);
                    // set the key
			    },
			    'Error': function(up, err, errTip) {
				    console.log('Things below are from Error');
				    console.log(up);
				    console.log(err);
				    console.log(errTip);
			    },
			    'Key': function(up, file) {
                    console.log("Key start");
                    console.log(file);
                    var suffix = file.type.split('/').pop();
                    console.log(suffix);
                    console.log("Key end");
				    var key = "";
				    //generate the key
                    var time = (new Date()).valueOf();
				    return $.md5(file.name) +time+ "." + suffix;
			    }
		    }
	    });
        
    };

    function FileProgress(file, targetID)
    {
	    this.fileProgressID = file.id;
	    this.file = file;
	    var fileSize = plupload.formatSize(file.size).toUpperCase();
	    this.fileProgressWrapper = $('#' + this.fileProgressID); 

	    if (!this.fileProgressWrapper.length) {
	 	    $('#process_info').find('.process_in .pro_cover').css('width', 0 + '%');

	    }

	    this.setTimer(null);
    }

    FileProgress.prototype.setTimer = function(timer) {
        this.fileProgressWrapper.FP_TIMER = timer;
    };

    FileProgress.prototype.getTimer = function(timer) {
        return this.fileProgressWrapper.FP_TIMER || null;
    };

    FileProgress.prototype.setProgress = function(percentage, speed, upload_btn) {

        var file = this.file;
        var uploaded = file.loaded;

        var size = plupload.formatSize(uploaded).toUpperCase();
        var formatSpeed = plupload.formatSize(speed).toUpperCase();

        percentage = parseInt(percentage, 10);
        if (file.status !== plupload.DONE && percentage === 100) {
            percentage = 99;
        }

        $('#'+upload_btn).parents('.row').siblings().find('.upload_process_info').css('width', percentage + '%');

    };

    //adcc
    $("body").on("click",".opt-set_open_class", function(){
        var id_name         = $("<input> </input>") ;
        var id_enter_type   = obj_copy_node("#id_enter_type") ;
        var id_lesson_type  = obj_copy_node("#id_lesson_type") ;
        var id_tea_list     = obj_copy_node("#id_tea_list") ;
        var id_lesson_total = $("<input style=\"width:50px\" value=\"10\"> </input>");
        var id_stu_total    = $("<input> </input>") ;
        var id_subject_type = obj_copy_node("#id_subject_list") ;
        var id_grade_type   = obj_copy_node("#id_grade_list") ;
        var packageid       = $(this).parent().data('packageid');
        var arr             = [
            [ "名称",  id_name ] ,
            [ "受众",  id_enter_type ] ,
            [ "科目",  id_subject_type ] ,
            [ "年级",  id_grade_type ] ,
            [ "类型",  id_lesson_type ] ,
            [ "老师",  id_tea_list ] ,
            [ "总课次",  id_lesson_total] ,
            [ "课程人数",  id_stu_total] ,
        ];

        show_key_value_table("新增课程", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var course_name   = $.trim(id_name.val());
                var enter_type    = id_enter_type.val();
                var contract_type = id_lesson_type.val(); 
                var teacherid     = id_tea_list.val();
                var lesson_total  = id_lesson_total.val();
                var stu_total     = id_stu_total.val();
                var subject       = id_subject_type.val(); 
                var grade         = id_grade_type.val(); 

                if(course_name == "" || typeof enter_type == "undefined" || typeof contract_type == "undefined"
                  || lesson_total == "" || stu_total=="")
                {
                    console.log(course_name);
                    console.log(enter_type);
                    console.log(contract_type);
                    console.log(lesson_total);
                    alert("请输入全部信息");
                    return;
                }
                $.ajax({
                    url: '/tea_manage/add_open_course',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'course_name'   : course_name,
                        'enter_type'    : enter_type,
                        'contract_type' : contract_type,
                        'teacherid'     : teacherid,
                        'lesson_total'  : lesson_total,
                        'stu_total'     : stu_total, 
                        'grade'         : grade,
                        'packageid'     : packageid,
                        'subject'       : subject
			        },
                    success: function(data){
                        if(data.ret != -1){
                            window.location.href="/tea_manage/open_class2";
                        }else{
                            BootstrapDialog.alert(data.info);
                        }
                    }
                });
            }
        });
         
        id_lesson_type.on("change",function(){
            var lesson_type = (id_lesson_type.val());
            if(lesson_type == "4001"){
                id_tea_list.hide();
            }else{
                id_tea_list.show();
            }
        });
        
    });
    
    $('body').on('click','.opt-set_small_class', function(){
        var id_course_name  = $("<input>");
        var id_grade        = $("<select/>");
        var id_subject      = $("<select/>");
        var id_lesson_count = $("<input>");
        var id_stu_total    = $("<input>");
        var packageid       = $(this).parent().data('packageid');
        Enum_map.append_option_list("grade", id_grade,true);
        Enum_map.append_option_list("subject", id_subject,true);
        
        var arr = [
            [ "名称",  id_course_name] ,
            [ "年级",  id_grade] ,
            [ "科目",  id_subject] ,
            [ "课次",  id_lesson_count] ,
            [ "人数",  id_stu_total ] ,
        ];
        show_key_value_table("新增小班课", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var course_name  = id_course_name.val();
                var lesson_total = id_lesson_count.val();
                var grade        = id_grade.val();
                var subject      = id_subject.val();
                var stu_total    = id_stu_total.val();
                if (!course_name) {
                    BootstrapDialog.alert('课程名称不可以为空');
                    html_node.find('.course_name').addClass('warning');
                    return;
                }
                if (parseInt(lesson_total) <= 0 || isNaN(parseInt(lesson_total))) {
                    BootstrapDialog.alert('课次总数不可以为零');
                    return;
                }
                if (parseInt(stu_total) <= 0 || isNaN(parseInt(stu_total))) {
                    BootstrapDialog.alert('学生总数不可以为零');
                    return;
                }

                $.ajax({
                    url  : '/small_class/add_lesson_course',
                    type : 'POST',
                    data : {
                        'grade'        : grade,
                        'subject'      : subject,
                        'lesson_total' : lesson_total,
                        'course_name'  : course_name,
                        'packageid'    : packageid,
                        'stu_total'    : stu_total
                    },
                    dataType: 'json',
                    success:  function(data){
                        if(data.ret!=-1){
                            window.location.href="/small_class/index";
                        }else{
                            BootstrapDialog.alert(data.info);
                        }
                    }
                });
            }
        });
    });
});
