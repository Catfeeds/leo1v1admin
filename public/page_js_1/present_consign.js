$(function(){
    $('.start_time').val(g_default_start);
    $('.end_time').val(g_default_end);

    loadPresentList(getCond());
    $('.submit_info').on('click', function(){
        loadPresentList(getCond());
    });

    $('.user_book_list').on('click', '.opt-send-present', function(){
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg_send_present'));
        var exchangeid = $(this).parents('td').siblings('.exchangeid').text();

        $(this).addClass('current_operate_record');
        BootstrapDialog.show({
	        title: "发放已兑换物品",
	        message : function(dialog){
                html_node.find('.express_name').on('change', function(){
                    $(this).parents('.row').siblings('.custom_express').remove();
                    var express_value = $(this).val();
                    console.log(express_value);
                    if (express_value == 6) {
                        $(this).parents('.row').after('    <div class="row custom_express">'+
                                                      '        <div class="input-group">'+
                                                      '            <span class="input-group-addon">快递名称</span>'+
                                                      '            <input type="text" class="custom_express_name" >'+
                                                      '        </div>'+
                                                      '    </div>');
                    }
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
                    var express_value = html_node.find('.express_name').val();
                    var express_name = '';
                    if (express_value == 5) {
                        express_name = html_node.find('.custom_express_name').val();
                    } else {
                        express_name = html_node.find('.express_name option:selected').text();
                    }

                    var express_num = html_node.find('.express_num').val();

                    if (!express_name || !express_num) {
                        BootstrapDialog.alert('请认真的输完所有的数据好么 ...');
                        return;
                    }

                    $.ajax({
                        url: '/present/set_material_sent',
                        type: 'POST',
                        data: {
                            'exchangeid': exchangeid, 'express_value': express_value, 'express_name': express_name, 'express_num': express_num
                        },
                        dataType: 'json',
                        success: function(result){
                            if (result['ret'] == 0) {
                                var status = g_enum_map.gift_status.desc_map[1];
                                $('.current_operate_record').parents('td').siblings('.status').text(status);
                            }
                            $('.current_operate_record').removeClass('.current_operate_record');
                            BootstrapDialog.alert(result['info']);
                        }
                    });
			        dialog.close();
		        }
	        }]
        });

    });

    // 查看进度
    $('.user_book_list').on('click', '.opt-progress', function(){
        // TODO 
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg_send_progress'));
        var exchangeid = $(this).parents('td').siblings('.exchangeid').text();

        var express_num =  $(this).parents('td').siblings('.express_num').text();
        html_node.find('.prog_express_name').text( $(this).parents('td').siblings('.express_name').text());
        html_node.find('.prog_express_num').text( $(this).parents('td').siblings('.express_num').text());
        // http://apis.haoservice.com/lifeservice/exp?com=shunfeng&no=305980661496&key=ea01343a745e43d69959b86dd715b0f9

        // $.ajax({
        //     url: 'http://apis.haoservice.com/lifeservice/exp',
        //     type: 'GET',
        //     data: {
        //         'com': com, 'no': express_num, 'key':'ea01343a745e43d69959b86dd715b0f9' 
        //     },
        //     dataType: 'jsonp',
        //     success: function(result) {
        //         /*
        //          {"error_code":0,"reason":"成功","result":{"no":"305980661496","ischeck":true,"com":"shunfeng","company":"顺丰速运","updatetime":"2015-08-11 13:40:58","data":[{"time":"2015-05-11 21:03:33","context":"顺丰速运 已收取快件"},{"time":"2015-05-11 21:59:56","context":"快件到达 【青岛流亭集散中心】"},{"time":"2015-05-11 23:23:42","context":"快件正转运至 【青岛北安服务点】"},{"time":"2015-05-12 05:53:53","context":"快件到达 【青岛北安服务点】"},{"time":"2015-05-12 07:28:39","context":"正在派送途中,请您准备签收"},{"time":"2015-05-12 09:25:38","context":"已签收,感谢使用顺丰,期待再次为您服务"},{"time":"2015-05-12 09:25:59","context":"签收人是：已签收"}]}}
        //          */
        //         if (result['error_code'] == 0) {
        //             html_node.find('.prog_express_stat').text();
        //         } else {
        //             html_node.find('.prog_express_stat').text('查询出错');
        //         }
        //         BootstrapDialog.show({
	    //             title: '查看进度',
	    //             message : html_node,
	    //             buttons: [{
		//                 label: '返回',
		//                 action: function(dialog) {
		// 	                dialog.close();
		//                 }
	    //             }]
        //         });
        // }
        BootstrapDialog.show({
	        title: '查看进度',
	        message : html_node,
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }]
        });
    });



    // 确认收货
    $('.user_book_list').on('click', '.opt-confirm-present', function(){
        var exchangeid = $(this).parents('td').siblings('.exchangeid').text();

        $(this).addClass('current_operate_record');
        BootstrapDialog.show({
	        title: '确认收货',
	        message : '是否确认用户已经签收物品',
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    $.ajax({
                        url: '/present/set_material_finished',
                        type: 'POST',
                        data: {
                            'exchangeid': exchangeid
                        },
                        dataType: 'json',
                        success: function(result){
                            if (result['ret'] == 0) {
                                var status = g_enum_map.gift_status.desc_map[2];
                                $('.current_operate_record').parents('td').siblings('.status').text(status);
                            }

                            BootstrapDialog.alert(result['info']);
                            $('.current_operate_record').removeClass('current_operate_record');

                        }
                    });
			        dialog.close();
		        }
	        }]
        });

    });

    // 兑换礼品
    $('.user_book_list').on('click', '.opt-exchange-present', function(){
        var exchangeid = $(this).parents('td').siblings('.exchangeid').text();

        $(this).addClass('current_operate_record');

        BootstrapDialog.show({
	        title: '确认兑换礼品',
	        message : '是否确认已经为用户兑换礼品',
	        buttons: [{
		        label: '返回',
		        action: function(dialog) {
			        dialog.close();
		        }
	        }, {
		        label: '确认',
		        cssClass: 'btn-warning',
		        action: function(dialog) {
                    $.ajax({
                        url: '/present/set_virtual_finished',
                        type: 'POST',
                        data: {
                            'exchangeid': exchangeid
                        },
                        dataType: 'json',
                        success: function(result){
                            if (result['ret'] == 0) {
                                var status = g_enum_map.gift_status.desc_map[2];
                                $('.current_operate_record').parents('td').siblings('.status').text(status);
                            }
                            $('.current_operate_record').removeClass('current_operate_record');
                            BootstrapDialog.alert(result['info']);
                        }
                    });
			        dialog.close();
		        }
	        }]
        });

    });

});
var getCond = function() {
    var start_time  = $('.start_time').val();
    var end_time    = $('.end_time').val();
    var gift_type   = $('.gift_type').val();
    var gift_status = $('.gift_status').val();

    var cond = {
        'start_time'  : start_time,
        'end_time'    : end_time,
        'gift_type'   : gift_type,
        'gift_status' : gift_status
    };

    return cond;
};

var loadPresentList = function(cond) {
    $.ajax({
        url: '/present/get_consign_list',
        type: 'POST',
        data: cond,
        dataType: 'json',
        success: function(result) {
            if (result['ret'] != 0) {
                BootstrapDialog.alert(result['info']);
            }

            $('.present_list').empty();
            for(var i=0; i<result['gift_list'].length; i++) {
                $('.present_list').append(presentRecord(result['gift_list'][i]));
            }
        }
    });
};

var presentRecord = function(pr){
    var $optd = $('<td></td>');
    if (pr['gift_type'] == 1) {
        $optd.append('<a class="btn fa fa-plane opt-send-present" href="javascript:;" title="发放礼品" ></a>').
            append('<a class="btn fa fa-info opt-progress" href="javascript:;" title="查看进度" ></a>').
            append('<a class="btn fa fa-gavel opt-confirm-present" href="javascript:;" title="确认收货" ></a>');
    } else {
        $optd.append('<a class="btn fa fa-mobile-phone opt-exchange-present" href="javascript:;" title="充充充" ></a>');
    }

    var $prtr = $('<tr></tr>');

    $prtr.append('<td class="exchangeid">'+pr['exchangeid']+'</td>'); 
    $prtr.append('<td>'+pr['nick']+'</td>'); 
    $prtr.append('<td>'+pr['phone']+'</td>'); 
    $prtr.append('<td>'+pr['ecg_time']+'</td>'); 
    $prtr.append('<td>'+pr['gift_name']+'</td>'); 
    $prtr.append('<td style="display:none;">'+pr['gift_type']+'</td>'); 
    $prtr.append('<td>'+pr['ecg_acc']+'</td>'); 
    $prtr.append('<td>'+pr['ecg_addr']+'</td>'); 
    $prtr.append('<td>'+pr['ecg_consigee']+'</td>'); 
    $prtr.append('<td>'+pr['ecg_phone']+'</td>'); 
    $prtr.append('<td class="status">'+g_enum_map.gift_status.desc_map[pr['ecg_status']]+'</td>'); 
    $prtr.append('<td class="express_name" style="display:none;">'+pr['ecg_express_name']+'</td>'); 
    $prtr.append('<td class="express_num" style="display:none;">'+pr['ecg_express_num']+'</td>'); 
    $prtr.append($optd); 

    return $prtr;
};

var expressTimeLine = function(exInfo) {
    if (exInfo.length == 0) {
        return '';
    }


    /*
     [{"time":"2015-05-11 21:03:33","context":"顺丰速运 已收取快件"},{"time":"2015-05-11 21:59:56","context":"快件到达 【青岛流亭集散中心】"},{"time":"2015-05-11 23:23:42","context":"快件正转运至 【青岛北安服务点】"},{"time":"2015-05-12 05:53:53","context":"快件到达 【青岛北安服务点】"},{"time":"2015-05-12 07:28:39","context":"正在派送途中,请您准备签收"},{"time":"2015-05-12 09:25:38","context":"已签收,感谢使用顺丰,期待再次为您服务"},{"time":"2015-05-12 09:25:59","context":"签收人是：已签收"}]
     */
    var timeLine = '';
    for (i=0; i< exInfo.length; i++) {
        timeLine += '<tr><td>' + exInfo[i]['time']+'</td>' +
            '<td>'+exInfo[i]['context']+'</td></tr>';
    }

    return timeLine;
};











