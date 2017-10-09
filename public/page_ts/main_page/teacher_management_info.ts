/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-teacher_management_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $(".fa-download").on("click",function(){
        var list_data=[];
        var $tr_list=$(this).parent().parent().find("table").find("tr" );
        $.each($tr_list ,function(i,tr_item )  {
            var row_data= [];
            var $td_list= $(tr_item ).find("td");
            $.each(  $td_list, function( i, td_item)  {
                if ( i>0 && i< $td_list.length-1 ) {
                    row_data.push( $.trim( $(td_item).text()) );
                }
            });
            list_data.push(row_data);
        });


        $.do_ajax ( "/page_common/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            window.location.href= "/common_new/download_xls";
        });
        
    });


	$('.opt-change').set_input_change_event(load_data);
});

