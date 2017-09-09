@extends('layouts.app')
@section('content')
    <style>
     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }
    </style>
    <script type="text/javascript" >
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
            <div class="panel panel-danger"  >
                <div class="panel-heading">
                    <font color="#333" >本月 - 我的业绩:</font>
                    <span style="color:red ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >　{{$self_info["all_price"]*1}}　</span>
                    <font color="#333" >成交单数:</font>
                    <span style="color:red ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >　{{$self_info["all_count"]*1}}　</span>

                    <font color="#333" >排名:</font>
                    <span style="color:red ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >　{{ @$self_top_info[6]["top_index"]*1}}　</span>


                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-md-4">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        本月-我的数据
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>项目</td>
                                    <td>数值</td>
                                    <td>公司排名</td>
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
                                    <td>试听取消率</td>
                                    <td>
                                        {{@$self_top_info[10]["value"]*1}}%
                                        <a href="javascript:;" id="id_show_fail_lesson_list">  ({{@$self_top_info[12]["value"]*1}}/{{@$self_top_info[11]["value"]*1}})  </a>

                                    </td>
                                    <td>{{@$self_top_info[10]["top_index"]}} </td>
                                </tr>
                                <tr>
                                    <td colspan="3">再做<font color="red">{{@$self_money["differ_price"]}}</font>业绩可多赚约<font color="red">{{@$self_money["differ_money"]}}</font>钱 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        本月-签单率排行榜
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td> 销售人 </td>
                                    <td>签单率</td>
                                </tr>
                            </thead>
                            <tbody id="id_ret_info_num">
                                @foreach ( $ret_info_num as $key=> $var )
                                    <tr>
                                        <td> {{$key+1}} </td>
                                        <td>{{@$var["admin_nick"]}} </td>
                                        <td>{{@$var["value"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>



            </div>
            <div class="col-xs-12 col-md-4">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        本月-个人排行榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>销售人 </td>
                                    <td>签单数</td>
                                    <td>总金额</td>
                                </tr>
                            </thead>
                            <tbody id="id_person_body">
                                @foreach ( $table_data_list as $var )
                                    <tr>
                                        <td> <span> {{$var["index"]}} </span> </td>
                                        <td>{{$var["sys_operator"]}} </td>
                                        <td>{{$var["all_count"]}} </td>
                                        <td>{{$var["all_price"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>



                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        本旬-个人排行榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>销售人 </td>
                                    <td>签单数</td>
                                    <td>总金额</td>
                                </tr>
                            </thead>
                            <tbody id="id_person_body">
                                @foreach ( $half_week_info as $key=> $var )
                                    <tr>
                                        <td> <span> {{ $key+1 }} </span> </td>
                                        <td>{{$var["sys_operator"]}} </td>
                                        <td>{{$var["all_count"]}} </td>
                                        <td>{{$var["all_price"]}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>


            </div>

            <div class="col-xs-12 col-md-4">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        本月-团队排行榜
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>团队 </td>
                                    <td>签单数</td>
                                    <td>总金额</td>
                                </tr>
                            </thead>
                            <tbody id="id_group_body">
                                @foreach ( $group_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td class="show-group" data-groupid="{{$var["groupid"]}}"> {{$var["group_name"]}} </td>
                                        <td class="all_count">{{$var["all_count"]}} </td>
                                        <td class="all_price">{{$var["all_price"]/100}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        本月-[{{$group_name}}]排行榜
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td> 销售人 </td>
                                    <td>签单数</td>
                                    <td>总金额</td>
                                </tr>
                            </thead>
                            <tbody id="id_group_self_body">
                                @foreach ( $group_self_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{$var["sys_operator"]}} </td>
                                        <td>{{$var["all_count"]}} </td>
                                        <td>{{$var["all_price"]/100}} </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-md-4">
            </div>
        </div>



    </section>

@endsection
