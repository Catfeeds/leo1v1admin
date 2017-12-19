// SWITCH-TO:   ../../template/al_common/
function table_init() {
    var thead=$("table  thead  ");

    $.each(thead, function(table_i,th_item){
        var path_list=window.location.pathname.split("/");
        var table_key=path_list[1]+"-"+path_list[2]+"-"+ table_i;
        var opt_td=$(th_item).find ("td:last");

        var config_item=$( " <a href=\"javascript:; \" class=\"btn btn-primary\">列配置</a>");
        config_item.on("click",function(){
            var $table= $(this).closest("table");
            var $th=$table.find("thead >tr");

            var $th_td_list= $th.find("td");
            var arr=[];
            $.each($th_td_list, function(i,item){
                if (!(i==0 || i== $th_td_list.length-1)) {
                    var $item=$(item);
                    var title= $.trim($item.text());
                    var display= $item.css("display");
                    var $input=$("<input type=\"checkbox\"/>");
                    if (display=="none") {
                        $input.attr("checked",false) ;
                    }else{
                        $input.attr("checked","checked") ;
                    }

                    $input.data("index",title);
                    arr.push([ title,  $input]);
                }
            });

            $.show_key_value_table("列显示配置", arr ,[{
                label: '默认',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    $.do_ajax("/user_deal/opt_table_field_list",{
                        "opt_type":"set",
                        "table_key":table_key,
                        "data":""
                    });
                    window.location.reload();
                }
            },{

                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var config_map={
                    };
                    $.each(arr, function(i,item){
                        var $input=item[1];
                        var index=$input.data("index");
                        var value=$input.prop("checked");
                        config_map[index]=value;
                    });
                    $.do_ajax("/user_deal/opt_table_field_list",{
                        "opt_type":"set",
                        "table_key":table_key,
                        "data":JSON.stringify(config_map)
                    });
                }
            }]);
        });
        opt_td.append(config_item);

    });


    var $table_list=$(".common-table") ;
    $.each($table_list, function(table_i){

        var path_list=window.location.pathname.split("/");
        var table_key=path_list[1]+"-"+path_list[2]+"-"+ table_i;
        var $table=$(this);
        if (!check_in_phone()) {
            var $div=$("<div class=\"table-responsive \"/>");
            $table.before($div);
            $div.append($table);
        }

        var reset_table = function(ret) {
                var config_map= ret.field_list;
                //处理 过滤选项
                if ( ret.filter_list) {
                    $( "aside  >section >.row >div  " ).hide();
                    $.each( ret.filter_list ,function(id,id_name){
                        $("#"+id_name).parent().parent().show();
                    });
                }
                if ( ret.hide_filter_list) {
                    $.each( ret.hide_filter_list ,function(id,id_name){
                        $("#"+id_name).parent().parent().hide();
                    });
                }

                if ( ret.row_opt_list) {
                    var div_list=$( "aside  >section  table > tbody >tr >td > div  " );
                    div_list.find("a").hide();
                    $.each( ret.row_opt_list,function(id,class_name){
                        div_list.find("."+class_name).show();
                    });
                }

                var use_table_config=!ret.field_default_flag  ;
                var only_show_true_field=false;
                if (config_map && ret.field_default_flag  ) { //有配置, 但是是默认的
                    only_show_true_field=true;
                }

                if (config_map==null) {
                    config_map=[];
                }

                $table.addClass( "table table-bordered table-striped");
                var $row_list= $table.find("tbody >tr  ");
                var $th=$table.find("thead >tr");

                //处理disable
                var display_none_list=[];
                var not_for_xs_list=[];
                var $th_td_list= $th.find("td");
                var set_reset_filed_flag=false;
                $.each($th_td_list, function(i,item){
                    var $item=$(item);
                    var title=$.trim($item.text());
                    var config_value=   config_map[title];
                    var use_config=false;
                    if ( config_value == undefined) {
                        if (only_show_true_field ) {
                            $item.hide();
                            use_config=true;
                        }

                    }else if ( config_value == true){
                        $item.show();
                        use_config=true;
                    }else if ( config_value == false){
                        $item.hide();
                        use_config=true;
                    }

                    if (use_config) {
                        $item.removeClass( "remove-for-xs");
                        $item.removeClass( "remove-for-not-xs");
                    }

                    if ($item.css("display")=="none") {
                        display_none_list.push(i );
                    }
                    if ($item.hasClass("remove-for-xs" ) ) {
                        not_for_xs_list.push(i);
                    }

                    if (i== $th_td_list.length-1) {
                        $item.addClass( "remove-for-xs");
                    }

                    if (use_table_config && !set_reset_filed_flag ) {
                        if ($item.css("display")!="none") {
                            var $reset_btn=$("<a href=\"javascript:;\">重置</a>")  ;
                            $reset_btn.on("click",function(){
                                $.do_ajax("/user_deal/opt_table_field_list",{
                                    "opt_type":"set",
                                    "table_key":table_key,
                                    "data":""
                                });
                            });
                            $item.append($reset_btn);
                            set_reset_filed_flag=true;
                        }

                    }
                });

                $th.prepend( '<td class="remove-for-not-xs" ></td>');

                $.each ($row_list ,function(i,item){
                    var $item=$(item);
                    var td_list=$item.find("td");
                    $.each( display_none_list, function( i,display_none_id){
                        $(td_list[display_none_id]).css("display", "none");
                    });
                    $.each( not_for_xs_list, function( i,id){
                        $(td_list[id]).addClass("remove-for-xs");
                    });


                    var $td_last= $(td_list[ td_list.length-1]);
                    $td_last.addClass( "remove-for-xs");

                    $td_last.find("a").addClass("btn fa");
                    $item.prepend(
                        $('<td class="remove-for-not-xs"  > <a class="  fa  fa-cog  start-opt-mobile " style="font-size:25px"  href="javascript:;"  > </a> </td>')
                    );
                    //remove-for-xs

                    $td_last.find(">div" ).prepend('<a href="javascript:;" class="btn  fa fa-cog td-info"></a>');
                });
                bind_td_info($table);
                bind_td_drapdown($table);

                reset_item();

        };


        if (!check_in_phone() ) {
            $.do_ajax("/user_deal/opt_table_field_list",{
                "opt_type":"get",
                "table_key":table_key
            }, reset_table );
        }else{
            reset_table ({ field_default_flag:true });
        }
    });
}


$(function(){
    table_init();

    var set_title=function(title){
        $("#header_title1").text(title);
        $(document).attr("title",title+"-后台" );
    };

    var pathname=window.location.pathname+"/";
    var item_arr=pathname .split(/[\/]/);
    var opt_url="/"+item_arr[1]+"/"+item_arr[2];
    if (item_arr[2]=="index" || item_arr[2]==""){
        opt_url="/"+item_arr[1]+"/index";
    }

    var obj=$(".treeview-menu >li>a[href*=\""+ opt_url +"\"]").first();
    var title1=obj.text();
    var title2="";
    if (title1=="") {
        //检查一级节点
        obj=$(".sidebar-menu >li>a[href*=\""+window.location.pathname+"\"]").first();
        title1=obj.text();

        obj.css(  "background-color", "rgb(60, 141, 188)");
        obj.css(  "color", "white");

        if (typeof g_sid != 'undefined'  && g_sid ){
            set_title( title1+"["+g_nick+"]");
        }else{
            set_title( title1);
        }
    }else{
        var obj_par= obj.parent();
        if ( obj_par.css("display") == "none" ){
            while ((obj_par=obj_par.prev()).length ){
                if   (obj_par.css("display") != "none"  ) {
                    var obj_par_a= obj_par.find("a");
                    obj_par_a.css(  "background-color", "rgb(60, 141, 188)");
                    obj_par_a.css(  "color", "white");
                    break;
                }
            }
        }else{
            obj.css(  "background-color", "rgb(60, 141, 188)");
            obj.css(  "color", "white");
        }



        var menu_item= obj.parent().parent().parent();
        title2=menu_item.find(">a span").text();

        if ( menu_item.hasClass("treeview")){
            menu_item.children(".fa-angle-left").first().
                removeClass("fa-angle-left").addClass("fa-angle-down");
            //if ( menu_item )
            menu_item.addClass("active");
        }


        menu_item= menu_item.parent().parent();
        if (menu_item.hasClass("treeview")) {
            menu_item.children(".fa-angle-left").first().
                removeClass("fa-angle-left").addClass("fa-angle-down");
            //if ( menu_item )
            menu_item.addClass("active");
        }

        if (check_in_phone()){
            set_title( title1);
        }else{
            set_title( title2+"/"+title1 );
        }
    }


    reset_item();
    $( window ).bind("resize",reset_item);





    //处理 page select num
    $(".pages > input"). on("keypress", function( e){
    if (e.keyCode==13){
      var url=$(this).attr("data");
      var page_num=$(this).val();
            url=url.replace(/{Page}/, page_num  );
            window.location.href=url;
    }
  });

    //处理 page select num
    $(".pages > .page-opt-show-all"). on("click", function( e){
    var url=$(this).attr("data");
    var page_num=0xFFFFFFFF+1;
        url=url.replace(/{Page}/, page_num  );
        window.location.href=url;
  });
});

function bind_td_drapdown($table){
    $table.find(".remove-for-not-xs .start-opt-mobile" ).on("click",function(){
        $(this).closest("tr").find("td > div .td-info ").click();
    });
}
function check_in_phone(){
    return  $(window).width() <= 678;
}



function reset_item (){
    if (check_in_phone()) {
        $(".remove-for-xs").hide();
        $(".remove-for-not-xs").show();
    }
    if (!check_in_phone()) {
        $(".remove-for-not-xs").hide();
        $(".remove-for-xs").show();
    }
};

function bind_td_info($table){
    $table.find(".td-info").attr("title","竖向显示");
    $table.find(".td-info").on("click" ,function(){
        var th_row=$(this).closest("table").find("thead td");
        var data_row=$(this).closest("tr").find("td");

        var arr=[];
        th_row.each( function( index,element){
            if (index!=0 && index!=th_row.length-1){
                arr.push( [ $(element).text() ,  $(data_row[index]).text().replace(/,/g, ", " ) ] );
            }
            if (  index==th_row.length-1 ) { //opt

                var a_list=$(data_row).find(">div >a");
                var opt_arr=[];

                $.each( a_list ,function(a_i,a_item)  {
                    var new_item=$(a_item).clone();
                    if(!new_item.hasClass("td-info")
                       && new_item.css("display" )!= "none"
                      ) {
                        new_item.append(" "+ new_item.attr("title"));
                        new_item.on("click",function (){
                            $(a_item).click();
                        });
                        opt_arr.push( [ "操作" ,
                                    new_item ] );
                    }
                });
                arr=opt_arr.concat(arr);
            }
        });
        $.show_key_value_table("详细信息",arr );
        return false;
    });

}

function download_show(){
    var thead=$(".common-table thead  ");

    $.each(thead, function(table_i,th_item){
        if ($(th_item).parent().hasClass("table-clean-flag") ){
            return;
        }
        var path_list     = window.location.pathname.split("/");
        var table_key     = path_list[1]+"-"+path_list[2]+"-"+ table_i;
        var opt_td        = $(th_item).find ("td:last");
        var download_item = $( " <a href=\"javascript:;\" title=\"下载为xls \" class=\"fa fa-download\"></a>");
        var download_fun  = function () {
            var list_data = [];
            var $tr_list  = $(th_item).closest("table").find("tr" );
            $.each($tr_list ,function(i,tr_item )  {
                var row_data= [];
                var $td_list= $(tr_item ).find("td");
                $.each(  $td_list, function( i, td_item)  {
                    console.log(td_item.className);

                    if ( i>0 && i< $td_list.length-1 ) {
                        if(td_item.className != 'ellipsis_jiaowu'){
                            row_data.push( $.trim( $(td_item).text()) );
                        }
                    }
                });
                list_data.push(row_data);
            });

            $.do_ajax ( "/page_common/upload_xls_data",{
                xls_data :  JSON.stringify(list_data )
            },function(data){
                window.location.href= "/common_new/download_xls";
            });
        };

        download_item.on("click",function(){
            if($(".page-opt-show-all").length >0 ) {
                BootstrapDialog.show({
                    title   : '下载为xls',
                    message : '你没有全部显示，要下载全部,请 点击 <全部显示>　, <br/>下载本页面的吗?',
                    buttons : [{
                        label  : '返回',
                        action : function(dialog) {
                            dialog.close();
                        }
                    }, {
                        label    : '确认',
                        cssClass : 'btn-warning',
                        action   : function(dialog) {
                            download_fun();
                        }
                    }]
                });
            }else{
                download_fun();
            }
        });

        opt_td.append(download_item );
    });
}

