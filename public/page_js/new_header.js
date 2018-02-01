// SWITCH-TO:   ../../template/al_common/
var get_self_todo_list=function() {
    function gen_item(title, percent, url  ) {
        var percent_str="";
        var percent_tip_str="";
        if (percent>=0) {
            percent_str="     <div class=\"progress xs\">"+
                "         <div class=\"progress-bar progress-bar-aqua\" style=\"width: "+percent+"%\" role=\"progressbar\" aria-valuenow=\""+percent+"\" aria-valuemin=\"0\" aria-valuemax=\"100\">"+
                "            <span class=\"sr-only\">"+percent+"% Complete</span>"+
                "         </div>"+
                "     </div>";
            percent_tip_str="        <small class=\"pull-right\">"+percent+"%</small>";

        }else{
        }
        var item_str="<li>"+
            "  <a href=\""+url+"\" style=\"white-space: normal;\" >"+
            "     <h3>"+ title+ percent_tip_str+
            "     </h3>"+
            "  </a>"+ percent_str+
            "</li>";
        return item_str;
    }


    var $flag_div=$(".tasks-menu .tasks-count-flag " );
    $.do_ajax("/ajax_deal/get_self_todo_info",{},function(resp){
        var status_info= resp.status_info;
        var start_count= 0;
        if (status_info[1]){
            start_count= status_info[1]*1;
        }

        var not_done_count= 0;
        if (status_info[3]){
            not_done_count=  status_info[3]*1;
        }

        var count=start_count+ not_done_count;
        if (count>0) {
            var $flag_str=$("<span class=\"label label-warning\" title=\"进行中/未完成\" >" + count + "</span>");
            $flag_div.append(  $flag_str);
        }
    });
    var load_flag=false;

    $flag_div.on("click",function(){
        var $menu=$(".tasks-menu .menu" );

        if (!load_flag) {
            $.do_ajax("/ajax_deal/get_self_todo_list",{},function(resp){
                load_flag=true;
                var data=resp.data;
                $.each( data, function(i,item){
                    $menu.append($(gen_item("<font color=blue>" +item.todo_type_str + " </font> [" + item.todo_status_str +"]" + item.line_info   ,  item.percent , item.jump_url  )));

                });

            });

        }

    });

};

function setCookie(name,value)
{
    var days = 300;
    var exp = new Date();
    exp.setTime(exp.getTime() + days*24*60*60*1000);
    document.cookie = name + "="+encodeURIComponent(value)+ ";expires=" + exp.toGMTString()+";path=/";
}

//读取cookies
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    arr=document.cookie.match(reg);
    if(arr)
        return (arr[2]);
    else
        return null;
}

//删除cookies
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

function sleep(numberMillis) {
    var now = new Date();
    var exitTime = now.getTime() + numberMillis;
    while (true) {
        now = new Date();
        if (now.getTime() > exitTime)
            return;
    }
}

function countly_log(key,count){
    if (!count ) {
        count=1;
    }
    $.get("http://countly.leo1v1.com/i", {
        app_key:"891f21003e1f2c904fd93a9f2fecbd90a6da1c52",
        device_id:"js",
        events: JSON.stringify( [
            {
                timestamp : (new Date()).getTime() ,
                key       : key,
                count     : count
            }
        ])
    });
}

var g_mathjax_init_flag =false;
function mathjax_init_once() {
    if (!g_mathjax_init_flag ) {
        MathJax.Hub.Config({
            showProcessingMessages: false,
            //tex2jax: { inlineMath: [['$','$'],['\\(','\\)']] }
            tex2jax: { inlineMath: [['$','$']] ,displayMath: [ ['$$','$$']] }
        });
        g_mathjax_init_flag=true;
    }
}


//JS数组去重
function unique(arr) {
    var result = [], hash = {};
    for (var i = 0, elem; (elem = arr[i]) != null; i++) {
        if (!hash[elem]) {
            result.push(elem);
            hash[elem] = true;
        }
    }
    return result;
}
function set_input_enter_event($input, func ) {
    $input.on('keypress',function(e){
        if(e.keyCode== 13){
            func();
        }
    });

}


function up(obj){
    var objParentTR = $(obj).parent().parent();
    var prevTR      = objParentTR.prev();
    if (prevTR.length > 0) {
        prevTR.insertAfter(objParentTR);
    }
}

function down(obj){
    var objParentTR = $(obj).parent().parent();
    var nextTR      = objParentTR.next();
    if (nextTR.length > 0) {
        nextTR.insertBefore(objParentTR);
    }
}



function hide_menu_by_power_list( ){
    var $menu_main_list=$(".menu-list [data-power-list]");
    $menu_main_list.hide();
    $.each( $menu_main_list,function(){
        var $item=$(this);
        var power_list_str=""+$item.data("power-list"); //1,10-20
        //var power_list=[];
        var power_list_str_arr=power_list_str.split(",");

        var find_flag=false;
        $.each (power_list_str_arr,function() {
            var tmp_arr=this.split("-");
            if (tmp_arr.length==2){
                for(var i= parseInt(tmp_arr[0]); i<=  parseInt(tmp_arr[1]);i++){
                    if (g_power_list[""+i] ){
                        find_flag=true;
                        break;
                    }
                }
            }else{
                if((g_power_list[$.trim(this) ] ) ){
                    find_flag=true;
                }
            }

            if (find_flag){
                $item.show();
                var $p=$item.parents(".treeview");

                $p.show();
                /**
                   $p=$p.parents(".treeview");
                   console.log( "xx:"+$p.text() );
                   $p.show();
                */

                return false;
            }else{
                return true;
            }
        });
    });
}
function table_init() {
    if ( window.g_args &&  g_args.date_type_config ) {
        var config=JSON.parse( g_args.date_type_config);
        if (config) {
            if(config[g_args.date_type] ) {
                var date_th_desc= config[g_args.date_type][1] ;
                $(".th-opt-time-field").text(date_th_desc);

            }
        }
    }
    var flag_key = "query_flag_"+ window.location.pathname;
    g_load_data_flag = window.localStorage.getItem(flag_key);

    var row_query=$(".row-query-list" );
    if (row_query.length>0) {
        var query_select_list=$( " <div class=\"col-xs-6 col-md-1\"> <div class=\"input-group\" >  <div class=\"input-group\" > <button   title=\"显示所有条件\" class=\"btn btn-warning fa   show-all  \"> ALL </button> </div> </div> <div> ");

        var query_attime=$( " <div class=\"col-xs-6 col-md-1\">  <button   title=\"点击查询\" class=\"btn btn-warning fa  \" > 查询 </button  <div> ");

        if ( window.load_data && g_load_data_flag) {
            row_query.append( query_attime );
            query_attime.find("button") .on("click",function(){
                g_load_data_flag = 0;
                window.load_data();
            });
        }


        row_query.append( query_select_list );
        var path_list=window.location.pathname.split("/");
        var table_query_key=path_list[1]+"-"+path_list[2]+"-query" ;


        var item_list=row_query.find (">div");
        if (item_list.length>5) {
            $.each( item_list, function(i,item){
                var $item=$(item);
                var input=$item.find("select");
                var field_name="";
                if (input.length==0) {
                    input=$item.find("input");//input
                    if (input.length>0) {
                        field_name=input.attr("id").substr(3);
                        if(input.parent().parent().data("always_hide")  ) {
                            input.parent().parent().hide();
                        }
                        if (g_args[field_name] == -1  ||   g_args[field_name] === ""   ) {
                            if ( !input.parent().parent().data("always_show") ) {
                                input.parent().parent().hide();
                            }

                        }
                    }
                }else{
                    if(input.parent().parent().data("always_hide")  ) {
                        input.parent().parent().hide();
                    }

                    field_name=input.attr("id").substr(3);
                    if (g_args[field_name] == -1 ) {
                        if ( !input.parent().parent().data("always_show") ) {
                            input.parent().parent().hide();
                        }
                    }
                }

            });
        }
        var show_all_btn=query_select_list.find(".show-all");

        show_all_btn.on("click",function(){
            var show_all_flag= !$(this).data("show_all_flag");
            $(this).data("show_all_flag", show_all_flag);
            $.each( item_list, function(i,item){
                var $item=$(item);
                var input=$item.find("select");
                var field_name="";
                if (input.length==0) {
                    input=$item.find("input");//input
                    if (input.length>0) {
                        if (show_all_flag) {
                            if (!input.parent().parent().data("always_hide")){
                                input.parent().parent().show();
                            }
                        }else{
                            field_name=input.attr("id").substr(3);
                            if (g_args[field_name] == -1  ||   g_args[field_name] === ""   ) {
                                if ( !input.parent().parent().data("always_show") ) {
                                    input.parent().parent().hide();
                                }
                            }
                        }
                    }
                }else{
                    field_name=input.attr("id").substr(3);
                    if (show_all_flag) {
                        if ( !input.parent().parent().data("always_hide") ) {
                            input.parent().parent().show();
                        }
                    }else{
                        if (g_args[field_name] == -1 ) {
                            if ( !input.parent().parent().data("always_show") ) {
                                if ( !input.parent().parent().data("always_hide") ) {
                                    input.parent().parent().show();
                                }

                                input.parent().parent().hide();
                            }
                        }
                    }
                }
            });
        });
    }

    var thead=$(".common-table thead  ");

    $.each(thead, function(table_i,th_item){
        if ($(th_item).parent().hasClass("table-clean-flag") ){
            return;
        }
        var path_list     = window.location.pathname.split("/");
        var table_key     = path_list[1]+"-"+path_list[2]+"-"+ table_i;
        var opt_td        = $(th_item).find ("td:last");

        if (!opt_td.css("min-width")  ) {
            opt_td.css("min-width","80px");
        }

        var config_item=$( " <a href=\"javascript:; \" style=\"color:red;\" title=\" 列显示配置\"   >列</a>");
        config_item.on("click",function(){
            var $table= $(this).closest("table");
            var $th=$table.find("thead >tr");

            var $th_td_list= $th.find(">td");
            var arr=[];
            $.each($th_td_list, function(i,item){
                if (!(i==0 || i== $th_td_list.length-1)) {
                    var $item=$(item);
                    var title= $item.data("title")  ;
                    if (!title) {
                        title= $.trim($item.text());
                    }
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
                    $.do_ajax("/page_common/opt_table_field_list",{
                        "opt_type":"set",
                        "table_key":table_key,
                        "data":""
                    });

                    //alert(" XXXXX set table_key clean 1 ");
                    window.localStorage.setItem(table_key , "");
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
                    $.do_ajax("/page_common/opt_table_field_list",{
                        "opt_type":"set",
                        "table_key":table_key,
                        "data":JSON.stringify(config_map)
                    },function(){
                        $.do_ajax("/page_common/opt_table_field_list",{
                            "opt_type":"get",
                            "table_key":table_key
                        }, function(resp ){
                            var cur=(new Date() ).getTime()/1000;
                            resp.log_time=cur;
                            //alert("XXXX SET :"+ JSON.stringify(resp)  );
                            window.localStorage.setItem(table_key , JSON.stringify(resp));
                            window.location.reload();
                        });
                    });


                }
            }]);
        });
        opt_td.append(config_item);

    });


    var $table_list=$(".common-table") ;
    $table_list.css({
        "border-top":  "3px solid #d2d6de",
        "border-radius" : "3px",
    });


    $.each($table_list, function(table_i){

        var path_list=window.location.pathname.split("/");
        var table_key=path_list[1]+"-"+path_list[2]+"-"+ table_i;
        var $table=$(this);
        var $div=$("<div class=\"table-responsive \"/>");
        $table.before($div);
        $div.append($table);

        var reset_table=     function(ret) {
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
                var title=$(item).data("title");
                if (!title){
                    title=$.trim($item.text());
                }
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
                            $.do_ajax("/page_common/opt_table_field_list",{
                                "opt_type":"set",
                                "table_key":table_key,
                                "data":""
                            });
                            window.localStorage.setItem(table_key , "");

                        });
                        $item.data("title", $.trim($item.text()) );
                        $item.append($reset_btn);
                        set_reset_filed_flag=true;

                    }

                }
            });

            //for

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
            $table.find(".remove-for-not-xs .start-opt-mobile" ).on("click",function(){
                $(this).closest("tr").find("td > div .td-info ").click();
            });

            reset_item();

        };


        if (!check_in_phone() ) {
            var val=window.localStorage.getItem(table_key  );
            var cur=(new Date() ).getTime()/1000;
            var config = null;
            try {
                config=JSON.parse(val );
            }catch( $e) {
            }
            if (config && cur - config.log_time < 3600    ) {
                reset_table (config);
            }else {
                $.do_ajax("/page_common/opt_table_field_list",{
                    "opt_type":"get",
                    "table_key":table_key
                }, function(resp ){
                    reset_table (resp);
                    resp.log_time=cur;
                    //alert("XXXX SET :"+ JSON.stringify(resp)  );
                    window.localStorage.setItem(table_key , JSON.stringify(resp));
                });
            }
        }else{
            reset_table ({});
        }


    });

}


$(function(){
    if (getCookie ("show-menu") == "false" ){
        //$(".sidebar-toggle").click();
        $("body").addClass("sidebar-collapse");
    }

    if ($.datetimepicker) {
        $.datetimepicker.setLocale("ch");
    }

    table_init();

    hide_menu_by_power_list();

    //$(".treeview").show();
    var set_title=function(title){
        if (check_in_phone() ){
            $("#header_title1").text( title.substring(0,5) );
        }else{
            $("#header_title1").text( title );
        }
        $(document).attr("title",title+"-后台" );
    };

    var pathname=window.location.pathname+"/";
    var item_arr=pathname .split(/[\/]/);
    var opt_url="/"+item_arr[1]+"/"+item_arr[2];
    if (item_arr[2]=="index" || item_arr[2]==""){
        opt_url="/"+item_arr[1]+"/index";
    }

    var check_url= window.location.toString().split("?" )[0];
    check_url= check_url.split("#" )[0].replace(/\/*$/, "" );

    var obj=$(".treeview-menu >li>a[href*=\""+ check_url +"\"]");

    var path_arr=window.location.pathname.split("/");
    var ctrl=path_arr[1];
    var action=path_arr[2];
    if(!action) {
        action="index";
    }
    var tmp_obj=$("<div/>");
    obj.each(function(){
        var href_str= $(this).attr("href") ;
        //http://....com/tt/ff => /tt/ff
        href_str= href_str.replace( /http.*\.com/, "" ).replace(/#.*/ , "" );


        var arr=href_str.split("/");
        var find_action=arr[2].split("?")[0];
        if(!find_action) {
            find_action="index";
        }
        if(find_action==action) {
            tmp_obj =$(this);

            return false;
        }
        return true;
    });
    obj=tmp_obj;

    var title1=obj.text();
    var title2="";
    if (title1=="") {
        //检查一级节点
        var check_url=$.trim( window.location.toString().split("?" )[0], "/");
        check_url= check_url.split("#" )[0].replace(/\/*$/, "" );

        obj=$(".sidebar-menu >li>a[href*=\""+check_url+"\"]").first();
        title1=obj.text();

        /*
          obj.css(  "background-color", "rgb(60, 141, 188)");
          obj.css(  "color", "white");
        */
        obj.parent().addClass("active");

        if (typeof g_nick != 'undefined'  && g_nick ){
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
                    obj.parent().addClass("active");
                    /*
                      obj_par_a.css(  "background-color", "rgb(60, 141, 188)");
                      obj_par_a.css(  "color", "white");
                    */
                    obj.parent().addClass("active");
                    break;

                }
            }
        }else{
            /*
              obj.css(  "background-color", "rgb(60, 141, 188)");
              obj.css(  "color", "white");
            */
            obj.parent().addClass("active");
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
    obj.addClass("global-menu-select-item");

    reset_item();
    $( window ).bind("resize",reset_item);

    if(
        window.location.host=="admin.leo1v1.com"
            || window.location.host=="admin-vue.leo1v1.com"
            || window.location.host=="admin-tongji.leo1v1.com"
    ){
        $("#id_version_switch").text("切换到冒烟版本");
        $("#id_version_switch").on("click",function(){
            window.location.href=window.location.href.replace("admin.leo1v1.com", "p.admin.leo1v1.com");
        });
    }else{
        $("#id_version_switch").text("切换到稳定版本");
        $("#id_version_switch").on("click",function(){
            window.location.href=window.location.href.replace("p.admin.leo1v1.com", "admin.leo1v1.com");
        });
    }


    $("#id_self_menu_add").on("click",function(){
        var title= $(".global-menu-select-item").text();
        var url=   window.location.href.substr( window.location.origin.length);
        //var url = window.location.href;
        $.do_ajax("/self_manage/self_menu_add",{
            "title" : title,
            "url" : url,
        });
    });


    $("#id_now_refresh").on("click",function(){
        if(window.localStorage){
            var flag_key = "query_flag_"+ window.location.pathname;
            var load_data_flag = window.localStorage.getItem( flag_key );
            var list_type_key = "query_list_type_"+ window.location.pathname;
            var list_type = window.localStorage.getItem(list_type_key  );
            var $list_type=$("<select  >   <option value=0>紧凑</option> <option value=1>列表</option> <option value=2>超级紧凑</option> </select>  ");

            var $load_data_flag=$("<select  > <option value=0>是</option> <option value=1>不是</option> </select> ");
            list_type= list_type?list_type:0;
            load_data_flag= load_data_flag?load_data_flag:0;

            var arr=[
                ["展现形式" , $list_type],
                ["是否立即查询" , $load_data_flag  ],
            ];
            $list_type.val( list_type );
            $load_data_flag.val( load_data_flag);

            $.show_key_value_table("查询参数", arr ,[{
                label: '提交',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    window.localStorage.setItem( flag_key , $load_data_flag.val()   );
                    window.localStorage.setItem( list_type_key, $list_type.val()   );
                    $.reload();
                }
            }]);



        }else{
            alert('浏览器不支持localstorage');
            return false;
        }
        // window.localStorage.setItem("key","value");//存
        // window.localStorage.getItem("key");//获取
        // window.localStorage.removeItem("key");//删除
        // $.do_ajax("/self_manage/ssh_login",{
        // },function(resp){
        //     alert("请登录: " + resp.ssh_cmd   );
        // } );
    });

    $("#id_ssh_open").on("click",function(){
        $.do_ajax("/self_manage/ssh_login",{
        },function(resp){
            alert("请登录: " + resp.ssh_cmd   );
        } );
    });
    $("#id_menu_config").on("click",function(){
        var enum_name="main_department";
        var desc_map=g_enum_map[enum_name]["desc_map"];

        $.do_ajax("/ajax_deal2/get_admin_member_config",{},function(resp){

            var data_list=[
            ];
            $.each(desc_map, function(k,v){
                data_list.push([k, v] );
            });

            var btn_list =[
            ];

            var select_list    = resp.menu_config.split(/,/);
            var select_id_list = [];
            $.each(select_list,function( ){
                var id= parseInt(this);
                select_id_list.push(id);
            });

            $("<div></div>").admin_select_dlg({
                'data_list': data_list,
                "header_list":["id","属性"] ,
                "onChange": function ( select_list,dlg ){
                    do_ajax("/ajax_deal2/set_admin_menu_config",{
                        "menu_config" : select_list.join(","),
                    });
                },
                "select_list": [],
                "multi_selection":true,
                btn_list :btn_list ,
                "select_list": select_id_list,

            });


        }) ;


    });

    $("#id_public_user_reset_power").on("click",function(){
        do_ajax("/user_deal/reload_account_power");
    });

    //logout
    $("#id_system_logout").on("click",function(){
        BootstrapDialog.show({
            title: '退出系统',
            message: '要退出系统吗',
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        'url': '/login/logout',
                        'type': 'POST',
                        'data': {},
                        'dataType': 'jsonp',
                        success: function(data) {
                            if (data['ret'] == 0) {
                                $.reload();
                            } else {
                                console.log(data);
                            }
                        }
                    });
                }
            }]
        });

    });
    //logout
    $("#id_system_logout_teacher").on("click",function(){
        BootstrapDialog.show({
            title: '退出系统',
            message: '要退出系统吗',
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        'url': '/login/logout_teacher',
                        'type': 'POST',
                        'data': {},
                        'dataType': 'jsonp',
                        success: function(data) {
                            if (data['ret'] == 0) {
                                window.location.href = "/login/teacher" ;
                            } else {
                                window.location.href = "/teacher_info/index" ;
                            }
                        }
                    });
                }
            }]
        });

    });

    //退出优学优享团系统
    $("#id_system_logout_agent").on("click",function(){
        BootstrapDialog.show({
            title: '退出系统',
            message: '要退出系统吗',
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        'url': '/login/logout_teacher',
                        'type': 'POST',
                        'data': {},
                        'dataType': 'jsonp',
                        success: function(data) {
                            if (data['ret'] == 0) {
                                window.location.href = "/login/agent" ;
                            } else {
                                window.location.href = "/agent_info/index" ;
                            }
                        }
                    });
                }
            }]
        });

    });

    if ($.query){
        if ( $.query.get("return_url") ){
            $("#id_header_return_back").attr("href" ,  $.query.get("return_url")   );
        }
    }


    /*
      $('.opt-time-picker').datetimepicker({
      lang:'ch',
      timepicker:false,
      format:'Y-m-d'
      });
    */

    //处理 page select num
    $(".pages > input"). on("keypress", function( e){
        if (e.keyCode==13){
            var url=$(this).attr("data");
            var page_num=$(this).val();
            url=url.replace(/{Page}/, page_num  );
            url=url.replace(/{PageCount}/, g_args.page_count );
            window.location.href=url;
        }
    });

    //处理 page select num
    $(".pages > .page-opt-show-all"). on("click", function( e){
        var url=$(this).attr("data");
        var page_num=0xFFFFFFFF+1;
        url=url.replace(/{Page}/, page_num  );
        url=url.replace(/{PageCount}/, g_args.page_count );
        window.location.href=url;
    });

    if ( window.g_args ) {
        $(".pages > .page-opt-select-page").val(g_args.page_count);
    }
    $(".pages > .page-opt-select-page"). on("change", function( e){
        var url=$(this).attr("data");
        url=url.replace(/{Page}/, 1);
        url=url.replace(/{PageCount}/, $(this).val() );
        window.location.href=url;
    });


    //处理 page select num
    // $(".pages > .page-opt-show-all-xls"). on("click", function( e){
    //     var url=$(this).attr("data");
    //     var page_num=0xFFFFFFFF+2;
    //     url=url.replace(/{Page}/, page_num  );
    //     window.location.href=url;
    // });

    //do role
    do_role();

});

function do_role() {
    var get_count_item = function( count ,title, url )  {
        var $count_item=$('<li title="' + title+ '"   > <a href="' +url+ '" style="  font-size: 18px; font-weight: bold; " > <span >' + count + '</span> </a> </li>');
        if (count>0) {
            $count_item.find("a").css("background-color",  "orange" );
        }
        return $count_item;
    };

    if(typeof(g_account_role)!="undefined"){
        get_self_todo_list();

        if ( (g_account_role==7 || g_account_role==2) && !$.check_in_phone() ) {
            var $noti_info=$("#_id_noti_info");

            $.do_ajax( "/ss_deal/seller_noti_info",{},function(resp){

                var date=new Date();
                var now=date.getTime()/1000;

                var base_url="http://"+ window.location.hostname+ "/seller_student_new/seller_student_list_all?";
                var query_str="";

                query_str= $.filed_init_date_range_query_str( 4,  0, now-86400*60 ,  now);
                query_str+="&seller_student_status=0&tmk_student_status=3";

                $noti_info.append(get_count_item(resp.tmk_new_no_call_count,"微信-新分配例子未回访数", base_url+query_str ) );

                //
                query_str= $.filed_init_date_range_query_str( 4,  0, now-86400*60 ,  now);
                query_str+="&seller_student_status=0&seller_resource_type=0";
                $noti_info.append(get_count_item( resp.new_not_call_count,  "新分配例子未回访数", base_url+query_str ) );


                //
                query_str= $.filed_init_date_range_query_str( 4,  0, now-86400*60 ,  now);
                query_str+="&seller_student_status=0";
                $noti_info.append(get_count_item( resp.not_call_count,    "例子未回访数" , base_url+query_str ) );
                //
                query_str= $.filed_init_date_range_query_str( 1,  0, now-7*86400,  now);
                $noti_info.append(get_count_item( resp.next_revisit_count,    "需再次回访数",  base_url+query_str ) );
                //
                query_str= $.filed_init_date_range_query_str(  5,  1, now,  now);
                $noti_info.append(get_count_item( resp.today,  "今天上课须通知数" , base_url+query_str ) );

                query_str= $.filed_init_date_range_query_str(  5,  1, now+86400,  now+86400);
                $noti_info.append(get_count_item(  resp.tomorrow, "明天上课须通知数" , base_url+query_str ) );


                query_str= $.filed_init_date_range_query_str(   3,  0, now-14*86400,  now);
                query_str+="&seller_student_status=110";
                $noti_info.append(get_count_item(  resp.return_back_count, "被驳回未处理的个数"   , base_url+query_str ) );


                query_str= $.filed_init_date_range_query_str(   3,  0, now-14*86400,  now);
                query_str+="&seller_student_status=200";
                $noti_info.append(get_count_item( resp.require_count , "已预约未排数"   , base_url+query_str ) );


                query_str= $.filed_init_date_range_query_str(   5,  0, now-14*86400,  now);
                query_str+="&success_flag=0";

                $noti_info.append(get_count_item( resp.no_confirm_count,"课程未确认数" ,  base_url+query_str ) );

            });

        }
    }


}

function show_ajax_table(options){
    var defaults = {
        "title" : "列表",
        "field_list" : [{
            "name"  : "id",
            "title" : "xxx",
            "class" : "xxx"
        },{
            "name"   : "name",
            "title"  : "名称",
            "class"  : "xxx",
            "render" : function(val ,item){
                return val;
            }
        }],
        "request_info" : {
            "url"  : "" ,
            "data" : {}
        },
        "bind":function($id_body ){
            //$id_body.find(".xxx").on(  );

        }
    };

    options = $.extend({}, defaults, options);

    var title_str="";
    $.each( options.field_list,function(){
        title_str+= "<th>"+ this.title + "</th>";
    });

    var html_node=$('<div><table class="table table-bordered table-striped">'+
                    '     <tr>'+ title_str+'</tr>'+
                    '     <tbody id="id_body">'+
                    '     </tbody>'+
                    '     </table>' +
                    '     <div id="id_page_info"> '+
                    '</div> </div> ') ;

    var reload_data=function(request_info){
        var me=this;
        $.ajax({
            type     :"post",
            url      : request_info.url,
            dataType :"json",
            data     : request_info.data,
            success  : function(result){
                var ret_list      = result.data.list;
                var ret_page_info = result.data.page_info;
                var html_str="";
                $.each( ret_list, function(i,item){
                    html_str+="<tr>";
                    $.each(options.field_list,function(){
                        if(!this.class){
                            this.class="";
                        }
                        if (this.render ){
                            html_str+= "<td class=\""+this.class+"\">"+ this.render(item[this.name], item) + "</td>";
                        }else{
                            html_str+= "<td class=\""+this.class+"\">"+ item[this.name]+ "</td>";
                        }
                    });
                    html_str+="</tr>";
                });

                var $id_page_info = html_node.find("#id_page_info");
                var $id_body      = html_node.find("#id_body");

                $id_body.html(html_str);

                //绑定事件
                if (options.bind) {
                    options.bind($id_body,dlg,result);
                }

                var page_html_node = get_page_node(ret_page_info,function(url){
                    reload_data({
                        url :url
                    });
                });
                $id_page_info.html( page_html_node );
            }
        });
    };

    var dlg=BootstrapDialog.show({
        title:  options.title ,
        message : html_node,
        closable: true,
        onhide: function(dialogRef){
        },

        buttons: [{
            label: '返回',
            action: function(dialog) {
                dialog.close();
            }
        }]

    });

    dlg.getModalDialog().find( ".modal-footer").css({
        "margin-top":"0px"
    });
    //加载数据
    reload_data( options.request_info );
}

function show_key_value_table(title,arr ,btn_config,onshownfunc){
    var table_obj = $("<table class=\"table table-bordered table-striped\"><tr><thead><td style=\"text-align:right;\">属性  </td>  <td> 值 </td> </thead></tr></table>");

    $.each(arr , function( index,element){
        var row_obj=$("<tr> </tr>" );
        var td_obj=$( "<td style=\"text-align:right; width:30%;\"></td>" );
        var v=element[0] ;
        td_obj.append(v);
        row_obj.append(td_obj);
        td_obj=$( "<td ></td>" );

        td_obj.append( element[1] );
        row_obj.append(td_obj);
        table_obj.append(row_obj);
    });
    var all_btn_config=[{
        label: '返回',
        action: function(dialog) {
            dialog.close();
        }
    }];
    if (btn_config){
        if($.isArray( btn_config)){
            $.each(btn_config ,function(){
                all_btn_config.push(this);
            });
        }else{
            all_btn_config.push(btn_config );
        }
    }

    BootstrapDialog.show({
        title    : title,
        message  : table_obj ,
        closable : true,
        buttons  : all_btn_config ,
        onshown  : onshownfunc
    });
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

function bind_td_info( $table){

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
                var $show_inter_data=$("<a class=\"btn fa inter-data\" href=\"javascript:;\"  >inter data</a>");
                if (!$table.hasClass("table-clean-flag")  ) {
                    opt_arr.push( [ "操作" ,$show_inter_data ] );
                }
                $show_inter_data.on("click",function(){
                    var opt_div=$(data_row[data_row.length-1]).find(">div");
                    var opt_data=opt_div.data();
                    var opt_data_arr=[];
                    $.each(opt_data, function(i,item ){
                        opt_data_arr.push([i, item] );
                    });

                    show_key_value_table("内部数据",opt_data_arr);


                });

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
        show_key_value_table("详细信息",arr );
        return false;
    });

}

function check_in_phone(){
    return  $(window).width() <= 678;
}

function dlg_need_html_by_id( id ){
    return $('<div></div>').append( $('#' +id ).html() );
}
function obj_copy_node( item  ){
    return $( $(item )[0].outerHTML );
}

function dlg_get_val_by_id( id ){
    return $(".bootstrap-dialog-message #"+id).val();
}
function dlg_set_val_by_id( id, value ){
    return $(".bootstrap-dialog-message #"+id).val(value);
}
function dlg_set_txt_by_id( id, value ){
    return $(".bootstrap-dialog-message #"+id).text(value);
}


function dlg_get_item_by_id( id ){
    return $(".bootstrap-dialog-message #"+id);
}
function dlg_get_item( item ){
    return $(".bootstrap-dialog-message "+item);
}

function dlg_get_html_by_class(item_class) {
    return $("." + item_class).html();
}



/*
  var time1 = new Date().Format("yyyy-MM-dd");
  var time2 = new Date().Format("yyyy-MM-dd hh:mm:ss");
*/
var DateFormat = function ( unixtime, fmt) {
    var date_v=new Date(unixtime*1000);
    var o = {
        "M+": date_v.getMonth() + 1, //月份
        "d+": date_v.getDate(), //日
        "h+": date_v.getHours(), //小时
        "m+": date_v.getMinutes(), //分
        "s+": date_v.getSeconds(), //秒
        "q+": Math.floor((date_v.getMonth() + 3) / 3), //季度
        "S": date_v.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (date_v.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return  fmt;
};
// 2016-14-10 12:10:10
var strtotime=function( str) {
    var tmp_datetime = str.replace(/:/g,'-');
    tmp_datetime = tmp_datetime.replace(/ /g,'-');
    var arr = tmp_datetime.split("-");
    var hh=arr[3];
    var mm=arr[4];
    var ss=arr[5];
    if (hh == undefined) {
        hh=0;
    }
    if (mm== undefined) {
        mm=0;
    }
    if (ss== undefined) {
        ss=0;
    }

    var now = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],hh-8,mm,ss));
    return parseInt(now.getTime()/1000);
};

function remove_last_comma(str) {
    return str.substr(0, str.length - 1);
}
function show_input( title,  value, ok_func ,$input  ){
    if (!$input){
        $input=$("<input/>");
    }else{
        $input=$($input);
    }

    $input.val(value);

    BootstrapDialog.show({
        title: title,
        message: $input,
        buttons: [{
            label: '确认',
            cssClass: 'btn btn-warning',
            action: function(dialog)  {
                ok_func($input.val());
                dialog.close();
            }
        }, {
            label: '取消',
            cssClass: 'btn btn-default',
            action: function(dialog) {
                dialog.close();
            }
        }]
    });

}

function show_message(title, message, ok_func ){
    if (ok_func){
        BootstrapDialog.show({
            title: title,
            message: message,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn btn-warning',
                    action: ok_func
                }, {
                    label: '取消',
                    cssClass: 'btn btn-default',
                    action: function(dialog) {
                        dialog.close();
                    }
                }
            ]
        });
    }else{
        BootstrapDialog.show({
            title: title,
            message: message,
            buttons: [
                {
                    label: '返回',
                    cssClass: 'btn btn-default',
                    action: function(dialog) {
                        dialog.close();
                    }
                }
            ]
        });
    }
}

//enum map function
Enum_map = {
    get_desc : function(group_name,val){
        var ret=g_enum_map[group_name]["desc_map"][val];
        return  ret?ret:val;
    },
    get_simple_desc: function (group_name,val){
        var desc=g_enum_map[group_name]["simple_desc_map"][val];
        if(desc){
            return this.get_desc(group_name,val) ;
        }else{
            return desc;
        };
    },
    append_option_list : function (group_name, $select , not_add_all_option, id_list ){
        var desc_map=g_enum_map[group_name]["desc_map"];

        var html_str="";
        if (!not_add_all_option  ){
            html_str += "<option value=\"-1\">[全部]</option>";
        }
        $.each(desc_map, function(k,v){
            if ($.isArray( id_list)) {
                if($.inArray( parseInt(k), id_list ) != -1 ){
                    html_str+="<option value=\""+k+"\">"+v+"</option>";
                }
            }else{
                html_str+="<option value=\""+k+"\">"+v+"</option>";
            }
        });
        $select.append(html_str);
    },
    append_option_list_by_not_id : function (group_name, $select , not_add_all_option, not_id_list ){
        var desc_map=g_enum_map[group_name]["desc_map"];

        var html_str="";
        if (!not_add_all_option  ){
            html_str += "<option value=\"-1\">[全部]</option>";
        }
        $.each(desc_map, function(k,v){
            if ($.isArray( not_id_list)) {
                if($.inArray( parseInt(k), not_id_list ) == -1 ){
                    html_str+="<option value=\""+k+"\">"+v+"</option>";
                }
            }else{
                html_str+="<option value=\""+k+"\">"+v+"</option>";
            }
        });
        $select.append(html_str);
    },
    append_child_option_list : function(group_name,$select,$child_select,not_add_all_option){
        var desc_map = g_enum_map[group_name]['desc_map'];
        var html_str = "";
        var val      = $select.val();

        if (!not_add_all_option){
            html_str+="<option value=\"-1\">[全部]</option>";
        }

        $.each(desc_map,function(k,v){
            if(k.substr(0,k.length-2)==val){
                html_str+="<option value=\""+k+"\">"+v+"</option>";
            }
        });
        $child_select.html(html_str);
    },
    append_checkbox_list : function (group_name,$select,checkname,id_list,not_br){
        var desc_map=g_enum_map[group_name]["desc_map"];

        var html_str="";
        var end_html="";
        if(!not_br){
            end_html="&nbsp;";
        }else{
            end_html="<br/>";
        }
        $.each(desc_map,function(k,v){
            if ($.isArray(id_list)) {
                if($.inArray( k, id_list ) != -1 ){
                    html_str+="<input type=\"checkbox\" name=\""+checkname+"\" class=\""+checkname+"\" value=\""+k+"\"/>"+v+end_html;
                }
            }else{
                html_str+="<input type=\"checkbox\" name=\""+checkname+"\" class=\""+checkname+"\" value=\""+k+"\"/>"+v+end_html;
            }
        });
        $select.append(html_str);
    },
    td_show_desc : function(group_name, $item_list , is_simple_flag ){
        var me = this;
        $.each($item_list,function(i,item ){
            var $item = $(item);
            var val   = $item.data("v") ;
            var desc  = "";
            if (is_simple_flag ){
                desc = me.get_simple_desc( group_name,val ) ;
            }else{
                desc = me.get_desc( group_name,val ) ;
            }
            $item.text( desc  );
        });
    },
    append_option_list_new : function (group_name, $select , not_add_all_option, id_list ){
        var desc_map=g_enum_map[group_name]["desc_map"];
        var newkey = Object.keys(desc_map).sort().reverse();
        var newObj = {};//创建一个新的对象，用于存放排好序的键值对
        for (var i = 0; i < newkey.length; i++) {//遍历newkey数组
            newObj[newkey[i]] = desc_map[newkey[i]];//向新创建的对象中按照排好的顺序依次增加键值对
        }


        var html_str="";
        if (!not_add_all_option  ){
            html_str += "<option value=\"-1\">[全部]</option>";
        }
        $.each(newObj, function(k,v){
            if ($.isArray( id_list)) {
                if($.inArray( parseInt(k), id_list ) != -1 ){
                    html_str+="<option value=\""+k+"\">"+v+"</option>";
                }
            }else{
                html_str+="<option value=\""+k+"\">"+v+"</option>";
            }
        });
        $select.append(html_str);
    },
    append_option_list_v2s : function (group_name, $select , not_add_all_option, id_list ){
        console.log(group_name);
        var desc_map=g_enum_map[group_name]["simple_desc_map"];
        console.log(group_name[group_name]);
        var html_str="";
        if (!not_add_all_option  ){
            html_str += "<option value=\"-1\">[全部]</option>";
        }
        $.each(desc_map, function(k,v){
            if ($.isArray( id_list)) {
                if($.inArray( parseInt(k), id_list ) != -1 ){
                    html_str+="<option value=\""+k+"\">"+v+"</option>";
                }
            }else{
                html_str+="<option value=\""+k+"\">"+v+"</option>";
            }
        });
        $select.append(html_str);
    },


};

function get_page_node(page_info ,reload_func)
{
    var ret_str="";

    if (page_info.page_num > 1) {

        ret_str= '<div class="pages"> ';
        ret_str+='<a href="javascript:;" class="btn page-opt-show-all" data="'+page_info.page.input_page_num_url+'" >显示全部</a> ';

        ret_str+='  <input style="width:50px" placeholder="输入页数" data="'+
            page_info.page.input_page_num_url+'"  > </input>';
        //<!--上一页-->
        if ( page_info.current_page == 1){
            ret_str+= '<a class="page_prev page_grey" href="javascript:void(0);"><</a>';
        }else{
            ret_str+= '<a class="page_prev" href="javascript:void(0);" data="'+
                page_info.page.previous_url+
                '" ><</a>';
        }
        //  <!--页码-->
        if ( page_info.page_num < 11 ){
            $.each( page_info.page.pages, function(key,val){
                if (val.page_num == page_info.current_page){
                    ret_str+=' <a class="page_num page_cur" href="javascript:void(0);" name="page_btn" page="'+val.page_num+'">'+val.page_num+'</a>';
                }else{
                    ret_str+=' <a class="page_num" href="javascript:void(0);"  data="'+val.page_link+'" name="page_btn" page="'+val.page_num+'">'+val.page_num+'</a>';
                }

            });
        }else{
            if ( page_info.current_page < 6){

                $.each( page_info.page.pages, function(key,val){
                    if ( val.page_num == page_info.current_page){
                        ret_str+=' <a class="page_num page_cur" href="javascript:void(0);" name="page_btn" page="'+val.page_num+'">'+val.page_num+'</a>';
                    }else{
                        ret_str+=' <a class="page_num" href="javascript:void(0);"  data="'+val.page_link+'" name="page_btn" page="'+val.page_num+'">'+val.page_num+'</a>';
                    }

                });

                ret_str+="<span>...</span>";
                ret_str+='<a class="page_num" href="javascript:void(0);"   data="'+page_info.page.last_page_url+'">'+page_info.page_num+'</a>';


            }else if ( page_info.page_num - page_info.current_page <4 ){
                ret_str+='<a class="page_num" href="javascript:void(0);" data="'+page_info.page.first_page_url+'">1</a>';
                ret_str+="<span>...</span>";


                $.each( page_info.page.pages, function(key,val){

                    if ( val.page_num == page_info.current_page){
                        ret_str+=' <a class="page_num page_cur" href="javascript:void(0);" name="page_btn" page="'+val.page_num+'">'+val.page_num+'</a>';
                    }else{
                        ret_str+=' <a class="page_num" href="javascript:void(0);"  data="'+val.page_link+'" name="page_btn" page="'+val.page_num+'">'+val.page_num+'</a>';
                    }

                });

            }else{
                ret_str+='<a class="page_num" href="javascript:void(0);" data="'+page_info.page.first_page_url+'">1</a>';
                ret_str+="<span>...</span>";

                $.each( page_info.page.pages, function(key,val){

                    if ( val.page_num == page_info.current_page){
                        ret_str+=' <a class="page_num page_cur" href="javascript:void(0);" name="page_btn" page="'+val.page_num+'">'+val.page_num+'</a>';
                    }else{
                        ret_str+=' <a class="page_num" href="javascript:void(0);"  data="'+val.page_link+'" name="page_btn" page="'+val.page_num+'">'+val.page_num+'</a>';
                    }

                });
                ret_str+="<span>...</span>";
                ret_str+='<a class="page_num" href="javascript:void(0);"   data="'+page_info.page.last_page_url+'">'+page_info.page_num+'</a>';

            }

        }

        //  <!--下一页-->
        if ( page_info.current_page == page_info.page_num  ){
            ret_str+=' <a class="page_next page_grey" href="javascript:void(0);">></a> ';
        }else{
            ret_str+=' <a class="page_next"  name="page_btn" data="'+page_info.page.next_url+'" href="javascript:void(0);" >></a>';
        }

        ret_str+="</div>";
    }
    var $node= $(ret_str);


    $node.find("a[data]").on("click", function(){
        var url=$(this).attr("data");
        reload_func(url);
    });

    $node.find("input").on("keypress", function( e){
        if (e.keyCode==13){
            var url=$(this).attr("data");
            var page_num=$(this).val();
            url=url.replace(/{Page}/, page_num  );
            reload_func(url);
        }
    });


    $node.find(".page-opt-show-all"). on("click", function( e){
        var url=$(this).attr("data");
        var page_num=0xFFFFFFFF+1;
        url=url.replace(/{Page}/, page_num  );
        reload_func(url);
    });

    // $node.find(".page-opt-show-all-xls"). on("click", function( e){
    //     var url=$(this).attr("data");
    //     var page_num=0xFFFFFFFF+2;
    //     url=url.replace(/{Page}/, page_num  );
    //     reload_func(url);
    // });


    return $node;
};

function mathjax_show_str(str){
    //image
    str=str .replace(/</g, "&lt"  )
        .replace(/!\[\]\(([^)]*)\)/g, "<img src=\"$1\" />")
        .replace(/\n/g, "<br/>" )
        .replace(/\(    \)/g,"(&nbsp&nbsp&nbsp&nbsp)" )
        .replace(/分析：/g, "<font color=blue>【分析】</font>" ).replace(/\(    \)/g,"(&nbsp&nbsp&nbsp&nbsp)" )
        .replace(/考点：/g, "<font color=blue>【考点】</font>" )
        .replace(/分析：/g, "<font color=blue>【分析】</font>" )
        .replace(/答题：/g, "<font color=blue>【答题】</font>" )
        .replace(/点评：/g, "<font color=blue>【点评】</font>" )
        .replace(/解答：/g, "<font color=blue>【解答】</font>" )
        .replace(/专题：/g, "<font color=blue>【专题】</font>" )
        .replace(/\\degree\b/g, "°" ) // mathjax no surport ..
    ;
    return str+ "<br/><font color=red>==================结束==========</font>";

}

function select_a_split(str){
    var a="",desc="";
    str.replace(/^([A-Za-z0-9])(:?)([\s\S]*)$/ ,
                function($0,$1,$2,$3){
                    a=$1;
                    desc = $3;
                });
    return {
        a:a,
        desc:desc
    };
}




function admin_show_question (questionid ){
    $.ajax({
        type     :"post",
        url      :"/question/get_record",
        dataType :"json",
        data     :{
            "questionid":questionid
        },
        success  : function(result){
            var row=result.data;

            var html_node="";
            var title = "";
            if (row.question_type ==1){
                var tmp_ret=   select_a_split( row.a ) ;
                title=" <span style=\"font-size:24px;\"> 问题:  答案("+ tmp_ret.a +") </span>";
                html_node= $( "<div style=\"font-size:18px;\" > "+ mathjax_show_str( row.q)+"<br/>"+   " <br/> <span style=\"font-size:24px;\"> 解析: </span><br>" +  mathjax_show_str(tmp_ret.desc)  + "</div>" );
            }else{
                title="<span style=\"font-size:24px;\"> 问题:</span><br> ";
                html_node= $( "<div style=\"font-size:18px;\" > "+ mathjax_show_str( row.q)+"<br/>"+  "<br/> <span style=\"font-size:24px;\"> 答案: </span><br>"+    mathjax_show_str( row.a) + "</div>");
            }


            var dlg=BootstrapDialog.show({
                title:title,
                message :  html_node   ,
                //message : "<iframe style=\"width:100%;height:"+($(window).height()-100)+"px ;border: 1px none;\" src=\""+edit_ex_url+"/viewer?show_url=http://"+window.location.hostname+"/question/get_q_a_show&id="+questionid+"\" id=\"iframe1\"> </iframe>"  ,
                closable: true,
                buttons: [{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }]
            });

            /*
              dlg.getModalDialog().css("width","800px");
              dlg.getModalDialog().css("margin-top","10px");
              dlg.getModalDialog().find(".modal-header").hide();
              dlg.getModalDialog().find(".modal-body").css("padding","8px");
              dlg.getModalDialog().find(".modal-footer").css("padding","8px");
              dlg.getModalDialog().find(".modal-footer").css("margin-top","0px");
              dlg.getModalDialog().find(".btn").css("margin-right","30px");
            */


            MathJax.Hub.Queue(
                ["Typeset",MathJax.Hub, html_node[0] ]
            );
        }
    });

}
function admin_show_question_diff(questionid, show_type ){

    if (typeof show_type == 'undefined'   ) {
        show_type =1;
    }

    $.ajax({
        type     :"post",
        url      :"/question/get_record",
        dataType :"json",
        data     :{
            "questionid":questionid
        },
        success  : function(result){
            var row=result.data;

            var html_node="";
            var title = "";
            title="diff";

            var  title1="";
            var  title2="";
            var a, q , bak_a,bak_q;
            if (show_type==1) {
                a=row.check2_bak_a;
                q=row.check2_bak_q;
                if (row.check2_flag) {

                }else{
                    a=row.a;
                    q=row.q;
                }
                bak_a=row.bak_a;
                bak_q=row.bak_q;
                title1="录入提交";
                title2="一审修改";
            }else{
                a=row.a;
                q=row.q;
                bak_a=row.check2_bak_a;
                bak_q=row.check2_bak_q;
                title1="一审提交";
                title2="二审修改";
            }

            if (row.question_type ==1){
                var tmp_ret=   select_a_split( a ) ;
                var bak_tmp_ret=   select_a_split( bak_a ) ;


                html_node= $(

                    " <tr width=\"1000\"> <td width=\"400\"> <span style=\"font-size:24px;\">"+title2+" :问题:  答案("+ tmp_ret.a +") </span> <br>"+ "<div style=\"font-size:18px;\" > "+ mathjax_show_str( q)+"<br/>"+   " <br/> <span style=\"font-size:24px;\"> 解析: </span><br>" +  mathjax_show_str(tmp_ret.desc)  + "</div> </div> </td> "+

                    " <td  width=\"400\"> <span style=\"font-size:24px;\"> "+title1+":问题:  答案("+ bak_tmp_ret.a +") </span> <br>"+ "<div style=\"font-size:18px;\" > "+ mathjax_show_str( q)+"<br/>"+   " <br/> <span style=\"font-size:24px;\"> 解析: </span><br>" +  mathjax_show_str(bak_tmp_ret.desc)  + "</div> </td> </tr>"

                );
            }else{
                html_node=$(  "<tr width=\"1000\">  <td  width=\"400\"> <span style=\"font-size:24px;\"> "+title2+":问题:  </span> <br>"+ "<div style=\"font-size:18px;\" > "+ mathjax_show_str( q)+"<br/>  <span style=\"font-size:24px;\"> 答案: </span><br>"+    mathjax_show_str( a) + "</div> </td> "+
                              " <td  width=\"400\"> <span style=\"font-size:24px;\"> "+title1+":问题:  </span> <br>"+ "<div style=\"font-size:18px;\" > "+ mathjax_show_str( bak_q)+"<br/>  <span style=\"font-size:24px;\"> 答案: </span><br>"+    mathjax_show_str( bak_a) + "</div> </td> </tr>");
            }


            var dlg=BootstrapDialog.show({
                title:title,
                message :  html_node   ,
                //message : "<iframe style=\"width:100%;height:"+($(window).height()-100)+"px ;border: 1px none;\" src=\""+edit_ex_url+"/viewer?show_url=http://"+window.location.hostname+"/question/get_q_a_show&id="+questionid+"\" id=\"iframe1\"> </iframe>"  ,
                closable: true,
                buttons: [{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }]
            });

            dlg.getModalDialog().css("width","1024px");
            /*
              dlg.getModalDialog().css("width","800px");
              dlg.getModalDialog().css("margin-top","10px");
              dlg.getModalDialog().find(".modal-header").hide();
              dlg.getModalDialog().find(".modal-body").css("padding","8px");
              dlg.getModalDialog().find(".modal-footer").css("padding","8px");
              dlg.getModalDialog().find(".modal-footer").css("margin-top","0px");
              dlg.getModalDialog().find(".btn").css("margin-right","30px");
            */


            MathJax.Hub.Queue(
                ["Typeset",MathJax.Hub, html_node[0] ]
            );
        }
    });

}



function get_note_name_list(note_id_arr_str ){
    if (!note_id_arr_str ){
        note_id_arr_str = "";
    }

    var note_id_arr        = note_id_arr_str.split(",");
    var note_name_list_str = "";
    $.each(note_id_arr,function(i,item){
        var note_id = $.trim(item);
        if (note_id != ""){
            note_name_list_str += g_note_name_map[note_id]+"," ;
        }
    });
    return note_name_list_str;
};


function ajax_default_deal_func(result){
    if (result.ret ){
        BootstrapDialog.alert(result['info']);
    }else{
        //alert("提交成功");
        $.reload();
    }
}

function do_ajax_t(url,data ){
    do_ajax(url,data,function(){});
};


function do_ajax(url,data, success_func){
    if (  !success_func) {
        success_func= ajax_default_deal_func ;
    }

    $.ajax({
        url:  url,
        type: 'POST',
        data:data,
        dataType: 'json',
        success: success_func
    });
}


function show_pdf_file (file_url) {
    $.ajax({
        url: '/tea_manage/get_pdf_download_url',
        type: 'GET',
        dataType: 'json',
        data: {'file_url': file_url},
        success: function(ret) {
            if (ret.ret != 0) {
                BootstrapDialog.alert(ret.info);
            } else {
                window.open('/pdf_viewer/?file='+ret.file, '_blank');
            }
        }
    });
}

function get_real_decodeURIComponent( str ){

    var error=false;
    var ret="";
    do {

        error=false;
        try {
            //只需要在此加上编码
            ret = decodeURIComponent(str );
        } catch (e) {
            error=true;
        }
        str= str.replace(/%[^%]*$/, "");
    }while( error &&  str.length >0);

    return ret;
}

function get_note_name_list (note_id_arr_str ){
    if (!note_id_arr_str ){
        note_id_arr_str ="";
    }

    var note_id_arr= note_id_arr_str.split(",");
    var note_name_list_str= "";
    $.each(note_id_arr,function(i,item){
        var note_id=$.trim(item);
        if (note_id!=""){
            note_name_list_str+= g_note_name_map[note_id]+"," ;
        }
    });
    return note_name_list_str;
};

function bind_input_enter_to_btn( src ,obj ){
    $(src).on("keydown",function(e){
        if(e.which==13 ){
            $(obj).click();
        }
    });
}

function  ajax_set_select_box($select,url,url_data,value , not_add_all_flag  ){
    do_ajax( url, url_data
             ,function( data){
                 if (data.ret==0) {
                     var html_str="";

                     if (!not_add_all_flag  ) {
                         html_str="<option value=\"-1\">[全部]</option>";
                     }
                     $.each(data.list, function(i,item){
                         html_str+="<option value=\""+
                             item["k"]+
                             "\">"+item["v"]+ "</option>";
                     });
                     $select.html(html_str);
                     if (value != null) {
                         $select.val(value);
                     }
                 }else{
                     alert(data.ret) ;
                 }
             });
}

function custom_qiniu_upload (btn_id,containerid,domain,is_public,complete_fun,max_file_size ){
    if (!max_file_size  ) {
        max_file_size ='30mb';
    }
    var token_url='';

    if (is_public ) {
        token_url='/upload/pub_token';
    }else{
        token_url='/upload/private_token';
    }
    /*
      if(typeof(domain_data)=="[object String]"){
      domain=domain_data;
      file_size='30mb';
      }else{
      domain=domain_data[0];
      file_size=domain_data[1];
      }
    */
    var uploader = Qiniu.uploader({
        runtimes      : 'html5, flash, html4',
        browse_button : btn_id , //choose files id
        uptoken_url   : token_url ,
        domain        : domain,
        container     : containerid,
        drop_element  : containerid,
        max_file_size : max_file_size,
        dragdrop      : true,
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf',
        chunk_size    : '4mb',
        unique_names  : false,
        save_key      : false,
        auto_start    : true,
        init          : {
            'FilesAdded': function(up, files) {
                plupload.each(files, function(file) {
                    var progress = new FileProgress(file, 'process_info');
                    console.log('waiting...');
                });
            },
            'BeforeUpload': function(up, file) {
                console.log('before uplaod the file');
            },
            'UploadProgress': function(up,file) {
                var progress = new FileProgress(file, 'process_info');
                progress.setProgress(file.percent + "%", up.total.bytesPerSec, btn_id);
                console.log('upload progress');
            },
            'UploadComplete': function() {
                $("#"+btn_id).siblings('div').remove();
                console.log('success');
            },
            'FileUploaded' : function(up, file, info) {
                console.log('Things below are from FileUploaded');
                if(info.response){
                    complete_fun(up, info.response, file );
                }else{
                    complete_fun(up, info, file );
                }
            },
            'Error': function(up, err, errTip) {
                console.log('Things below are from Error');
                console.log(up);
                console.log(err);
                console.log(errTip);
            },
            'Key': function(up, file) {
                var key = "";
                //generate the key
                var time = (new Date()).valueOf();
                console.log('Aaron ' + file.name);
                var match = file.name.match(/.*\.(.*)?/);

                return $.md5(file.name) +time +'.' + match[1];
            }
        }
    });
};

function custom_show_pdf(file_url) {
    $.ajax({
        url      : "/tea_manage/get_pdf_download_url",
        type     : 'GET',
        dataType : 'json',
        data     : {'file_url': file_url},
        success : function(ret) {
            if (ret.ret != 0) {
                BootstrapDialog.alert(ret.info);
            } else {
                var match = file_url.match(/.*\.(.*)?/);
                if (match[1].toLowerCase() != "pdf") {
                    window.open(ret.file_ex, '_blank');
                    return;
                }
                window.open('/pdf_viewer/?file='+ret.file, '_blank');
            }
        }
    });
};

function custom_upload_file(btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list, noti_process ){
    do_ajax( "/common/get_bucket_info",{
        is_public: is_public_bucket ? 1:0
    },function(ret){
        var domain_name=ret.domain;
        var token=ret.token;

        var uploader = Qiniu.uploader({
            runtimes: 'html5, flash, html4',
            browse_button: btn_id , //choose files id
            uptoken: token,
            domain: "http://"+domain_name,
            max_file_size: '30mb',
            dragdrop: true,
            flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
            chunk_size: '4mb',
            unique_names: false,
            save_key: false,
            auto_start: true,
            multi_selection: false,
            filters: {
                mime_types: [
                    {title: "", extensions: ext_file_list.join(",") }
                ]
            },
            init: {
                'FilesAdded': function(up, files) {
                    plupload.each(files, function(file) {
                        console.log('waiting...'+file.name );
                    });
                },
                'BeforeUpload': function(up, file) {
                    console.log('before uplaod the file 11111');
                    var match = file.name.match(/.*\.(.*)?/);
                    var file_ext=match[1];
                    var check_flag=false;
                    $.each ( ext_file_list,  function(i,item) {
                        if ( item.toLowerCase() ==file_ext.toLowerCase()) {
                            check_flag=true;
                        }
                    });
                    if (!check_flag  ) {
                        BootstrapDialog.alert("文件后缀必须是: "+ ext_file_list.join(",") +"<br> 刷新页面，重新上传"  );
                        return false;
                    }
                    console.log('before uplaod the file');
                    return true;

                },
                'UploadProgress': function(up,file) {
                    if(noti_process) {
                        noti_process (file.percent);
                    }
                    console.log(file.percent);
                    console.log('upload progress');
                },
                'UploadComplete': function() {
                    console.log(' UploadComplete .. end ');
                },
                'FileUploaded' : function(up, file, info) {
                    if(noti_process) {
                        noti_process (0);
                    }
                    console.log('Things below are from FileUploaded');
                    if(info.response){
                        complete_func(up, info.response, file ,ctminfo);
                    }else{
                        complete_func(up, info, file,ctminfo );
                    }
                },
                'Error': function(up, err, errTip) {
                    console.log('Things below are from Error');
                    BootstrapDialog.alert(errTip);
                },
                'Key': function(up, file) {
                    var key = "";
                    var time = (new Date()).valueOf();
                    var match = file.name.match(/.*\.(.*)?/);
                    var file_name=$.md5(file.name) +time +'.' + match[1];
                    console.log('gen file_name:'+file_name);
                    return file_name;

                }
            }
        });
    });

};

function multi_upload_file(new_flag,is_multi,is_auto_start,btn_id, is_public_bucket ,select_func,befor_func, complete_func, ext_file,process_id ){
    do_ajax( "/common/get_new_bucket_info",{
        // is_public: is_public_bucket ? 1:0
    },function(ret){
        var domain_name=ret.domain;
        var token=ret.token;
        //保证每次new不同的对象
        var qi_niu = ['Qiniu_'+new_flag];
        // console.log(qi_niu[0]);
        qi_niu[0] = new QiniuJsSDK();
        var uploader = qi_niu[0].uploader({
        // var uploader = Qiniu.uploader({
            // disable_statistics_report: false,
            runtimes: 'html5,flash,html4',
            browse_button: btn_id , //choose files id
            // container: 'container',
            // drop_element: 'container',
            max_file_size: '15mb',
            filters: {
                mime_types: [
                    {title: "", extensions: ext_file}
                ]
            },
            flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
            // dragdrop: true,
            chunk_size: '4mb',
            multi_selection: is_multi,
            uptoken: token,
            domain: "http://"+domain_name,
            get_new_uptoken: false,
            auto_start: is_auto_start,
            // log_level: 5,
            init: {
                'BeforeChunkUpload': function(up, file) {
                    // console.log("before chunk upload:", file.name);
                },
                'FilesAdded': function(up, files) {
                    // $('table').show();
                    // $('#success').hide();
                    //删除单选文件的多余文件
                    var remove_file_id = select_func(files);
                    $(remove_file_id).each(function(i,val){
                        if(val != undefined){
                            uploader.removeFile(val);
                            $('#'+val).remove();
                        }
                    });
                    plupload.each(files, function(file) {
                        var progress = new FileProgress(file, 'fsUploadProgress');
                        progress.setStatus("等待...");
                        progress.bindUploadCancel(up);
                    });

                },
                'BeforeUpload': function(up, file) {

                    var is_remove = befor_func(up, file);
                    if(process_id != '') {
                        var progress = new FileProgress(file, process_id);
                        var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                        if (up.runtime === 'html5' && chunk_size) {
                            progress.setChunkProgess(chunk_size);
                        }

                        if(is_remove > -1){
                            uploader.removeFile(file);
                            $('#'+file.id).remove();
                        }
                   }

                },
                'UploadProgress': function(up, file) {
                    if(process_id != '') {
                        var progress = new FileProgress(file, process_id);
                        var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                        progress.setProgress(file.percent + "%", file.speed, chunk_size);
                    }
                },
                'UploadComplete': function() {
                    // $('#success').show();
                },
                'FileUploaded': function(up, file, info) {
                    if(process_id != '') {
                        var progress = new FileProgress(file, process_id);
                        progress.setComplete(up, info.response,false);
                    }
                    complete_func(up, file, info);

                },
                'Error': function(up, err, errTip) {
                    // $('table').show();
                    BootstrapDialog.alert('抱歉，文件最大不能超过15M哦！');
                    if(process_id != '') {
                        var progress = new FileProgress(err.file, process_id);
                        progress.setError();
                        progress.setStatus(errTip);
                    }
                } ,
                'Key': function(up, file) {
                    var key = "";
                    var time = (new Date()).valueOf();
                    var match = file.name.match(/.*\.(.*)?/);
                    /*
                      if( uploader.on_noti_origin_file_func) {
                      uploader.on_noti_origin_file_func(file.name);
                      }
                    */
                    this.origin_file_name=file.name;
                    var file_name=$.md5(file.name) +time +'.' + match[1];
                    //tapd 1001231
                    //tapd ID：1001335
                    // file_name = '/teacher-doc/'+file_name;
                    console.log('gen file_name:'+file_name);
                    return file_name;

                }
            }
        });

        $('#up_load').on('click', function(){
            if($(this).attr('flag') == new_flag){//保证文件是这次上传的
                uploader.start();
            }
        });
    });

};



function do_ajax_get_nick( type,  id, func) {
    $.ajax({
        type     : "post",
        url      : "/user_manage/get_nick" ,
        dataType : "json",
        data : {
            "type" : type
            ,"id"  : id
        },
        success : function(result){
            var nick = result.nick;
            func(id,nick );
        }});
}

function do_get_env( func) {
    $.ajax({
        type     : "post",
        url      : "/common_new/get_env" ,
        dataType : "json",
        data : {
        },
        success : function(result){
            var env= result.env;
            func(env);
        }});
}


function wopen(url){
    window.open(url);
}

function reload_self_page(args){
    var args_str="";
    var first_flag=true;

    $.each(args, function(key,value){
        if (first_flag) {
            args_str= key +"=" + encodeURIComponent( value);
            first_flag=false;
        }else{
            args_str+= "&"+key +"=" +  encodeURIComponent(value);
        }
    });

    window.location.href=window.location.pathname +"?" +  args_str;
}

//<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

function admin_select_user( $element, type, call_func, is_not_query_flag ) {
    var select_no_select_value = -1;
    var select_no_select_title = "[全部]";
    if (is_not_query_flag) {
        select_no_select_value = 0;
        select_no_select_title = "[未设置]";
    }

    $element.admin_select_dlg_ajax({
        "opt_type"             : "select", // or "list"
        select_no_select_value : select_no_select_value , // 没有选择是，设置的值
        select_no_select_title : select_no_select_title, // "未设置"
        "url"                  : "/user_manage/get_user_list",
        //其他参数
        "args_ex" : {
            type  : type
        },
        select_primary_field : "id",
        select_display       : "nick",

        //字段列表
        'field_list' :[{
            title:"id",
            width :50,
            field_name:"id"
        },{
            title:"性别",
            render:function(val,item) {
                return item.gender;
            }
        },{
            title  : "昵称",
            render : function(val,item) {
                return item.nick;
            }
        },{
            title      : "电话",
            field_name : "phone"
        }],
        //查询列表
        filter_list:[
            [{
                size_class : "col-md-4" ,
                title      : "性别",
                type       : "select" ,
                'arg_name' : "gender"  ,
                select_option_list : [{
                    value : -1 ,
                    text  : "全部"
                },{
                    value : 1 ,
                    text  : "男"
                },{
                    value : 2 ,
                    text  : "女"
                }]},{
                    size_class : "col-md-8" ,
                    title      : "姓名/电话",
                    'arg_name' : "nick_phone"  ,
                    type       : "input"
                }]
        ],
        "auto_close" : true,
        //选择
        "onChange"   : call_func,
        //加载数据后，其它的设置
        "onLoadData" : null
    });
}

$(function(){
    var arr=[];

    var out_line=function(value,fix, $a_node ) {
        var class_name=$a_node.find(">i").attr("class");
        var class_list=class_name.split(" ");
        class_name="";
        $.each(class_list,function(){
            var s=$.trim(this);
            if (s.substr(0,3) == "fa-" ) {
                class_name=s;
            }
        });
        var l_0= Math.floor( value /10000);
        var l_1= Math.floor( value %10000 /100);
        var l_2= Math.floor( value %100 );
        var path_name=$.trim($a_node.text());
        if (fix) {
            path_name= fix+"/"+  path_name;
        }else{

        }
        var fix_arr=path_name.split("/");
        var url=$a_node.attr("href");

        if (l_0!=0) {
            if (!arr[l_0]) {
                arr[l_0] = {
                    "id" : l_0,
                    "name" :  fix_arr[0],
                    "list" :{
                    }
                };
            }
        }

        if (l_1!=0) {
            if (!arr[l_0]["list"][l_1]) {
                arr[l_0]["list"][l_1] = {
                    "id" : l_1,
                    "name" :  fix_arr[1],
                    "list" :{
                    }
                };
            }
        }

        if (l_2!=0) {
            if (!arr[l_0]["list"][l_1]["list"][l_2]) {
                arr[l_0]["list"][l_1]["list"][l_2] = {
                    "id" : l_2,
                    "name" :  fix_arr[2],
                    "list" :{
                    }
                };
            }
        }

        if (l_1==0) { //end l_0
            arr[l_0] = {
                "id" : l_0,
                "name" :  fix_arr[0],
                "icon" :  class_name,
                "url" :  url,
            };
        }else if  ( l_2==0) {
            arr[l_0]["list"][l_1] = {
                "id" : l_1,
                "name" :  fix_arr[1],
                "icon" :  class_name,
                "url" :  url,
            };
        }else{
            arr[l_0]["list"][l_1]["list"][l_2] = {
                "id" : l_2,
                "name" :  fix_arr[2],
                "icon" :  class_name,
                "url" :  url,
            };
        }

    };
    function test() {
        var all=$(".sidebar-menu >li" );

        $.each( all,function (l_0){
            l_0++;
            var $item1=$(this);
            if ($item1.hasClass("treeview")   ) {
                var $item1_a= $item1.find(">a");
                var item1_name= $.trim($item1_a.text());

                $.each( $item1.find("> ul > li"),function (l_1){
                    l_1++;
                    var $item2=$(this);
                    var $item2_a= $item2.find(">a");
                    var item2_name= $.trim($item2_a.text());

                    if ($item2.hasClass("treeview")   ) {

                        $.each( $item2.find("> ul > li"),function (l_2){
                            l_2++;
                            var $item3=$(this);
                            var $item3_a= $item3.find(">a");
                            var item3_name= $.trim($item3_a.text());

                            if ($item3.hasClass("treeview")   ) {
                            }else{
                                out_line( l_0*10000+l_1*100+l_2,item1_name+"/"+item2_name, $item3 .find(">a") );
                            }
                        });

                    }else{
                        out_line(l_0*10000+l_1*100,item1_name, $item2 .find(">a") );
                    }
                });

            }else{
                out_line( l_0*10000,"", $item1 .find(">a") );
            }

        });

    }
    //test();
    //console.log(JSON.stringify( arr ));

    /*
      $(".row-td-field-value >span").each(function(){
      var $this=$(this);
      if ($.trim($this.text()) =="") {
      $this.text("[无]");
      }
      });
    */

    $(".td-sort-item").on("click",function(){
        var order_by_str="";
        var $this=$(this);
        var field_name=$.trim($this.data("field-name"));
        if ($this.hasClass("fa-sort-down")  ) {
            order_by_str= field_name+" "+"asc" ;
        }else{
            order_by_str= field_name+" "+"desc" ;
        }


        g_args.order_by_str=order_by_str;

        load_data();
        return false;

    });
    try {

        if( g_args   && g_args.order_by_str) {
            var arr=g_args.order_by_str.split(" ");
            var $sort_item=$(".td-sort-item[data-field-name="+arr[0]+"]") ;
            $sort_item.removeClass("fa-sort");
            if (arr[1]=="asc") {
                $sort_item.addClass("fa-sort-up");
            }else{
                $sort_item.addClass("fa-sort-down");
            }
        }

    }catch (e){

    }



});

var check_data_to_arr = function(data,check_str){
    var arr=new Array();
    if(String(data).indexOf(check_str)>0){
        arr=data.split(check_str);
    }else{
        arr.push(String(data));
    }
    return arr;
};

var get_new_whiteboard = function (obj_drawing_list){
    var ret = {
        "obj_drawing_list" : obj_drawing_list,
        "get_page"         : function ( pageid,show_pageid){
            var me        = this;
            var div_id    = "drawing_"+ pageid ;
            var page_info = me.draw_page_list[pageid];
            if (!page_info){
                var tmp_div = $(  "<div  class=\"page_item\"  id=\""+div_id+ "\"/>");
                if ( show_pageid &&  pageid != show_pageid ) {
                    tmp_div.hide();
                }

                obj_drawing_list.append( tmp_div );
                me.draw_page_list[ pageid] = {
                    pageid    : pageid
                    ,opt_list : [] //svg_obj_list
                    ,draw : SVG(tmp_div[0]).size(me.width, me.height )
                };

                page_info = me.draw_page_list[pageid];
                page_info.draw.attr("viewBox", "0,0,1024,768" );
                //page_info.draw.
                var text = page_info.draw.text(""+pageid+"/"+me.max_pageid );
                //1024', '768
                text.attr({
                    x : 969,
                    y : 732
                });
            }
            return page_info;
        }
        ,"play_one_svg" : function(item_data,show_pageid){

            var me = this;

            if(item_data.svg_id){
                $("#"+ item_data.svg_id) .show();
                return;
            }

            var page_info = me.get_page(item_data.pageid,show_pageid);


            var draw = page_info.draw;


            var opt_args = item_data.opt_args;
            var id       = "";
            switch( item_data.opt_type  ) {
            case "path"  :
                var path = draw.path( opt_args.d );
                // console.log('path:'+path);
                path.fill( opt_args.fill   ).stroke({ width : opt_args["stroke-width"]  }).attr({
                    "stroke" : opt_args.stroke
                });
                id           = path.id();

                break;

            case "image"  :
                var image = draw.image(opt_args.url,opt_args.width,opt_args.height  );
                image.attr({ x : opt_args.x , y:  opt_args.y });
                id             = image.id();
                break;

            case "eraser"  :
                var eraser = draw.path( opt_args.d );
                eraser.fill( opt_args.fill   ).stroke({ width : opt_args["stroke-width"] , dasharray:opt_args["stroke-dasharray"], color:opt_args["stroke-color"] }).attr({
                    "stroke" : opt_args.stroke
                });
                id=eraser.id();
            default          :
                console.log( "ERROR : " +  item_data.opt_type );
                break;
            }
            item_data.svg_id = id;

        }

        ,"init_to_play" : function(){
            var me = this;

            me.draw_page_list = [];
            me.play_index     = 0;
            me.play_pageid    = -1;
            me.play_svg       = null ;
            me.get_page(1);
            me.show_page(1);

        }
        , "play_next_front" : function(  cur_play_time ){
            var me          = this;
            var front_flag  = false;
            var show_pageid = -1;
            var opt_list    = [];
            while ( me.play_index< me.svg_data_list.length  ){
                var item_data = me.svg_data_list[me.play_index];
                if (item_data.timestamp <= cur_play_time ){ //时间到了已经
                    console.log(cur_play_time);
                    show_pageid = item_data.pageid;
                    opt_list.push(item_data);
                    me.play_index++;
                    front_flag = true;
                }else{
                    break;
                }
            }
            if(show_pageid != -1){
                me.show_page(show_pageid);
                $.each(opt_list,function(i,item_data){
                    me.play_one_svg(item_data ,show_pageid);
                });
            }
            return front_flag;
        }

        , "play_next_back" : function (cur_play_time) {

            //后退处理

            var me             = this;
            var a_show_page_id = -1;
            var opt_list       = [];
            while ( me.play_index>0 ){
                var item_data = me.svg_data_list[me.play_index-1];
                if (item_data.timestamp > cur_play_time ){
                    opt_list.push(item_data);
                    a_show_page_id = item_data.pageid;
                    me.play_index--;
                }else{
                    break;
                }
            }

            if(a_show_page_id != -1){
                me.show_page(a_show_page_id);
                $.each(opt_list,function(i,item_data){
                    $("#"+ item_data.svg_id) .hide();
                });
            }
        }

        ,"play_next" :  function( cur_play_time){
            var me = this;
            //前进处理
            var front_flag = me.play_next_front(cur_play_time);
            if (!front_flag){
                me.play_next_back(cur_play_time);
            }
        }

        ,"show_page" : function( pageid ){
            console.log("PAGEID : "+pageid);
            var me              = this;
            if ( me.play_pageid != pageid ){
                var div_id = "drawing_"+ pageid ;
                me.obj_drawing_list.find("div").hide();
                me.obj_drawing_list.find("#"+div_id ).show();
                me.play_pageid = pageid;
            }
        }

        ,"loadData" : function(  w, h, lession_start_time , xml_file, mp3_file,html_node ){
            var me           = this;
            var audio_node   = html_node.find("audio" );
            me.svg_data_list = [];
            me.height        = h;
            me.width         = w;
            me.max_pageid    = 0;
            me.start_time    = lession_start_time ;
            $.get(xml_file,function(xml){
                var svg_list = $(xml).find("svg") ;

                svg_list.each(function(){
                    var item_data={};
                    var item         = $(this);


                    item_data["pageid"]= Math.floor(item.attr("y")/768)+1;
                    if (me.max_pageid <  item_data["pageid"]){
                        me.max_pageid = item_data["pageid"];
                    }

                    item_data["timestamp"]= item.attr("timestamp")-me.start_time;
                    var opt_item        = item.children(":first");
                    item_data["opt_type"]= $(opt_item)[0].tagName;

                    var stroke_info = opt_item.attr("stroke");
                    if( typeof stroke_info != "undefined" && stroke_info.indexOf("#") == -1){
                        stroke_info = "#" + stroke_info;
                    }
                    var opt_args={};
                    switch( item_data["opt_type"]) {

                    case "path" :
                        //<path fill = "none" stroke="0bceff" stroke-width="4" d="M458.0 235.5Q458.0 235.5 457.0 237.5M457.0 237.5Q456.0 239.5 456.0 242.5M456.0 242.5Q456.0 245.5 455.5 253.2M455.5 253.2Q455.0 261.0 453.0 273.5M453.0 273.5Q451.0 286.0 447.8 301.0M447.8 301.0Q444.5 316.0 441.2 333.0M441.2 333.0Q438.0 350.0 435.8 367.8M435.8 367.8Q433.5 385.5 433.2 402.5"></path>
                        opt_args     = {
                            fill            : opt_item.attr("fill")
                            ,stroke         : stroke_info
                            ,"stroke-width" : opt_item.attr("stroke-width")
                            ,"d"            : opt_item.attr("d")
                            ,"stroke-dasharray" : opt_item.attr("stroke-dasharray")
                            ,"stroke-color" : "FFFFFF"
                        }; break;
                    case "image" :
                        opt_args = {
                            x         : opt_item.attr("x")
                            ,y        : opt_item.attr("y")
                            ,"width"  : opt_item.attr("width")
                            ,"height" : opt_item.attr("height")
                            ,"url"    : opt_item.text()
                        };
                        break;
                    case "eraser" :
                        opt_args  = {
                            fill                : opt_item.attr("fill")
                            ,stroke             : stroke_info
                            ,"stroke-width"     : opt_item.attr("stroke-width")
                            ,"stroke-dasharray" : opt_item.attr("stroke-dasharray")
                            ,"d"                : opt_item.attr("d")
                            ,"stroke-color"     : "white"
                        };
                        break;
                    default :
                        console.log( "ERROR : " +  item_data.opt_type );
                        break;
                    }

                    item_data["opt_args"] = opt_args;

                    me.svg_data_list.push( item_data );
                });

                me.init_to_play();

                //加载mp3
                audiojs.events.ready(function(){
                    var as = audiojs.createAll({}, audio_node  );
                    html_node.find(".audiojs").css("width",""+w+"px" );
                    html_node.find(".scrubber").css("width", (w-174).toString()+"px" );
                    as[0].load( mp3_file  );
                });
            });
        }
    };
    return ret;
};


//下载显示
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
                    if ( i>0 && i< $td_list.length-1 ) {
                        if(td_item.className != 'ellipsis_jiaowu'){
                            // if($(td_item).find('a').text() !== ''){
                            //     row_data.push( $.trim($(td_item).find('a').text()));
                            // }else{
                            //     if($(td_item).parent().parent().parent().attr('class') == 'common-table table table-bordered table-striped'){
                            row_data.push( $.trim( $(td_item).text()) );
                                // }
                            // }
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

$(function () {
    $('.common-table').each(function(){
        $(this).bind("contextmenu copy selectstart", function() {
            // return false;
        });
        // var screen_height=window.screen.availHeight-350;

        // $(this).parent().css({"height":screen_height,"overflow":"auto"});

    });
});

//设置字体颜色
function font_color(font_str,color){
    if(color==undefined){
        color = "red";
    }
    return "<font color="+color+">"+font_str+"</font>";
}

//下载隐藏
function download_hide(){
    $(".page-opt-show-all-xls").hide();
}
