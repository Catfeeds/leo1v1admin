/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-resource_frame_new.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}

$(function(){

    var re_arr = [1,2,3,4,5,9];
    var get_next_info = function(obj){
        var info_str = obj.attr('info_str');
        var level = obj.attr('level');
        do_ajax( "/resource/get_next_info_js",{
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
                        add_or_del_or_edit($(this).parent().attr('info_str'),'add');
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
                ajax_submit(data_str,'use');
            }
        },class:'menu_use'},
        {text: '禁用',onclick: function() {
            $('#contextify-menu').hide();
            ajax_submit(data_str,'ban');
        },class:'menu_ban'},
        {text: '选择教材版本', onclick: function() {
            $('#contextify-menu').hide();
            if( ban_level > 0){
                alert('请先启用上一级！');
            }else {
                add_or_del_or_edit(data_str,'add');
            }
        },class:'menu_select hide'},
        {text: '删除', onclick: function() {
            $('#contextify-menu').hide();
            ajax_submit(data_str,'del');
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
            for(var i=0;i<7;i++){
                if( i==(level-1) ){
                    tr_str = tr_str+'<td>'+td_text+'</td>';
                } else if(i==level){
                    tr_str = tr_str+'<td class="add_book">添加教材</td>';
                } else {
                    tr_str = tr_str+'<td></td>';
                }
            }
        } else {
            for(var i=0;i<7;i++){
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

    var add_or_del_or_edit = function(info_str,do_type){
        var id_book = $("<select />");
        Enum_map.append_option_list("region_version",id_book,true);
        // id_book.val(50000);
        var arr= [
            ["添加教材版本：", id_book],
        ];

        $.show_key_value_table('添加教材版本', arr,{
            label    : '确认',
            cssClass : 'btn-info',
            action   : function() {

                if(id_book.val() > 0){
                    ajax_submit(info_str,do_type,id_book.val());
                } else {
                    alert('请选择教材版本!');
                }

            }
        },function(){},600);

    };

    var ajax_submit = function(info_str,do_type,id_book){
        $.ajax({
            type     : "post",
            url      : "/resource/add_or_del_or_edit",
            dataType : "json",
            data : {
                'info_str' : info_str,
                'region'   : id_book,
                'do_type'  : do_type,
            } ,
            success : function(result){
                if(result.ret == 0){

                    BootstrapDialog.alert("操作成功！");
                    setTimeout(function(){
                        window.location.reload();
                    },1000);

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
