/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_money-show_teacher_bank_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
 			      teacherid:	$('#id_teacherid').val(),
            is_bank:    $("#id_is_bank").val()
        });
    }


	  $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"), "teacher",load_data);

    $('#id_is_bank').val(g_args.is_bank);

    $('#download_data').on("click", function(){
        var list_data = [
            ["老师id",'老师姓名','科目','手机号','持卡人','卡号','银行类型','开户省','开户市','支行','手机预留号','身份证','绑卡时间'],
        ];

        data = g_data.info;
        for (var i = 0; i < data.length; i ++) {
            data_line = [];
            data_line.push(data[i].teacherid);
            data_line.push(data[i].nick);
            data_line.push(data[i].subject_str);
            data_line.push(data[i].phone);
            data_line.push(data[i].back_account);
            data_line.push(data[i].bankcard + ' ');
            data_line.push(data[i].bank_type);
            data_line.push(data[i].bank_province);
            data_line.push(data[i].bank_city);
            data_line.push(data[i].bank_address);
            data_line.push(data[i].bank_phone);
            data_line.push(data[i].idcard + ' ');
            data_line.push(data[i].bind_bankcard_time_str);
            list_data.push(data_line);
        }

        $.do_ajax ( "/page_common/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            window.location.href= "/common_new/download_xls";
        });
    });



	  $('.opt-change').set_input_change_event(load_data);
    if(g_account=="sunny"){
        download_show();
    }

});
