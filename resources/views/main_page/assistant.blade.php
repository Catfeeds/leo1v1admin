@extends('layouts.app')
@section('content')
    <style>
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 40px;
     }
     .panel-green {
         border-color: #5cb85c;
     }
     .panel-green .panel-heading {
         background-color: #5cb85c;
         border-color: #5cb85c;
         color: #fff;
     }
     .panel-green a {
         color: #5cb85c;
     }
     .panel-green a:hover {
         color: #3d8b3d;
     }
     .panel-red {
         border-color: #d9534f;
     }
     .panel-red .panel-heading {
         background-color: #d9534f;
         border-color: #d9534f;
         color: #fff;
     }
     .panel-red a {
         color: #d9534f;
     }
     .panel-red a:hover {
         color: #b52b27;
     }
     .panel-yellow {
         border-color: #f0ad4e;
     }
     .panel-yellow .panel-heading {
         background-color: #f0ad4e;
         border-color: #f0ad4e;
         color: #fff;
     }
     .panel-yellow a {
         color: #f0ad4e;
     }
     .panel-yellow a:hover {
         color: #df8a13;
     }


    </style>


    <section class="content " id="id_content" style="max-width:1200px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                    <button type="button" class="btn btn-warning" id="show_warning_info">点击查看预警信息</button>
                    <input id="id_revisit_warning_type" style="display:none;" />
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-one">{{$warning['warning_type_one']}}</button>
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-two">{{$warning['warning_type_two']}}</button>
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-three">{{$warning['warning_type_three']}}</button>
                </div>

            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary  ">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"> {{$stu_info["status1_count"]*1}}</div>
                                    <div>在读人数</div>
                                </div>
                            </div>
                        </div>
                        <a href="/user_manage/ass_archive_ass" >
                            <div class="panel-footer" >
                                <span class="pull-left">查看详情</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow ">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart  fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{$ret_info["send"]*1}}/{{$ret_info["sent"]*1}}</div>
                                    <div>未处理品/已发货礼品</div>
                                </div>
                            </div>
                        </div>
                        <a href="/user_manage_new/commodity_exchange_management_assistant?date_type=null&opt_date_type=0&start_time={{$start_time}}&end_time={{$end_time}}&gift_type=-1&status=0&assistantid={{$assistantid}}" >
                            <div class="panel-footer" >
                                <span class="pull-left">查看未处理礼品详情</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow ">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart  fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{$used_assign_lesson_count/100}}/{{$assign_lesson_count/100}}</div>
                                    <div> 赠送课时详情 已赠送/待赠送</div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            退费预警
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>退费预警级别</td>
                                        <td>学生数量</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr style="color:#FF0000">
                                        <td>三级</td>
                                        <td><a href="/user_manage/ass_archive_ass?refund_warn=3"><span style="color:#FF0000">{{$refund_warning["three"]}}</span></a></td>
                                    </tr>
                                    <tr style="color:#FFCC33">
                                        <td>二级</td>
                                        <td><a href="/user_manage/ass_archive_ass?refund_warn=2"><span style="color:#FFCC33">{{$refund_warning["two"]}}</span></a></td>
                                    </tr>
                                    <tr style="color:#0099FF">
                                        <td>一级</td>
                                        <td><a href="/user_manage/ass_archive_ass?refund_warn=1"><span style="color:#0099FF">{{$refund_warning["one"]}}</span></a></td>
                                    </tr>
                                    <tr style="color:#0000FF">
                                        <td>总计</td>
                                        <td>{{$refund_warning["total"]}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            

                <div class="col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            本月-回访进度
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>项目</td>
                                        <td>已回访量</td>
                                        <td>目标回访量</td>
                                        <td>已回访通时(分钟)</td>
                                        <td>目标回访通时(分钟)</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>今日</td>
                                        <td id="today_revisit_num">{{@$today_info["revisit_num"]/1}} </td>
                                        <td id="today_goal">{{@$today_info["goal"]}} </td>
                                        <td id="today_call_num">{{@$today_info["call_num"]}} </td>
                                        <td id="today_goal_three">{{@$today_info["goal"]*3}}:00 </td>
                                    </tr>
                                    <tr>
                                        <td>本月</td>
                                        <td  id="month_revisit_num">{{@$month_info["revisit_num"]/1}} </td>
                                        <td id="month_stu_num">{{@$month_info["stu_num"]*2}} </td>
                                        <td  id="month_call_num">{{@$month_info["call_num"]}} </td>
                                        <td  id="month_stu_num_three">{{@$month_info["stu_num"]*6}}:00 </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
               




            </div>
            <!-- <div class="row">
                 <div class="col-xs-12 col-md-12">
                 <div class="panel panel-warning"  >
                 <div class="panel-heading center-title ">
                 薪资提成
                 </div>
                 <div class="panel-body">

                 <table   class="table table-bordered "   >
                 <thead>
                 <tr>
                 <td>排名</td>
                 <td>中文名</td>
                 <td>课耗收入提成</td>
                 <td>扩课奖励</td>
                 <td>续费提成</td>
                 <td>转介绍人头奖励</td>
                 <td>CC签单转介绍提成</td>
                 <td>总提成</td>
                 </tr>
                 </thead>
                 <tbody>
                 @foreach($ass_month as $var)
                 <tr>
                 <td>{{@$var["num_range"]}}</td>
                 <td>{{$var["name"]}}</td>
                 <td>{{$var["lesson_price_money"]}}</td>
                 <td>{{$var["kk_money"]}}</td>
                 <td>{{$var["renw_money"]}}</td>
                 <td>{{$var["tran_num_money"]}}</td>
                 <td>{{$var["cc_tran_price"]}}</td>
                 <td>{{$var["all_money"]}}</td>
                 
                 </tr>
                 
                 @endforeach

                 </tbody>
                 </table>
                 </div>
                 </div>
                 </div>

                 </div>
            -->

            <div class="row">

                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            本月-课时排行榜
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>排名</td>
                                        <td>助教</td>
                                        <td>课时数</td>
                                        <td>人数</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    @foreach ( $lesson_count_list["list"] as $key=> $var )
                                        <tr>
                                            <td> <span> {{$key+1}} </span> </td>
                                            <td  > {{$var["assistant_nick"]}} </td>
                                            <td>{{$var["lesson_count"]/100}} </td>
                                            <td>{{$var["user_count"]}} </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td> </td>
                                        <td  > 总计 </td>
                                        <td>{{$lesson_all/100}} </td>
                                        <td>{{$user_all}} </td>
                                    </tr>

                                </tbody>
                            </table>
                            <div style="font-size:20px">系数:{{$xs}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-7">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            本月-续费排行榜
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>排名</td>
                                        <td>助教</td>
                                        <td>总金额</td>
                                        <td>购买总人数</td>
                                        <td>购买总课时</td>
                                        <td>赠送总课时</td>
                                        <td>总课时</td>
                                    </tr>
                                </thead>
                                <tbody id="id_assistant_renew_list">
                                    @foreach ( $assistant_renew_list as $key=> $var )
                                        <tr>
                                            <td> <span> {{$key+1}} </span> </td>
                                            <td  > {{$var["sys_operator"]}} </td>
                                            <td>{{$var["all_price"]/100}} </td>
                                            <td>{{$var["all_student"]}} </td>
                                            <td>{{$var["bye_total"]/100}} </td>
                                            <td>{{$var["give_total"]/100}} </td>
                                            <td>{{$var["all_total"]/100}} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div style="font-size:20px">续费总金额:{{$all_money_ass/100}}</div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </section>

@endsection
