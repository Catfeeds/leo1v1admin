/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_member_list.d.ts" />

$(function(){
    $(".common-table").tbody_scroll_table();

    function load_data(){
        $.reload_self_page ( {
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
    // $(".common-table").table_admin_level_4_init();
    //$(".common-table").table_admin_level_5_init();
    var whole_data = [];
    function load_row_data (){
        
        var row_list = $("#id_tbody .l-5");
        var row_all = $("#id_tbody tr") ;
        var do_index = 0;
        function do_one() {
            
            var className = $("#id_tbody tr:eq("+do_index+")") .attr('class');
          
            var arr_4 = new Array();
            var arr_3 = new Array();
            var arr_2 = new Array();
            var arr_1 = new Array();
            var arr_0 = new Array();
            whole_data[do_index] = {'level':className};
      
            if (do_index < row_all.length ){

                if( className == 'l-4' ){
                    arr_4.push(do_index)
                }
                if( className == 'l-3' ){
                    arr_3.push(do_index)
                }
                if( className == 'l-2' ){
                    arr_2.push(do_index)
                }
                if( className == 'l-1' ){
                    arr_1.push(do_index)
                }
                if( className == 'l-0' ){
                    arr_0.push(do_index)
                }

                if( className == 'l-5' ){
                    var $tr      = $(row_all[do_index]);
                    var opt_data = $tr.find(".opt-show").get_opt_data();
                    $.do_ajax("/seller_student_new2/seller_test_lesson_info",{
                        "adminid"    : opt_data.adminid,
                        "start_time" : g_args.start_time,
                        "end_time"   : g_args.end_time,
                    },function(data){
                        pullData($tr,data);
                        data['level'] = className;
                        whole_data[do_index] = data;
                        do_one();
                    });
                }
            }
           
            if(do_index == row_all.length){
              
                var strArr = {
                    'test_lesson_count':0,
                    'succ_all_count_for_month':0,
                    'suc_lesson_count_one':0,
                    'suc_lesson_count_two':0,
                    'suc_lesson_count_three':0,
                    'suc_lesson_count_four':0,
                    'fail_all_count_for_month':0
                };
                whole_data =  super_add(arr_4,whole_data,'l-5',strArr);
                whole_data =  super_add(arr_3,whole_data,'l-4',strArr);
                whole_data =  super_add(arr_2,whole_data,'l-3',strArr);
                whole_data =  super_add(arr_1,whole_data,'l-2',strArr);
                whole_data =  super_add(arr_0,whole_data,'l-1',strArr);
                console.log(whole_data);
            }
            do_index++;
        };
        do_one();
    };

    load_row_data ();

    function pullData(obj,data){
        obj.find(".test_lesson_count").text(data["test_lesson_count"]);
        obj.find(".succ_all_count_for_month").text(data["succ_all_count_for_month"]);
        obj.find(".suc_lesson_count_one").text(data["suc_lesson_count_one"]);
        obj.find(".suc_lesson_count_two").text(data["suc_lesson_count_two"]);
        obj.find(".suc_lesson_count_three").text(data["suc_lesson_count_three"]);
        obj.find(".suc_lesson_count_four").text(data["suc_lesson_count_four"]);
        obj.find(".fail_all_count_for_month").text(data["fail_all_count_for_month"]);
        obj.find(".lesson_per").text(data["lesson_per"]);
        obj.find(".kpi").text(data["kpi"]);
        obj.find(".order_per").text(data["order_per"]);
    }

    if(g_account=='龚隽' || g_account=='sherry'){
        download_show();
    }
    $('.opt-change').set_input_change_event(load_data);

    function super_add(arr_n,arr,lev,strArr){
         if(!arr_n || !arr){
            return arr;
        } 
        for( var x in arr_n){
           
            var first = arr_n[x] + 1;
            var end = arr_n.length - 1;
            if( arr_n[x] == arr_n[end] ){
                var last = arr.length - 1;
            }else{
                var last = arr_n[x+1] - 1;
            }

            if(first > last){
                arr[arr_n[x]] = strArr;
            }else if(first == last){
                arr[arr_n[x]] = arr[first];
            }else{
                for( var i = first; i <= last; i++ ){
                    if(arr[i].level == lev){
                        var obj = arr[i];
                        var superObj = {};
                        for( var y in strArr ){
                            superObj[y] += obj[y];
                        }
                        arr[arr_n[x]] = superObj;
                    }
                }
            }
            arr[arr_n[x]].level = lev;
                        
        }
        return arr;
    }
});
