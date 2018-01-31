/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/require1-get_resource_count.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		});
}
$(function(){

    $('#download_data').on("click", function(){
        var list_data = [ 
            ["文件名","科目","年级","教研员","浏览次数","使用次数"]
        ];

        data = g_data.info;
        for (var i = 0; i < data.length; i ++) {
            data_line = [];
            data_line.push(data[i].file_title);
            data_line.push(data[i].subject_str);
            data_line.push(data[i].grade_str);
            data_line.push(data[i].nick);
            data_line.push(data[i].visit_num);
            data_line.push(data[i].use_num);
            list_data.push(data_line);
        }

        $.do_ajax ( "/page_common/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            window.location.href= "/common_new/download_xls";
        });
    });




	$('.opt-change').set_input_change_event(load_data);
});
