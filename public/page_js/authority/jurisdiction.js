
$(function(){
    var cur_groupid=-1;
    $ (".opt-checkbox-power").on("click",function(){
        var $this=$(this);
        if($this.hasClass("danger")){
            $this.removeClass("danger");
            
        }else{
            $this.addClass("danger");
        }
    });

    $(".opt-select-group" ).on("click",function(){
        $(".opt-select-group" ).removeClass("danger");
        $(this ).addClass("danger");

        cur_groupid=$(this).data("groupid");
		$.ajax({
			type     :"post",
			url      :"/authority/group_auth",
			dataType :"json",
			data     :{'groupid':cur_groupid},
			success  : function(result){
                var show_list= $(".opt-checkbox-power" );
                show_list.removeClass("danger");
                $.each(result.power_list,function(i,powerid){
                    $.each(show_list,function(j,check_box) {
                        var $check_box=$(check_box);
                        if ($check_box.data("powerid")== powerid) {
                            $check_box.addClass("danger");
                            return false;
                        }
                        return true;
                    });
                    
                });

			}
		});
    });
    
    $(".opt-commit").on("click",function(){
        var groupid = $(this).data("groupid");
        var power_list=[];
        var show_list= $(".opt-checkbox-power" );
        $.each(show_list,function(j,check_box) {
            var $check_box=$(check_box);
            if ($check_box.hasClass("danger") ) {
                power_list.push( $check_box.data("powerid") );
            }
        });

        $.ajax({
			type     :"post",
			url      :"/authority/update_group_auth",
			dataType :"json",
			data     :{'groupid': cur_groupid ,'auth': power_list.join("," ) },
			success  : function(result){
                if (result.ret!=0){
                    alert(result.info);
                }else{
                    window.location.href= window.location.pathname +"?groupid="+ $(".danger.opt-select-group").data("groupid");
                }
			}
		});

    });

    $(".opt-select-group[data-groupid="+$.query.get("groupid") +"]" ).click();
    
});
