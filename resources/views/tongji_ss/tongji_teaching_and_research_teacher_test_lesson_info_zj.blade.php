@extends('layouts.app')
@section('content')
    <style>
     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }
    </style>

    <section class="content " id="id_content">

        <div  id="id_query_row">
            <div class="row" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_read_reward_rule"> 查看签单奖规则 </button>
                </div>


            </div>
        </div>

        <br>

        <div class="row">

            <div class="col-xs-12 col-md-4">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        负责面试的老师签单率排行榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>老师</td>
                                    <td>面试试听课数</td>
                                    <td>面试签单数</td>
                                    <td>面试签单率</td>
                                    <td>对应奖金</td>
                                </tr>
                            </thead>
                            <tbody id="id_order_num_body">
                                @foreach ( $order_num_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td > {{@$var["realname"]}} </td>
                                        <td class="test_lesson" data-adminid='{{@$var["uid"]}}'>
                                            <a  href="javascript:;" >{{@$var["person_num"]}}</a>
                                        </td>
                                        <td > {{@$var["order_num"]}} </td>

                                        <td > {{@$var["order_per"]}}% </td>
                                        <td > {{@$var["order_reward"]}} </td>
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
                        负责面试的老师签单奖排行榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>老师</td>
                                    <td>签单奖</td>
                                    <td>对应签单老师数</td>
                                    <td>首次试听签单奖</td>
                                    <td>首签奖对应老师</td>
                                </tr>
                            </thead>
                            <tbody id="id_order_reward_body">
                                @foreach ( $order_reward_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td > {{@$var["realname"]}} </td>
                                        <td >
                                            @if(@$var["reward"]>0)
                                                {{@$var["reward"]/100}}
                                            @endif
                                        </td>
                                        <td >
                                            @if(@$var["reward"]>0)
                                                <a  href="javascript:;" class="reward_num" data-adminid='{{@$var["uid"]}}'>{{@$var["reward_num"]}}</a>
                                            @endif
                                        </td>
                                        <td >
                                            @if(@$var["first_reward"]>0)
                                                {{@$var["first_reward"]/100}}
                                            @endif
                                        </td>
                                        <td >
                                            @if(@$var["first_reward"]>0)
                                                <a  href="javascript:;" class="first_reward_num" data-adminid='{{@$var["uid"]}}'>{{@$var["first_reward_num"]}}</a>
                                            @endif
                                        </td>



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
                        各年级签单率排行榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>老师</td>
                                    <td>试听课</td>
                                    <td>签单数</td>
                                    <td>签单率</td>
                                </tr>
                            </thead>
                            <tbody id="id_order_per_body">
                                @foreach ( $subject_grade_arr as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td > {{$var["realname"]}} </td>
                                        <td class="all_lesson" data-teacherid='{{@$var["teacherid"]}}' data-subject='{{@$var["subject"]}}' data-grade='{{@$var["grade"]}}' data-realname='{{@$var["realname"]}}' data-lesson='{{@$var["all_lesson"]}}' data-num='{{@$var["order_num"]}}' data-per='{{@$var["order_per"]}}'><a href="javascript:;" >{{$var["all_lesson"]}}</a></td>

                                        <td > {{$var["order_num"]}} </td>
                                        <td > {{$var["order_per"]}}% </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="row" id="team_info">
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        团队学科签单数排行榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>学科</td>
                                    <td>签单数</td>
                                    <td>签单数(扩课)</td>
                                    <td>签单数(换老师)</td>
                                </tr>
                            </thead>
                            <tbody id="id_subject_order_num_body">
                                @foreach ( $subject_order_per_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td > {{$var["subject_str"]}} </td>
                                        <td > {{$var["order_num"]}} </td>
                                        <td > {{$var["kk_num"]}} </td>
                                        <td > {{$var["hls_num"]}} </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        团队学科签单率排行榜
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>学科</td>
                                    <td>签单率</td>
                                    <td>签单率(扩课)</td>
                                    <td>签单率(换老师)</td>
                                </tr>
                            </thead>
                            <tbody id="id_subject_order_per_body">
                                @foreach ( $subject_order_per_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td > {{$var["subject_str"]}} </td>
                                        <td class="subject_order_per"  data-subject='{{@$var["subject"]}}' ><a href="javascript:;" > {{$var["order_per"]}}% </a></td>
                                        <td > {{$var["kk_per"]}}% </td>
                                        <td > {{$var["hls_per"]}}% </td>
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
                    <div class="panel-heading">
                        个人KPI
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>教研</td>
                                    <td>审核时长</td>
                                    <td>面试签单率</td>
                                    <td>新入职反馈时长</td>
                                    <td>反馈数量</td>
                                    <td>首次试听转化率</td>
                                    <td>反馈后转化率提升度</td>
                                    <td>投诉处理时长</td>
                                    <td>试听成功数(销售)-占比</td>
                                    <td>签单率</td>
                                    <td>签单率(转介绍)</td>
                                    <td>签单率(扩课)</td>
                                    <td>签单率(换老师)</td>
                                    <td>总分</td>

                                </tr>
                            </thead>
                            <tbody id="id_person_kpi">
                                @foreach ( $person_kpi as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{@$var["name"]}}</td>
                                        <td class="five_score">{{ @$var["interview_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["interview_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_num_score"] }}</td>
                                        <td class="five_score">{{ @$var["first_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["add_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["other_record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_num_per_score"] }}</td>
                                        <td class="twenty_five_score">{{ @$var["lesson_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_other_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_kk_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_change_score"] }}</td>
                                        <td>{{ @$var["total_score"] }}</td>

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
                    <div class="panel-heading">
                        团队KPI
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>团队</td>
                                    <td>审核时长</td>
                                    <td>面试签单率</td>
                                    <td>新入职反馈时长</td>
                                    <td>反馈数量</td>
                                    <td>首次试听转化率</td>
                                    <td>反馈后转化率提升度</td>
                                    <td>投诉处理时长</td>
                                    <td>试听成功数(销售)-占比</td>
                                    <td>签单率</td>
                                    <td>签单率(转介绍)</td>
                                    <td>签单率(扩课)</td>
                                    <td>签单率(换老师)</td>
                                    <td>总分</td>

                                </tr>
                            </thead>
                            <tbody id="id_subject_kpi">
                                @foreach ( $subject_kpi as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{@$var["name"]}}</td>
                                        <td class="five_score">{{ @$var["interview_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["interview_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_num_score"] }}</td>
                                        <td class="five_score">{{ @$var["first_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["add_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["other_record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_num_per_score"] }}</td>
                                        <td class="twenty_five_score">{{ @$var["lesson_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_other_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_kk_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_change_score"] }}</td>
                                        <td>{{ @$var["total_score"] }}</td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>





    </section>

@endsection
