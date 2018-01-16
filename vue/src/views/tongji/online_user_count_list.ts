import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/tongji-online_user_count_list"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./online_user_count_list.html" ),
})
export default class extends vtable {

  loading_selector="#id_pic_user_count";

  get_args() :self_Args  {return  this.get_args_base();}
  do_created_ex(call_func) {
    this.load_admin_js_list([
      "/js/flot/jquery.flot.min.js",
      "/js/flot/jquery.flot.categories.js",
      "/js/flot/jquery.flot.time.js",
    ], call_func);
  }
  //
  do_load_data_end () {
    this.show_plot();
  }

  data_ex() {
    //扩展的 data  数据
    var me=this;

    return {
      "data_ex_list" :[],
    }
  }
  opt_edit( e:MouseEvent, opt_data: self_RowData ){
    alert(JSON.stringify( opt_data));
  }

  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;
    $.admin_date_select ({
    'join_header'  : $header_query_info,
    'title' : "时间",
    'date_type' : this.get_args().date_type,
    'opt_date_type' : this.get_args().opt_date_type,
    'start_time'    : this.get_args().start_time,
    'end_time'      : this.get_args().end_time,
    date_type_config : JSON.parse(this.get_args().date_type_config),
    as_header_query :true,
    });
  }

  js_loaded() {
    this.show_plot();
  }

  show_plot( ) {
    var me =this;
    var id_name="id_pic_user_count";
    var plot_data_list :Array<any>=[];
    var start_time=$.strtotime( this.get_args().start_time);

    var online_count_list: Array<any>=[];
    console.log( "KKKKKKK LLLLL " );

    $.each( this.$data.data_ex_list.time_list,function(j,item_list){
        //(i*300)*1000+86400-3600*8
        online_count_list[j]=[];
        $.each(item_list ,function(i, item){
            if (j==0) {
                online_count_list[j].push([i*60000, item["online_count"] ]);
            }else if(j==2){
                online_count_list[j].push([i*60000, item["value"] ]);
            }else{
                online_count_list[j].push([i*300000, item ]);
            }
        } )
    });


    $.each( online_count_list , function(i,item_list)   {

      //var date=$.DateFormat( start_time-i*86400, "MM-dd" );
      if (i==0) {
        plot_data_list.push({
          data: online_count_list[0],
          lines: { show: true,
                   lineWidth: 2},
          label: "实际",

          color: "red",
        });
      }else if (i==1 ){
        plot_data_list.push({
          data: online_count_list[i],
          lines: { show: true,
                   lineWidth:1
                 },
          label: "预期",
        });
      }else{
        plot_data_list.push({
          data: online_count_list[i],
          lines: { show: true,
                   lineWidth:1
                 },
          label: "课后视频未处理",
        });
      }

    });

    var plot=$.plot("#"+id_name, plot_data_list.reverse() , {
      series: {
        lines: {
          show: true
        },

        points: {
          show: false
        }

      }, yaxes: [{
        min: 0
      }], xaxis: {
        mode: "time",
        timeformat: "%H:%M",
        minTickSize: [1, "hour"]
      },
      legend: {
        show: true ,
        position:"nw"
      },

      grid: {
        hoverable: true,
        clickable: true,
        backgroundColor: { colors: [ "#fff", "#eee" ] },
        borderWidth: {
          top: 1,
          right: 1,
          bottom: 2,
          left: 2
        }

      }
      ,shadowSize:0

    });

    $("<div id='tooltip'></div>").css({
      position: "absolute",
      display: "none",
      border: "1px solid #fdd",
      padding: "2px",
      "background-color": "#fee",
      opacity: 0.80
    }).appendTo("body");

    $("#"+id_name).bind("plothover", function (event, pos, item) {
      if (item) {
        var data_item=item.series.data[item.dataIndex];


        var title_funcion=function( date_item) {
          return "时间:"+ item.series.label+" "+ $.DateFormat( (data_item[0]) /1000+57600 ,"hh:mm")  + "<br/>课数:"+ data_item[1];
        }
        $("#tooltip").html( title_funcion(data_item) ).css({top: item.pageY+5, left: item.pageX+5})
          .fadeIn(200);
      } else {
        $("#tooltip").hide();
      }
    });


    /*
      $("#id"+id_name).bind("plotclick", function (event, pos, item) {
      if (item) {
      $("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
      plot.highlight(item.series, item.datapoint);
      }
      });
    */
  }
}
