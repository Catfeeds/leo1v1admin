interface GargsStatic {
	lessonid:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/show_lesson_video.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-show_lesson_video.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			lessonid:	$('#id_lessonid').val()
        });
    }


	$('#id_lessonid').val(g_args.lessonid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>
*/
