// SWITCH-TO: ../../template/authority/show_power.html
$(function(){
    //查找功能
    $("#id_search_acc").on("click",function(){
        var acc_name = $.trim( $("#id_acc_name").val());
        if(acc_name== ""){
            alert("请输入所需查找信息！");
        }else{
		    var url = "/authority/get_acc_power_list?acc_name="+acc_name;
		    window.location.href = url;
        }
    });

    //查找信息回车
    $("#id_acc_name").on("keypress",function(e){
        if(e.keyCode == 13){
            var acc_name = $("#id_acc_name").val();
            if(acc_name== ""){
                alert("请输入所需查找信息！");
            }else{
		        var url = "/authority/get_acc_power_list?acc_name="+acc_name;
		        window.location.href = url;
            }
        }
    });

    //查找功能
    $("#id_search_power").on("click",function(){
        var power_name = $.trim($("#id_power_name").val());
        if(power_name== ""){
            alert("请输入所需查找信息！");
        }else{
		    var url = "/authority/get_acc_power_list?power_name="+power_name;
		    window.location.href = url;
        }
    });

    //查找信息回车
    $("#id_power_name").on("keypress",function(e){
        if(e.keyCode == 13){
            var power_name = $("#id_power_name").val();
            if(power_name== ""){
                alert("请输入所需查找信息！");
            }else{
		        var url = "/authority/get_acc_power_list?power_name="+power_name;
		        window.location.href = url;
            }
        }
    });
});
