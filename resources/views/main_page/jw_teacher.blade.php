@extends('layouts.app')
@section('content')
    <style>
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 35px;
     }
     .subjects {
         font-size: 20px;
         text-align:center;
     }
     .plan_font{
         font-size: 18px;
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

     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }

    </style>


    <section class="content " id="id_content" style="max-width:1400px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
            </div>
            <hr/>
 
            <div class="row">
                <div class="panel panel-danger"  >
                    <div class="panel-heading">
                        <font color="#333" >本月未排总量:</font> 
                        <span style="color:red ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >　{{@$none_total}}　</span> 
                        <font color="#333" >本月未分配总量:</font> 
                        <span style="color:red ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >　{{@$no_assign_total}}　</span> 

                        <font color="#333" >本月教务已排量:</font> 
                        <span style="color:blue ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >　{{$all_total}}　</span> 

                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            本月-排课转化率排行榜&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                            <button id="id_tongji" class="btn btn-primary" style="display:none">统计转化量</button>
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>排名</td>
                                        <td>教务</td>
                                        <td>中文名</td>
                                        <td>总排课量</td>
                                        <td>已排课程</td>
                                        <td>已排课程(绿色)</td>
                                        <td>已排课程(销售绿色)</td>
                                        <td>已排课程(助教绿色)</td>
                                        <td>待排量</td>
                                        <td>挂起量</td>
                                        <td>退回量</td>
                                        <td>排课完成率</td>
                                        <td>排课转化量</td>
                                        <td>排课转化量(销售)</td>
                                        <td>排课转化量(助教)</td>
                                        <td>排课转化量(绿色通道)</td>
                                        <td>排课转化量(销售绿色)</td>
                                        <td>排课转化量(助教绿色)</td>
                                        <td>排课转化率</td>
                                        <td>精排已排</td>
                                        <td>精排未排</td>
                                        <td>精排转化</td>
                                        <td>精排转化率</td>
                                    </tr>
                                </thead>
                                <tbody id="id_per_count_list">
                                    @foreach ( $ret_info as $key=> $var )
                                        <tr>
                                            <td> <span> {{$key+1}} </span> </td>
                                            <td>{{@$var["account"]}} </td> 
                                            <td>{{@$var["name"]}} </td> 
                                            <td class="all_count">{{@$var["all_count"]}} </td> 
                                            <td>{{@$var["set_count"]}} </td>
                                            <td>{{@$var["green_count"]}} </td>
                                            <td>{{@$var["seller_green_count"]}} </td>
                                            <td>{{@$var["ass_green_count"]}} </td>
                                            <td>{{@$var["un_count"]}} </td>
                                            <td>{{@$var["gz_count"]}} </td>
                                            <td>{{@$var["back_count"]}} </td>
                                            <td>{{@$var["set_per"]}} </td>
                                            <td class="order_lesson" data-adminid={{@$var["accept_adminid"]}} ><a href="javascript:;" >{{@$var["tra_count"]}}</a></td>
                                            <td class="tra_count_seller" data-adminid={{@$var["accept_adminid"]}}  ><a href="javascript:;" >{{@$var["tra_count_seller"]}}</a> </td>
                                            <td class="tra_count_ass" data-adminid={{@$var["accept_adminid"]}} ><a href="javascript:;" >{{@$var["tra_count_ass"]}}</a> </td>
                                            <td class="tra_count_green" data-adminid={{@$var["accept_adminid"]}} ><a href="javascript:;" >{{@$var["tra_count_green"]}}</a> </td>
                                            <td class="tra_count_green" data-adminid={{@$var["accept_adminid"]}} ><a href="javascript:;" >{{@$var["seller_green_tran_count"]}}</a> </td>
                                            <td class="tra_count_green" data-adminid={{@$var["accept_adminid"]}} ><a href="javascript:;" >{{@$var["ass_green_tran_count"]}}</a> </td>

                                            <td class="tra_per_str">{{@$var["tra_per_str"]}} </td>
                                            <td class="top_count" data-adminid={{@$var["accept_adminid"]}} ><a href="javascript:;" >{{@$var["top_count"]}}</a> </td>
                                            <td>{{@$var["top_un_count"]}} </td>
                                            <td>{{@$var["tran_count_seller_top"]}} </td>
                                            <td>{{@$var["top_per"]}} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="panel-heading center-title ">
                        当天未排试听课时间段 <span>(合计:{{$cur_num}})</span>
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>时间段</td>
                                    <td>试听课数量</td>
                                </tr>
                            </thead>
                            <tbody id="id_per_count">
                                @foreach ( $test_lesson_info as  $var )
                                    <tr>
                                        <td>{{@$var["hour"]}}</td>
                                        <td>{{@$var["num"]}}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
               
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel-heading center-title ">未来6天未排试听课时间段</div>
                </div>
                @foreach ( $test_week as  $k=>$var )
                <div class="col-xs-12 col-md-2">
                    
                    <div class="panel-heading center-title ">
                        {{@$k}} <span>(合计:{{$test_num[$k]}})</span>

                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>时间段</td>
                                    <td>试听课数量</td>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach ( @$var as  $k=>$v )
                                    <tr>
                                        <td>{{@$k}}</td>
                                        <td>{{@$v}}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
                
            </div>

            
           


        </div>

    </section>
    
@endsection



