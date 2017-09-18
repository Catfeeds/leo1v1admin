/// <时间 path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_boby-st.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      nick_phone:	$('#id_nick_phone').val(),
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
    var prev_phone = '';
    $('#id_nick_phone').keyup(function(){
        var cur_phone = $.trim( $(this).val() );
        if ( prev_phone != cur_phone & cur_phone != '') {
            prev_phone = cur_phone;
            $.ajax({
                url:'/test_boby/ajax',
                type: 'post',
			          dataType : "json",
                data: {'phone': cur_phone},
                success:function(ret){
                    $('#cur_ret').empty();
                    if (ret.length) {
                        var add_li = '';
                        for(var i=0; i<ret.length; i++) {
                            add_li = add_li+'<li>'+ret[i]+'</li>';
                        }
                        $('#cur_ret').append(add_li);
                        $('#cur_ret>li').css('cursor','pointer');
                        $('#cur_ret>li').css('border','1px solid #ccc');
                        $('#cur_ret>li').on('click', function() {
                            var phone = $(this).text();
                            $('#id_nick_phone').val(phone);
	                          load_data();
                        });
                    }
                },
            });
        }
        if(cur_phone == '') {
            $('#cur_ret').empty();
        }
    });

	  $('#id_nick_phone').val(g_args.nick_phone);
	  // $('.opt-change').set_input_change_event(load_data);
});



/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">nick_phone</span>
                <input class="opt-change form-control" id="id_nick_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>
*/
