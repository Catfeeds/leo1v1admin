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

        </div>

    </section>

@endsection
