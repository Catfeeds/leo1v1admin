@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row " >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否有效</span>
                        <select class="opt-change form-control" id="id_lesson_user_online_status" >
                        </select>
                    </div>
                </div>


                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>

            </div>
            <div class="row  " >
                <div class="col-xs-12 col-md-12"  >
                    未拨打数: <font color="red">{{ $no_call_count }} </font>,
                    已拨打数: <font color="red"> {{ $call_count }}</font> , 
                    课后15分钟内拨打数: <font color="red">{{ $call_15min_count }}</font>,
                    拨打平均时间间隔: <font color="red">{{ $avg_call_duration }} </font>,
                </div>


            </div>

        </div>

        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>

                    <td>申请人</td>
                    <td>学生 </td>
                    <td>电话</td>
                    {!!\App\Helper\Utils::th_order_gen([
                        ["下课时间","lesson_start" ],
                        ["系统判定是否有效","lesson_user_online_status" ],
                        ["课后首次电话时间","tq_call_time" ],
                        ["间隔","duration" ],
                        ["最后一次通话时间","last_tq_call_time" ],
                        ["课后通话次数","tq_call_count" ],
                        ["课后通话总时长","tq_call_all_time" ],
                        ["合同下单时间","order_time" ],
                        ["合同金额","price" ],
                       ])!!}

                    <td>主管评价 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["cur_require_admin_nick"]}} </td>
                        <td>{{$var["nick"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["lesson_end"]}} </td>
                        <td>{!!$var["lesson_user_online_status_str"]!!} </td>
                        <td>{{$var["tq_call_time"]}} </td>
                        <td>{{$var["duration_str"]}} </td>
                        <td>{{$var["last_tq_call_time"]}} </td>
                        <td>{{$var["tq_call_count"]}} </td>
                        <td>{{$var["tq_call_all_time"]}} </td>

                        <td>{{$var["order_time"]}} </td>
                        <td>{{$var["price"]}} </td>
                        <td>{{$var["assess"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-phone opt-telphone  btn" title="电话列表"> </a>
                                <a class=" fa-list-alt opt-log-list" title="登录日志"></a>
                                <a class="fa fa-edit opt-edit"  title="主管评价"> </a>
                                <!-- <a class="fa fa-times opt-del" title="删除"> </a> -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display:none;" >
            <div id="id_lesson_log"  >
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-userid form-control" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-server-type form-control"  >
                                <option value="-1" > 不限 </option>
                                <option value="1" > webrtc</option>
                                <option value="2" > xmpp</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr/>
                <table   class="table table-bordered "   >
                    <tr>  <th> 时间 <th>角色 <th>用户id <th>服务 <th> 进出 <th> ip </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>

        @include("layouts.page")
    </section>

@endsection
