// SWITCH-TO:   ../../template/question/
$(function(){

    $("#datetime_start").val(g_args.start_time );
    $("#datetime_end").val(g_args.end_time );

    $(".tr-admin-date-info").hide();
    $(".opt-user-all" ).data("show",false);

    $(".opt-user-all" ).on("click",function(){
        var $this= $(this);
        var show_flag= ! $this.data("show" );

        $this.data("show",show_flag );
        var adminid=$this.data("adminid");
        var obj_list=$(".tr-admin-date-info[data-adminid="+adminid+"]");
        if (show_flag){
            obj_list.show();
        }else{
            obj_list.hide();
        }
    });


	$('#datetime_start,#datetime_end').datetimepicker({
		lang:'ch',
		timepicker:false,

		onChangeDateTime :function(){
            var start_time=$("#datetime_start").val();
            var end_time=$("#datetime_end").val();
            var url= window.location.pathname+ "?start_time="+start_time+"&end_time="+end_time;
		    window.location.href=url;
		},

		format:'Y-m-d'
	});

    /*
    $(".opt-post").each(function(){
        var nopass=$(this).prev().text();
        var pass=$(this).prev().prev().text();

        var all=$(this).prev().prev().prev().text();
        $(this).text(0+all-pass -nopass  );
    
    });
     */

});
