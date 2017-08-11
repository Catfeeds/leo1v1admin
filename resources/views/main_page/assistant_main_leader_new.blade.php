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


     #id_content .panel-body {
         text-align:center;
     }

    </style>

  


    <section class="content " id="id_content" style="max-width:1200px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
            </div>
            <hr/>
           

            <div class="row">

                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            学员留存
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>考核项</td>
                                        <td>系统量</td>
                                    </tr>
                                </thead>
                                <tbody >
                                        <tr>
                                            <td>在册学员</td> 
                                            <td>{{$stu_info["all_student"]}} </td>
                                        </tr>
                                        <tr>
                                            <td>有效学员(截止上月底)</td> 
                                            <td>{{$stu_info["read_student_last"]}} </td>
                                        </tr>
                                        <tr>
                                            <td>在读学员</td> 
                                            <td>{{$stu_info["lesson_student"]}} </td>
                                        </tr>

                                        <tr>
                                            <td>停课学员(本月)</td> 
                                            <td>{{$stu_info["month_stop_student"]}} </td>
                                        </tr>
                                        <tr>
                                            <td>停课学员(累积)</td> 
                                            <td>{{$stu_info["stop_student"]}} </td>
                                        </tr>
                                        <tr>
                                            <td>退费</td> 
                                            <td>{{@$stu_info["refund_student"]}} </td>
                                        </tr>
                                        <tr>
                                            <td>扩课成功数</td> 
                                            <td>{{@$stu_info["kk_suc"]}} </td>
                                        </tr>
                                        <tr>
                                            <td>扩课申请数</td> 
                                            <td>{{@$stu_info["kk_require"]}} </td>
                                        </tr>


                                        

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            现金流任务
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>考核项</td>
                                        <td>系统量</td>
                                        <td>实际量</td>
                                        <td>完成率</td>
                                    </tr>
                                </thead>
                                <tbody id="id_assistant_renew_list">
                                    <tr>
                                        <td>课时系数</td> 
                                        <td>{{@$stu_info["lesson_target"]}} </td>
                                        <td>{{@$stu_info["lesson_ratio"]}} </td>
                                        <td>{{@$stu_info["lesson_per"]}}% </td>
                                    </tr>
                                    <tr>
                                        <td>有效学员课耗</td>
                                        <td colspan="3">{{@$stu_info["lesson_total_old"]}} </td>
                                    </tr>

                                    <tr>
                                        <td>课时累计/收入</td> 
                                        <td colspan="3">{{@$stu_info["lesson_total"]}}&nbsp/&nbsp{{@$stu_info["lesson_money"]}} </td>
                                    </tr>
                                    <tr>
                                        <td>续费任务</td> 
                                        <td>{{@$stu_info["renw_target"]}} </td>
                                        <td>{{@$stu_info["all_price"]}} </td>
                                        <td>{{@$stu_info["renw_per"]}}% </td>
                                    </tr>
                                    <tr>
                                        <td>续费人数</td> 
                                        <td>{{@$stu_info["renw_stu_target"]}}</td>
                                        <td>{{@$stu_info["renw_student"]}} </td>
                                        <td>{{@$stu_info["renw_stu_per"]}}% </td>
                                    </tr>
                                    <tr>
                                        <td>交接单周课时信息</td> 
                                        <td>人数/课时</td>
                                        <td>{{@$stu_info["except_num"]}} </td>
                                        <td>{{@$stu_info["except_count"]/100}} </td>
                                    </tr>
                                    <tr>
                                        <td>新签学生信息</td> 
                                        <td>新签人数/购买课时</td>
                                        <td>{{@$stu_info["new_student"]}} </td>
                                        <td>{{@$stu_info["new_lesson_count"]}} </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="panel panel-warning"  >
                            <div class="panel-heading center-title ">
                                各组明细
                            </div>
                            <div class="panel-body">

                                <table   class="table table-bordered "   >
                                    <thead>
                                        <tr>
                                            <td>排名</td>
                                            <td>小组</td>
                                            <td>在册学员</td>
                                            <td>有效学员</td>
                                            <td>在读学员</td>
                                            <td>结课学员</td>
                                            <td>停课学员(本月)</td>
                                            <td>退费人数</td>
                                            <td>预警学员</td>
                                            <td>有效学员课耗</td>
                                            <td>本月总课耗</td>
                                            <td>本月课耗收入</td>
                                            <td>完成系数</td>
                                            <td>系数完成率</td>
                                            <td>续费人数</td>
                                            <td>续费金额</td>
                                            <td>转介绍金额</td>
                                            <td>扩课成功数</td>
                                            <td>扩课申请数</td>
                                            <td>新签退费金额</td>
                                            <td>续费退费金额</td>
                                        </tr>
                                    </thead>
                                    <tbody id="id_ass_group">
                                        @foreach ( $ass_group as $key=> $var )
                                            <tr>
                                                <td> <span> {{$key+1}} </span> </td>
                                                <td  > {{@$var["account"]}}组 </td> 
                                                <td>{{@$var["all_student"]}} </td>
                                                <td>{{@$var["read_student_last"]}} </td>
                                                <td>{{@$var["lesson_student"]}} </td>
                                                <td>{{@$var["end_stu_num"]}} </td>
                                                <td>{{@$var["month_stop_student"]}} </td>
                                                <td>{{@$var["refund_student"]}} </td>
                                                <td>{{@$var["warning_student"]}} </td>
                                                <td>{{@$var["lesson_total_old"]}} </td>
                                                <td>{{@$var["lesson_total"]}} </td>
                                                <td>{{@$var["lesson_money"]}} </td>
                                                <td  class="per" data-per="{{@$var["lesson_per"]}}" >
                                                    <a href="javascript:;" >{{@$var["lesson_ratio"]}}</a>
                                                </td>
                                                <td  class="per" data-per="{{@$var["lesson_per"]}}" >
                                                    <a href="javascript:;" >{{@$var["lesson_per"]}}%</a>
                                                </td>

                                                <td  class="per" data-per="{{@$var["return_stu_per"]}}" >
                                                    <a href="javascript:;" >{{@$var["renw_student"]}}</a>
                                                </td>
                                                <td  class="per" data-per="{{@$var["renw_per"]}}" >
                                                    <a href="javascript:;" >{{@$var["renw_price"]}}</a>
                                                </td>

                                                <td>{{@$var["tran_price"]}}</td>
                                               
                                                <td>{{@$var["kk_suc"]}}</td>
                                                <td>{{@$var["kk_require"]}}</td>
                                                <td>{{@$var["new_refund_money"]}} </td>
                                                <td>{{@$var["renw_refund_money"]}} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="panel panel-warning"  >
                            <div class="panel-heading center-title ">
                                组员明细
                            </div>
                            <div class="panel-body">

                                <table   class="table table-bordered "   >
                                    <thead>
                                        <tr>
                                            <td>排名</td>
                                            <td>英文名</td>
                                            <td>助教</td>
                                            <td>在册学员</td>
                                            <td>有效学员</td>
                                            <td>在读学员</td>
                                            <td>结课学员</td>
                                            <td>停课学员(本月)</td>
                                            <td>退费人数</td>
                                            <td>预警学员</td>
                                            <td>有效学员课耗</td>
                                            <td>本月总课耗</td>
                                            <td>本月课耗收入</td>
                                            <td>完成系数</td>
                                            <td>系数完成率</td>
                                            <td>续费目标人数</td>
                                            <td>续费人数</td>
                                            <td>续费目标金额</td>
                                            <td>续费金额</td>
                                            <td>转介绍金额</td>
                                            <td>扩课成功数</td>
                                            <td>扩课申请数</td>
                                            <td>新签退费金额</td>
                                            <td>续费退费金额</td>
                                        </tr>
                                    </thead>
                                    <tbody id="id_ass_list">
                                        @foreach ( $ass_list as $key=> $var )
                                            <tr>
                                                <td> <span> {{$key+1}} </span> </td>
                                                <td  > {{@$var["account"]}} </td> 
                                                <td  > {{@$var["nick"]}} </td> 
                                                <td>{{@$var["all_student"]}} </td>
                                                <td>{{@$var["read_student_last"]}} </td>
                                                <td>{{@$var["lesson_student"]}} </td>
                                                <td>{{@$var["end_stu_num"]}} </td>
                                                <td>{{@$var["month_stop_student"]}} </td>
                                                <td>{{@$var["refund_student"]}} </td>
                                                <td>{{@$var["warning_student"]}} </td>
                                                <td>{{@$var["lesson_total_old"]}} </td>
                                                <td>{{@$var["lesson_total"]}} </td>
                                                <td>{{@$var["lesson_money"]}} </td>
                                                <td  class="per" data-per="{{@$var["lesson_per"]}}" >
                                                    <a href="javascript:;" >{{@$var["lesson_ratio"]}}</a>
                                                </td>
                                                <td  class="per" data-per="{{@$var["lesson_per"]}}" >
                                                    <a href="javascript:;" >{{@$var["lesson_per"]}}%</a>
                                                </td>

                                                <td>{{@$var["renw_stu_target"]}}</td>
                                                <td  class="per" data-per="{{@$var["return_stu_per"]}}" >
                                                    <a href="javascript:;" >{{@$var["renw_student"]}}</a>
                                                </td>
                                                <td>{{@$var["renw_target"]}}</td>
                                                <td  class="per" data-per="{{@$var["renw_per"]}}" >
                                                    <a href="javascript:;" >{{@$var["renw_price"]}}</a>
                                                </td>
                                                <td>{{@$var["tran_price"]}}</td>

                                                <td class="opt_kk_suc" data-uid='{{@$var["uid"]}}'>
                                                    <a href="javascript:;" >{{@$var["kk_suc"]}}</a>
                                                </td>
                                                <td>{{@$var["kk_require"]}}</td>
                                                <td>{{@$var["new_refund_money"]}} </td>
                                                <td>{{@$var["renw_refund_money"]}} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
             
        </div>
    </section>
    
@endsection



