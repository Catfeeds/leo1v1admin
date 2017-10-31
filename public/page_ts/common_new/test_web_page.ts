/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/common_new-test_web_page.d.ts" />

function load_data(){
}
$(function(){
    $.do_ajax_t("/common_new/web_page_log",{
        "web_page_id" : g_args.web_page_id,
        "from_adminid" : g_args.from_adminid,
    });
});
