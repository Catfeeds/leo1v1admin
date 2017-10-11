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
                                @if($seller_top_flag==1)
                                    <tr>
                                        <td>精排消耗情况</td>
                                        <td colspan="2">{{@$top_num}}/40</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>今日需回访个数</td>
                                    <td colspan="2">
                                        <a href="http://admin.yb1v1.com/seller_student_new/seller_student_list_all?{{@$next_time_str}}" target="_blank" style="text-decoration:none;">
                                            {{@$next_revisit_count}}
                                        </a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-warning" style="display:none;" >
                    <div class="panel-heading">
                        本月-签单率排行榜
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td><strong><font class="font_thead" >排名</font></strong></td>
                                    <td><strong><font class="font_thead" >销售人</font></strong></td>
                                    <td><strong><font class="font_thead" >签单率</font></strong></td>
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
                                    <td><strong><font class="font_thead" >排名</font></strong></td>
                                    <td><strong><font class="font_thead" >销售人</font></strong> </td>
                                    <td><strong><font class="font_thead" >签单数</font></strong> </td>
                                    <td><strong><font class="font_thead" >总金额</font></strong> </td>
                                </tr>
                            </thead>
                            <tbody id="id_person_body">
                                @foreach ( $table_data_list as $var )
                                    @if($var['index'] == 1)
                                        <tr>
                                            <td colspan="4">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <p>
                                                            <img src="http://7u2f5q.com2.z0.glb.qiniucdn.com/146ba063e107d23a3d5745f3a1cfbe6b1507697031726.png" width="100%"  alt="" />
                                                            <img src="{{$var["face_pic"]}}" width="100%"  alt="" />
                                                        </p>
                                                        <span style="color:#F6A623;">
                                                        <p>
                                                            <a title="" class=" fa-trophy fa" style="color:#F6A623;" ></a>
                                                        </p>
                                                        <p>
                                                            {{$var["sys_operator"]}}
                                                        </p>
                                                        <p>{{$var["all_count"]}} </p>
                                                        <p>{{$var["all_price"]}} </p>
                                                        </span>
                                                    </div>
                                    @elseif($var['index'] == 2)
                                                    <div class="col-xs-4">
                                                        <p>
                                                            <img src="http://7u2f5q.com2.z0.glb.qiniucdn.com/f19dcf8890a5148d6ede72d04ee3a7ba1507696988396.png" width="100%"  alt="" />
                                                            <img src="{{$var["face_pic"]}}" width="100%"  alt="" />
                                                        </p>
                                                        <span style="color:#9EB0C2;">
                                                        <p>
                                                            <a title="" class=" fa-trophy fa" style="color:#9EB0C2;"></a>
                                                        </p>
                                                        <p> {{$var["sys_operator"]}} </p>
                                                        <p>{{$var["all_count"]}} </p>
                                                        <p>{{$var["all_price"]}} </p>
                                                        </span>
                                                    </div>
                                    @elseif($var['index'] == 3)
                                                    <div class="col-xs-4">
                                                        <p>
                                                            <img src="http://7u2f5q.com2.z0.glb.qiniucdn.com/89aff3cbc879529e2143e7528b9961071507696959137.png" width="100%"  alt="" />
                                                            <img src="{{$var["face_pic"]}}" width="100%"  alt="" />
                                                        </p>
                                                        <span style="color:#CB7F31;">
                                                        <p>
                                                            <a title="" class=" fa-trophy fa" style="color:#CB7F31;"></a>
                                                        </p>
                                                        <p> {{$var["sys_operator"]}} </p>
                                                        <p>{{$var["all_count"]}} </p>
                                                        <p>{{$var["all_price"]}} </p>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                    <tr>
                                        <td>
                                            <span><font style="color:#000000;">{{$var["index"]}}</font> </span>
                                        </td>
                                        <td style="text-align:left">
                                            <img src="{{$var["face_pic"]}}" width="30px" height="30px" alt="" />
                                            <font style="color:#000000;">{{$var["sys_operator"]}}</font>
                                        </td>
                                        <td><font style="color:#000000;">{{$var["all_count"]}}</font> </td>
                                        <td>
                                            <font style="color:#000000;">{{$var["all_price"]}}</font>
                                            <img src="{{$var["level_icon"]}}" width="30px" height="30px" alt="" style="margin:-10% -10% 0% 0%;padding:0px 0px 0px 0px;" />
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                @if( $is_master)
                                    <tr>
                                        <td colspan="4">
                                            <a type="button" href="/tongji/seller_personal_rank" target="_block" class="btn btn-default pull-right" >查看更多</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>



                <div class="panel panel-warning"  style="display:none;">
                    <div class="panel-heading">
                        本旬-个人排行榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td><strong><font class="font_thead" >排名</font></strong></td>
                                    <td><strong><font class="font_thead" >销售人</font></strong> </td>
                                    <td><strong><font class="font_thead" >签单数</font></strong></td>
                                    <td><strong><font class="font_thead" >总金额</font></strong></td>
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
                                    <td><strong><font class="font_thead" >排名</font></strong></td>
                                    <td><strong><font class="font_thead" >团队 </font></strong></td>
                                    <td><strong><font class="font_thead" >签单数</font></strong></td>
                                    <td><strong><font class="font_thead" >总金额</font></strong></td>
                                </tr>
                            </thead>
                            <tbody id="id_group_body">
                                @foreach ( $group_list as $key=> $var )
                                    <tr>
                                        <td>
                                            <span>
                                            @if($key==0)
                                                <a title="" class=" fa-trophy fa" style="color:#F6A623;"></a>
                                            @elseif($key==1)
                                                <a title="" class=" fa-trophy fa" style="color:#9EB0C2;"></a>
                                            @elseif($key==2)
                                                <a title="" class=" fa-trophy fa" style="color:#CB7F31;"></a>
                                            @elseif($key>count($group_list)-4)
                                                <img src="http://7u2f5q.com2.z0.glb.qiniucdn.com/eb805df3bc89d12435e08f04cb1726d81505971183252.png" width="20px" height="20px" alt="" />
                                            @else
                                                {{$key+1}}
                                            @endif
                                            </span>
                                        </td>
                                        <td class="show-group" data-groupid="{{$var["groupid"]}}" style="text-align:left" width="150px">
                                            <img src="{{$var["group_img"]}}" width="30px" height="30px" alt="" />
                                            @if($key==0)
                                                <font style="color:#F6A623;">{{$var["group_name"]}}</font>
                                            @elseif($key==1)
                                                <font style="color:#9EB0C2;">{{$var["group_name"]}}</font>
                                            @elseif($key==2)
                                                <font style="color:#CB7F31;">{{$var["group_name"]}}</font>
                                            @else
                                                {{$var["group_name"]}}
                                            @endif
                                        </td>
                                        <td class="all_count">
                                            @if($key==0)
                                                <font style="color:#F6A623;">{{$var["all_count"]}}</font>
                                            @elseif($key==1)
                                                <font style="color:#9EB0C2;">{{$var["all_count"]}}</font>
                                            @elseif($key==2)
                                                <font style="color:#CB7F31;">{{$var["all_count"]}}</font>
                                            @else
                                                {{$var["all_count"]}}
                                            @endif
                                        </td>
                                        <td class="all_price">
                                            @if($key==0)
                                                <font style="color:#F6A623;">{{$var["all_price"]/100}}</font>
                                            @elseif($key==1)
                                                <font style="color:#9EB0C2;">{{$var["all_price"]/100}}</font>
                                            @elseif($key==2)
                                                <font style="color:#CB7F31;">{{$var["all_price"]/100}}</font>
                                            @else
                                                {{$var["all_price"]/100}}
                                            @endif
                                        </td>
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
                                    <td><strong><font class="font_thead" >排名</font></strong></td>
                                    <td><strong><font class="font_thead" > 销售人 </font></strong></td>
                                    <td><strong><font class="font_thead" >签单数</font></strong></td>
                                    <td><strong><font class="font_thead" >总金额</font></strong></td>
                                </tr>
                            </thead>
                            <tbody id="id_group_self_body">
                                @foreach ( $group_self_list as $key=> $var )
                                    <tr>
                                        <td>
                                            <span>
                                                @if($key==0)
                                                    <a title="" class=" fa-trophy fa" style="color:#F6A623;"></a>
                                                @elseif($key==1)
                                                    <a title="" class=" fa-trophy fa" style="color:#9EB0C2;"></a>
                                                @elseif($key==2)
                                                    <a title="" class=" fa-trophy fa" style="color:#CB7F31;"></a>
                                                @elseif($key>count($group_list)-4)
                                                    <a title="" class=" fa-frown-o fa" ></a>
                                                @else
                                                    {{$key+1}}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if($key==0)
                                                <font style="color:#F6A623;">{{$var["sys_operator"]}}</font>
                                            @elseif($key==1)
                                                <font style="color:#9EB0C2;">{{$var["sys_operator"]}}</font>
                                            @elseif($key==2)
                                                <font style="color:#CB7F31;">{{$var["sys_operator"]}}</font>
                                            @else
                                                <font>{{$var["sys_operator"]}}</font>
                                            @endif
                                        </td>
                                        <td>
                                            @if($key==0)
                                                <font style="color:#F6A623;">{{$var["all_count"]}}</font>
                                            @elseif($key==1)
                                                <font style="color:#9EB0C2;">{{$var["all_count"]}}</font>
                                            @elseif($key==2)
                                                <font style="color:#CB7F31;">{{$var["all_count"]}}</font>
                                            @else
                                                {{$var["all_count"]}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($key==0)
                                                <font style="color:#F6A623;">{{$var["all_price"]/100}}</font>
                                            @elseif($key==1)
                                                <font style="color:#9EB0C2;">{{$var["all_price"]/100}}</font>
                                            @elseif($key==2)
                                                <font style="color:#CB7F31;">{{$var["all_price"]/100}}</font>
                                            @else
                                                {{$var["all_price"]/100}}
                                            @endif
                                        </td>
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
