/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-resource_frame_new.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}

$(function(){

    var re_arr = [1,2,3,4,5,6,9];
    var get_next_info = function(obj){
        var info_str = obj.attr('info_str');
        var level = obj.attr('level');
        do_ajax( "/resource/get_next_tag",{
            'info_str' : info_str,
            'level'    : level,
        },function(ret){
            if(ret.ret==0){
                var ret_data = ret.data, add_str = '',select = ret.select;
                $.each(ret_data, function(i,val){
                    var new_info = info_str+'-'+val[select];
                    var next_level = parseInt(level)+1;
                    if(select == 'tag_two'){
                        var text_str = ChineseDistricts[86][ val['tag_two'] ];
                        var tr_str = get_add_tr(next_level, new_info, text_str, info_str, ret.is_end, val.is_ban,val.ban_level);
                    } else if (select == 'tag_three'){
                        var pro_num = info_str.slice(-6);
                        var text_str = ChineseDistricts[pro_num][ val['tag_three'] ];
                        var tr_str = get_add_tr(next_level, new_info, text_str, info_str, ret.is_end, val.is_ban,val.ban_level);
                    }else {
                        var tr_str = get_add_tr(next_level, new_info, val[select+'_str'], info_str, ret.is_end, val.is_ban,val.ban_level);
                    }
                    add_str = add_str + tr_str;
                });
                obj.after(add_str);

                $('tr[key]').each(function(){
                    if( $(this).attr('is_end') == 0){
                        $(this).css({color: "#3c8dbc", cursor:"pointer"});
                    }
                    if( $(this).attr('ban_level') > 0){
                        $(this).css('color', "#666");
                    }
                });
                $('[key]').contextify(options);
                $('.add_book').unbind();
                $('.add_book').on('click',function(){
                    if($(this).parent().attr('ban_level') > 0){
                        alert('请先启用上一级！');
                    } else {
                        add_book_new($(this).parent().attr('info_str'));
                    }
                    return false;
                });

            } else {
                alert(ret.info);
            }
        });
    }
    $('tr').css({color: "#3c8dbc", cursor:"pointer"});

    var tr_resource = 0,tr_level = 0,data_str = '',ban_level = 0;
    //右键自定义
    var options = {items:[
        {text: '启用', onclick: function() {
            $('#contextify-menu').hide();
            if(tr_level > ban_level && ban_level > 0){
                alert('请先启用上一级！');
            } else {
                ajax_submit(data_str,'use',null,'');
            }
        },class:'menu_use'},
        {text: '禁用',onclick: function() {
            $('#contextify-menu').hide();
            ajax_submit(data_str,'ban',null,'');
        },class:'menu_ban'},
        {text: '添加教材版本', onclick: function() {
            $('#contextify-menu').hide();
            if( ban_level > 0){
                alert('请先启用上一级！');
            }else {
                add_book_new(data_str);
            }
        },class:'menu_select hide'},
        {text: '删除', onclick: function() {
            $('#contextify-menu').hide();
            ajax_submit(data_str,'del',null,'');
        },class:'menu_del hide'},
    ],before:function(obj){
        data_str = $(obj).attr('info_str');
        ban_level = $(obj).attr('ban_level');
        tr_resource = parseInt(data_str);
        tr_level = $(obj).attr('level');
    },onshow:function(one){
        if($.inArray(tr_resource, re_arr) > -1 && tr_level == 4){
            $('.menu_select,.menu_del').removeClass('hide');
        }
        // if(is_ban == 0){
        //     $('.menu_use').text('启用√');
        // } else if(is_ban == 1) {
        //     $('.menu_ban').text('禁用√');
        // }
    }};

    var menu_hide = function(){
        $('#contextify-menu').hide();
        return $('#contextify-menu');
    };

    $('body').click(function(){
        menu_hide();
    });



    var get_add_tr = function(level, info_str, td_text, key, is_end, is_ban, ban_level){
        var tr_str = '<tr level='+level+' info_str='+info_str+' key='+key+' is_end='+is_end+' is_ban='+is_ban+' ban_level='+ban_level+'>';
        var resource = parseInt( key );
        if( ($.inArray(resource, re_arr) > -1) && level == 3){
            for(var i=0;i<8;i++){
                if( i==(level-1) ){
                    tr_str = tr_str+'<td>'+td_text+'</td>';
                } else if(i==level){
                    tr_str = tr_str+'<td class="add_book">添加教材</td>';
                } else {
                    tr_str = tr_str+'<td></td>';
                }
            }
        } else {
            for(var i=0;i<8;i++){
                if( i==(level-1) ){
                    tr_str = tr_str+'<td>'+td_text+'</td>';
                } else {
                    tr_str = tr_str+'<td></td>';
                }
            }

        }
        tr_str = tr_str + '</tr>';
        return tr_str;
    }

    $('table').on('click','tr',function(){
        if($(this).attr('is_end') != 1){
            if( $(this).hasClass('get_mark') ){
                var hide_info = $(this).attr('info_str');
                if( $(this).hasClass('hide_mark') ){
                    $(this).removeClass('hide_mark');
                    $('tr[key='+hide_info+']').show();
                } else {
                    $(this).addClass('hide_mark');
                    $('tr[info_str^='+hide_info+'-]').hide();
                }
            } else {
                $(this).addClass('get_mark')
                get_next_info($(this));
            }
        }
    });

    var add_book_new = function(info_str,do_type){
        var id_book = $("<input style='width:90%' id='id_book' />");

        var id_resource = $("<input style='width:90%' id='id_resource_type' />");

        var arr= [
            ["选择资源类型：", id_resource],
            ["添加教材版本：", id_book],
        ];

        $.show_key_value_table('添加教材版本', arr,{
            label    : '确认',
            cssClass : 'btn-info',
            action   : function() {
                var resource = id_resource.attr('resource');
                if(!resource){
                    alert('请选择资源类型!');
                    return false;
                }

                var book = id_book.val();
                if(!book){
                    alert('请选择教材版本!');
                    return false;
                }

                var data = {
                    'info_str':info_str,
                    'book'  :book,
                    'resource':resource,
                };

                console.log(data);

                $.ajax({
                    type     : "post",
                    url      : "/resource/add_book_resource",
                    dataType : "json",
                    data : data,
                    success : function(result){
                        console.log(data);
                        if(result.ret == 0){                            
                            BootstrapDialog.alert("操作成功！");
                            window.location.reload();                                                  
                        } else {
                            alert(result.info);
                        }
                    }
                });

            }
        },function(){
            $("#id_resource_type").on("click",function(){
                $.do_ajax("/resource/get_resource_type",{},function(response){
                    var data_list   = [];
                    $.each( response.data.list,function(){
                        data_list.push([this['resource_id'], this["resource_type"] ]);                                               
                    });

                    $(this).admin_select_dlg({
                        header_list     : [ "id","名称" ],
                        data_list       : data_list,
                        multi_selection : true,
                        select_list     : [],
                        onChange        : function( select_list,dlg) {
                            var str = '';
                            var str_id = '';
                            $('#id_body .warning').each(function(){
                                str += $(this).find('td:eq(1)').text() + ',';
                                str_id += $(this).find('td:eq(0)').text() + ',';
                            })

                            str_id != '' ? str_id = str_id.substring(0,str_id.length-1) : '' ;
                            str != '' ? str = str.substring(0,str.length-1) : '' ;

                            id_resource.val(str);
                            id_resource.attr({'resource':str_id});
                        }
                    });
                }) ;

            })

            $.enum_multi_select_new( $('#id_book'), 'region_version', function(){});

        },false,800);

    };

    var ajax_submit = function(info_str,do_type,id_book,resource){
        var data = {
            'info_str':info_str,
            'region'  :id_book,
            'do_type' :do_type,
            'resource':resource,
        };
        if( do_type == 'add' && resource == '' ){
            BootstrapDialog.alert("请至少选择一个资源类型！");
            return false;
        }
        $.ajax({
            type     : "post",
            url      : "/resource/add_or_del_or_edit_new",
            dataType : "json",
            data : data,
            success : function(result){
                console.log(data);
                if(result.ret == 0){
                    if( result.status == 200 ){
                        BootstrapDialog.alert("操作成功！");
                        window.location.reload();                      
                    }

                    if( result.status == 201 ){
                       $("tr[info_str='"+info_str+"']").remove(); 
                    }

                } else {
                    alert(result.info);
                }
            }
        });
    };

    // var sss= '[';
    // $.each(ChineseDistricts[86],function(i,val){
    //         $.each(ChineseDistricts[i],function(a,value){
    //             sss = sss+ '['+i+','+a+'],';
    //         });
    // });
    // sss = sss+ ']';

    // $('.common-table').after(sss);


    $('.opt-change').set_input_change_event(load_data);
});
