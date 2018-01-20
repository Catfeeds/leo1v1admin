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







$(function(){
  if (getCookie ("show-menu") == "false" ){
    //$(".sidebar-toggle").click();
    $("body").addClass("sidebar-collapse");
  }

  if ($.datetimepicker) {
    $.datetimepicker.setLocale("ch");
  }

  //table_init();

  //hide_menu_by_power_list();

  //$(".treeview").show();

  reset_item();
  $( window ).bind("resize",reset_item);



  if(window.location.host=="admin.leo1v1.com"
     || window.location.host=="admin-vue.leo1v1.com"
     || window.location.host=="admin-tongji.leo1v1.com"
    ){
    $("#id_version_switch").text("切换到冒烟版本");
    $("#id_version_switch").on("click",function(){
      window.location.href=window.location.href.replace("//admin", "//p.admin");
    });
  }else{
    $("#id_version_switch").text("切换到稳定版本");
    $("#id_version_switch").on("click",function(){
      window.location.href=window.location.href.replace("//p.admin", "//admin");
    });
  }


  $("#id_self_menu_add").on("click",function(){
    var title= $(".treeview-menu .active").text();
    var url=   window.location.href.split("?")[0];
    $.do_ajax("/self_manage/self_menu_add",{
      "title" : title,
      "url" : url,
    },function(){
      $.do_ajax("/user_deal/reload_account_power",{},function(){
        $.reload();
      });
    });

  });


  $("#id_now_refresh").on("click",function(){
    if(window.localStorage){
      var flag_key = "query_flag_"+ window.location.pathname;
      var load_data_flag = window.localStorage.getItem( flag_key );
      var list_type_key = "query_list_type_"+ window.location.pathname;
      var list_type = window.localStorage.getItem(list_type_key  );
      var $list_type=$("<select  > <option value=0>紧凑</option> <option value=1>列表</option> <option value=2>超级紧凑</option>  </select> ");


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
          $.do_ajax("/ajax_deal2/set_admin_menu_config",{
            "menu_config" : select_list.join(","),
          },function(){
            $.reload();
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
    $.do_ajax("/user_deal/reload_account_power",function(){
      $.reload();
    });
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
          $.do_ajax(
            '/login/logout',
            {},
             function(data) {
              if (data['ret'] == 0) {
                $.reload();
              } else {
                console.log(data);
              }
            }
          );
        }
      }]
    });

  });



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



function reset_item (){
  if ($.check_in_phone()) {
    $(".remove-for-xs").hide();
    $(".remove-for-not-xs").show();
  }
  if (!$.check_in_phone()) {
    $(".remove-for-not-xs").hide();
    $(".remove-for-xs").show();
  }
};





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
    var int_id_list=[]
    $.each( id_list,function(i,v){
      int_id_list.push( parseInt(v));
    });
    console.log(int_id_list);

    $.each(desc_map, function(k,v){
      if ($.isArray( id_list)) {
        if($.inArray( parseInt(k), int_id_list ) != -1 ){
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
    //console.log(group_name);
    var desc_map=g_enum_map[group_name]["v2s_map"];

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
