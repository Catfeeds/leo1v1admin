@extends('layouts.app')
@section('content')
    <style>
     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }
     .font_thead{
         font-size:17px;
         color:#3c8dbc;
     }
    </style>
    <script type="text/javascript" >
     var seller_account = "{{$seller_account}}";
     var group_type = "{{$group_type}}";
     var self_groupid = "{{$self_groupid}}";
     var is_group_leader_flag   = "{{$is_group_leader_flag}}";
    </script>

    <section class="content " id="id_content">

        <div  id="id_query_row">
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
            </div>
        </div>



        <div class="row">

            <div class="col-xs-12 col-md-4">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        本月-我的数据
                    </div>
                    <div class="input-group " id="id_seller_new">
                        <span class="input-group-addon">销售</span>
                        <input id="id_test_seller_id" style="width:100px" class="opt-change" />
                    </div>

                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td style="width:140px"><strong><font class="font_thead">项目</font><strong></td>
                                        <td><strong><font class="font_thead">数值</font><strong></td>
                                            <td><strong><font class="font_thead">公司排名</font><strong></td>
                                </tr>
                            </thead>
                            <tbody id="id_self_body">
                                <tr>
                                    <td>邀约数</td>
                                    <td>{{@$self_top_info[1]["value"]*1}} </td>
                                    <td>{{@$self_top_info[1]["top_index"]}} </td>
                                </tr>

                                <tr>
                                    <td>成功试听数</td>
                                    <td>{{@$self_top_info[2]["value"]*1}}/(目标数:{{$test_lesson_need_count}}) </td>
                                    <td>{{@$self_top_info[2]["top_index"]}} </td>
                                </tr>
                                <tr>
                                    <td>签单数</td>
                                    <td>{{@$self_top_info[4]["value"]*1}} </td>
                                    <td>{{@$self_top_info[4]["top_index"]}} </td>
                                </tr>

                                <tr>
                                    <td>转化率</td>
                                    <td>{{@$self_top_info[5]["value"]*1}}%</td>
                                    <td>{{@$self_top_info[5]["top_index"]}} </td>
                                </tr>
                                <tr>
                                    <td>月度试听取消率</td>
                                    <td>
                                        {{@$self_top_info[10]["value"]*1}}%
                                        <a href="javascript:;" id="id_show_fail_lesson_list">  ({{@$self_top_info[12]["value"]*1}}/{{@$self_top_info[11]["value"]*1}})  </a>

                                    </td>
                                    <td>{{@$self_top_info[10]["top_index"]}} </td>
                                </tr>
                                <tr rowspan="2">
                                    <td >上周({{@$start_time}}-{{@$end_time}})试听取消率</td>
                                    <td >
                                        {{@$self_top_info[15]["value"]*1}}%
                                        <a href="javascript:;" id="id_show_fail_lesson_list">  ({{@$self_top_info[14]["value"]*1}}/{{@$self_top_info[13]["value"]*1}})  </a>

                                    </td>
                                    <td >{{@$self_top_info[15]["top_index"]}} </td>
                                </tr>
                                <tr>
                                    <td colspan="3">再做<font color="red">{{@$self_money["differ_price"]}}</font>业绩可多赚约<font color="red">{{@$self_money["differ_money"]}}</font>元 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


        </div>




    </section>

@endsection
