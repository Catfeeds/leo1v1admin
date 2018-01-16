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
      "/js/echarts.min.js",
      "/js/flot/jquery.flot.min.js",
      "/js/flot/jquery.flot.categories.js",
      "/js/flot/jquery.flot.time.js",
    ], call_func);
  }
  //
  do_load_data_end () {
    this.show_line();
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
  }


  show_line() {

    var myChart = echarts.init(document.getElementById('id_pic_user_count'));

    var real_data= this.$data.data_ex_list.time_list[0];
    var def_data= this.$data.data_ex_list.time_list[1];
    var need_deal_count_data = this.$data.data_ex_list.time_list[2];

    var end_time=$.DateFormat(new Date(  this.get_args().start_time  ).getTime()/1000 + 86400 -3600*8, "yyyy-MM-dd hh:mm"   );
    //一
    var anchor = [
      { value:[this.get_args().start_time , 0]},
      { value:[ end_time , 0]}
    ];

    var option = {
      title: {
        text: ''
      },
      grid: {
        left: '10px',
        right: '30px',
        buttom: '10px',
        y:"20px",
        containLabel: true,

      },
      /*
        grid:{
        "show" :true,
        x: '7%', y: '7%', width: '38%', height: '38%'
        },
      */
      tooltip: {
        trigger: 'axis',
        /*
          formatter: function (params) {
          params = params[0];
          var date = new Date(params.name);
          return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' : ' + params.value[1];
          },
        */
        axisPointer: {
          animation: false
        }
      },
      legend: {
        data:['实际课数', '预期课数',"课后视频未处理"],
        x: 'left'
      },
      xAxis: {
        type: 'time',
        splitLine: {
          show: true
        },
        //gridIndex: 0
      },
      yAxis: {
        type: 'value',
        boundaryGap: [0, 0],
        splitLine: {
          show: true
        },
        max: function(value) {
          return Math.floor( value.max *1.2) ;
        }
      },
      series: [{
        name: '预期课数',
        type: 'line',
        showSymbol: false,
        hoverAnimation: false,
        data: def_data,
        lineStyle:{normal:{width:1 } }

      },{ //
        name: '实际课数',
        type: 'line',
        showSymbol: false,
        hoverAnimation: false,
        data: real_data,
        lineStyle:{normal:{width:2 } }

      },{ //
        name: '课后视频未处理',
        type: 'line',
        showSymbol: false,
        hoverAnimation: false,
        data: need_deal_count_data

      },{ //用于隐藏
        name:'.anchor',
        type:'line',
        showSymbol:false,
        data:anchor,
        itemStyle:{normal:{opacity:0}},
        lineStyle:{normal:{opacity:0}}
      }]
    };
    myChart.setOption(option);
  }
}
