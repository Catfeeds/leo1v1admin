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




    <section class="content " id="id_content" style="max-width:1400px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div  class="col-xs-12 col-md-5">
                    <input id="id_revisit_warning_type" style="display:none;" />
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-one">{{$warning['warning_type_one']}}</button>
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-two">{{$warning['warning_type_two']}}</button>
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-three">{{$warning['warning_type_three']}}</button>
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
                                        <td>{{$stu_info["read_student"]}} </td>
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
                                        <td colspan="3">{{@$stu_info["lesson_total_old"]}}</td>
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
                                        <td><a href="/user_manage/ass_archive?refund_warn=3"><span style="color:#FF0000">{{$refund_warning["three"]}}</span></a></td>
                                    </tr>
                                    <tr style="color:#FFCC33">
                                        <td>二级</td>
                                        <td><a href="/user_manage/ass_archive?refund_warn=2"><span style="color:#FFCC33">{{$refund_warning["two"]}}</span></a></td>
                                    </tr>
                                    <tr style="color:#0099FF">
                                        <td>一级</td>
                                        <td><a href="/user_manage/ass_archive?refund_warn=1"><span style="color:#0099FF">{{$refund_warning["one"]}}</span></a></td>
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
            
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            本组汇总
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>在册学员</td>
                                        <td>有效学员</td>
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
                                        <td>新签金额</td>
                                        <td>转介绍金额(CC)</td>
                                        <td>扩课成功数</td>
                                        <td>扩课申请数</td>
                                        <td>新签退费金额</td>
                                        <td>续费退费金额</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>{{@$stu_info["all_student"]}} </td>
                                        <td>{{@$stu_info["read_student_last"]}} </td>
                                        <td>{{@$stu_info["month_stop_student"]}} </td>
                                        <td>{{@$stu_info["refund_student"]}} </td>
                                        <td>{{@$stu_info["warning_student"]}} </td>
                                        <td>{{@$stu_info["lesson_total_old"]}} </td>
                                        <td>{{@$stu_info["lesson_total"]}} </td>
                                        <td>{{@$stu_info["lesson_money"]}} </td>
                                        <td  class="per" data-per="{{@$stu_info["lesson_per"]}}" >
                                            <a href="javascript:;" >{{@$stu_info["lesson_ratio"]}}</a>
                                        </td>
                                        <td  class="per" data-per="{{@$stu_info["lesson_per"]}}" >
                                            <a href="javascript:;" >{{@$stu_info["lesson_per"]}}%</a>
                                        </td>

                                        <td  class="per" data-per="{{@$stu_info["return_stu_per"]}}" >
                                            <a href="javascript:;" >{{@$stu_info["renw_student"]}}</a>
                                        </td>
                                        <td  class="per" data-per="{{@$stu_info["renw_per"]}}" >
                                            <a href="javascript:;" >{{@$stu_info["renw_price"]}}</a>
                                        </td>

                                        <td>{{@$stu_info["tran_price"]}}</td>
                                        <td>{{@$stu_info["cc_tran_money"]}}</td>

                                        <td>{{@$stu_info["kk_suc"]}}</td>
                                        <td>{{@$stu_info["kk_require"]}}</td>
                                        <td>{{@$stu_info["new_refund_money"]}} </td>
                                        <td>{{@$stu_info["renw_refund_money"]}} </td>
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
                            本组汇总
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>在册学员</td>
                                        <td>有效学员</td>
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
                                        <td>新签金额</td>
                                        <td>转介绍金额(CC)</td>
                                        <td>扩课成功数</td>
                                        <td>扩课申请数</td>
                                        <td>新签退费金额</td>
                                        <td>续费退费金额</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>{{@$stu_info["all_student"]}} </td>
                                        <td>{{@$stu_info["read_student_last"]}} </td>
                                        <td>{{@$stu_info["month_stop_student"]}} </td>
                                        <td>{{@$stu_info["refund_student"]}} </td>
                                        <td>{{@$stu_info["warning_student"]}} </td>
                                        <td>{{@$stu_info["lesson_total_old"]}} </td>
                                        <td>{{@$stu_info["lesson_total"]}} </td>
                                        <td>{{@$stu_info["lesson_money"]}} </td>
                                        <td  class="per" data-per="{{@$stu_info["lesson_per"]}}" >
                                            <a href="javascript:;" >{{@$stu_info["lesson_ratio"]}}</a>
                                        </td>
                                        <td  class="per" data-per="{{@$stu_info["lesson_per"]}}" >
                                            <a href="javascript:;" >{{@$stu_info["lesson_per"]}}%</a>
                                        </td>

                                        <td  class="per" data-per="{{@$stu_info["return_stu_per"]}}" >
                                            <a href="javascript:;" >{{@$stu_info["renw_student"]}}</a>
                                        </td>
                                        <td  class="per" data-per="{{@$stu_info["renw_per"]}}" >
                                            <a href="javascript:;" >{{@$stu_info["renw_price"]}}</a>
                                        </td>

                                        <td>{{@$stu_info["tran_price"]}}</td>
                                        <td>{{@$stu_info["cc_tran_money"]}}</td>

                                        <td>{{@$stu_info["kk_suc"]}}</td>
                                        <td>{{@$stu_info["kk_require"]}}</td>
                                        <td>{{@$stu_info["new_refund_money"]}} </td>
                                        <td>{{@$stu_info["renw_refund_money"]}} </td>
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
                                        <td>新签金额</td>
                                        <td>转介绍金额(CC)</td>
                                        <td>扩课成功数</td>
                                        <td>扩课申请数</td>
                                        <td>新签退费金额</td>
                                        <td>续费退费金额</td>
                                    </tr>
                                </thead>
                                <tbody id="id_ass_list_group">
                                    @foreach ( $ass_list_group as $key=> $var )
                                        <tr>
                                            <td> <span> {{$key+1}} </span> </td>
                                            <td  > {{@$var["account"]}} </td>
                                            <td  > {{@$var["name"]}} </td>
                                            <td>{{@$var["all_student"]}} </td>
                                            <td>{{@$var["read_student_last"]}} </td>
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
                                            <td>{{@$var["cc_tran_money"]}}</td>
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
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        月回访考核
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
                                @foreach ( $month_info as $var )
                                    <tr>
                                        <td>{{@$var['name']}}</td>
                                        <td>{{@$var["revisit_num"]/1}} </td>
                                        <td>{{@$var["stu_num"]*2}} </td>
                                        <td>{{@$var["call_num"]}}</td>
                                        <td>{{@$var["stu_num"]*6}}:00 </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading center-title ">
                        满意度回访考核
                    </div>
                    <div class="panel-body">

                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>姓名</td>
                                    <td>已回访量</td>
                                    <td>月目标回访量</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$leader_revisit_info['nick']}}</td>
                                    <td>{{$leader_revisit_info['leader_revisited']}}</td>
                                    <td>{{$leader_revisit_info['leader_goal']}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
