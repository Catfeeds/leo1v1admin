@extends('layouts.app')
@section('content')

    <style type="text/css">
     .row-td-field-name {
         padding-right: 0px;
     }

     .row-td-field-value {
         padding-left:0px;
         padding-right: 0px;
         poisition:relative;
     }

     .row-td-field-name >  span {
         background-color: #eee;
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: table-cell;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         text-align: right;
         vertical-align: middle;
         width: 1%;
         height: 26pt;
     }

     .row-td-field-value >  span {
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: inline-block;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         height: 26pt;
         line-height: 16pt;
         width: 100%;
         text-align:left ;
         background-color: #FFF;
     }
     .row-td-field-value >  span.time_span{
         width: 45%;
         margin-right: 3%;
     }
     p {margin-left: 20px}
     b.to{
         position:absolute;
         left: 46%;
         top: 15px;
     }
     .json_input{
         width:100%;
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         background-color: #FFF;
     }
     .lesson_activity{
         margin-bottom:10px;
     }
     .lesson_times_off_perent_list span,input{
         display:inline-block;
     }
     .show_activity{ width:100px;}
    </style>
    <section class="content">
        <div class="row">

            <div class="col-xs-12 col-md-12"   >
                <div style="width:98%" id="id_tea_info"
                     {!!  \App\Helper\Utils::gen_jquery_data($ret_info)  !!}
                >
                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >活动ID:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['id']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >活动标题:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['title']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span>活动开始至结束日期:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span class="time_span" id="date_range_start">{{@$ret_info['date_range_start']}}</span>
                                    <b class="to">--</b>
                                    <span class="time_span" id="date_range_end">{{@$ret_info['date_range_end']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span>活动课时区间:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span class="time_span">{{@$ret_info['lesson_times_min']}}</span>
                                    <b class="to">--</b>
                                    <span class="time_span">{{@$ret_info['lesson_times_max']}}</span>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_01" type="button" class="btn btn-primary">编辑</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >适配年级:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['grade_list_str']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >分期试用:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['period_flag_list_str']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >合同类型:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['contract_type_list_str']}}</span>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_02" type="button" class="btn btn-primary">编辑</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >优惠力度(power_value):</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['power_value']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >最大合同数:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['max_count']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >最大合同数预期:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['diff_max_count']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >优惠份额最大个数:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['max_change_value']}}</span>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_03" type="button" class="btn btn-primary">编辑</button>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span>总配额组合:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    @if(!empty($activity_type_list))
                                        @foreach ($activity_type_list as $var => $val)
                                            <span>{{@$val['title']}}</span>
                                        @endforeach
                                    @else
                                        <span></span>
                                    @endif
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_04" type="button" class="btn btn-primary">编辑</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >是否需要特殊申请:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['need_spec_require_flag_str']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >是否需要分享微信:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['is_need_share_wechat_str']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >是否开启活动:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['open_flag_str']}}</span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >是否手动开启活动:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['can_disable_flag_str']}}</span>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_05" type="button" class="btn btn-primary">编辑</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span>用户加入开始至结束日期:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span class="time_span" id="user_join_time_start">{{@$ret_info['user_join_time_start']}}</span>
                                    <b class="to">--</b>
                                    <span class="time_span" id="user_join_time_end">{{@$ret_info['user_join_time_end']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span>最近一次试听开始至结束日期:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span class="time_span" id="last_test_lesson_start">{{@$ret_info['last_test_lesson_start']}}</span>
                                    <b class="to">--</b>
                                    <span class="time_span" id="last_test_lesson_end">{{@$ret_info['last_test_lesson_end']}}</span>
                                </div>                  
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span>试听成功开始至结束日期:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span class="time_span" id="success_test_lesson_start">{{@$ret_info['success_test_lesson_start']}}</span>
                                    <b class="to">--</b>
                                    <span class="time_span" id="success_test_lesson_end">{{@$ret_info['success_test_lesson_end']}}</span>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_06" type="button" class="btn btn-primary">编辑</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >优惠类型:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <span>{{@$ret_info['order_activity_discount_type_str']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >优惠信息:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <div class="json_input discount_input" style="padding:5px">
                                        @foreach ($discount_list as $var => $val)
                                            {{$val}}<br/>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_07" type="button" class="btn btn-primary">编辑</button>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-10 col-md-10"  >
                            <div class="row">
                                <div class="col-xs-2 col-md-2 row-td-field-name"  >
                                    <span >json字符串:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <textarea class="json_input" disabled ="disabled">{{@$ret_info['discount_json']}}</textarea>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_08" type="button" class="btn btn-primary">编辑</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-10 col-md-10">
                            <div class="row">
                                <div class="col-xs-2 col-md-2  row-td-field-name">
                                    <span>操作:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <button style="margin-left:10px"  id="id_close" type="button" class="btn btn-success">关闭</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <div class="lesson_activity lesson_times_off_perent_list hide">
        <span>课时数：</span>
        <input type="text" class="show_activity lesson_counts" onkeypress="nextInput(event)">
        <span>打折：</span>
        <input type="text" class="show_activity give_perent" onkeypress="addActivity(event,1)">
        <span> 输完回车</span>
        <button onclick="remove_activity()"> 移除</button>
    </div>

    <div class="lesson_activity grade_off_perent_list hide">
        <span>年级：</span>
        <select class="show_activity lesson_grade" onkeypress="nextInput(event)">
            @foreach ($gradeArr as $var => $val)
                <option value="{{$var}}">{{$val}}</option>
            @endforeach
        </select>
        <span>打折：</span>
        <input type="text" class="show_activity give_grade_perent" onkeypress="addActivity(event,2)">
        <span> 输完回车</span>
        <button onclick="remove_activity()">移除</button>
    </div>

    <div class="lesson_activity lesson_times_present_lesson_count hide">
        <span>课时数：</span>
        <input type="text" class="show_activity lesson_have" onkeypress="nextInput(event)">
        <span>送课：</span>
        <input type="text" class="show_activity give_lessons" onkeypress="addActivity(event,3)">
        <span> 输完回车</span>
        <button onclick="remove_activity()"> 移除</button>
    </div>

    <div class="lesson_activity price_off_money_list hide">
        <span>金额：</span>
        <input type="text" class="show_activity lesson_pay" onkeypress="nextInput(event)">
        <span>立减：</span>
        <input type="text" class="show_activity give_money" onkeypress="addActivity(event,4)">
        <span> 输完回车</span>
        <button onclick="remove_activity()"> 移除</button>

    </div>

    <div class="lesson_activity lesson_times_off_money hide">
        <span>按课次：</span>
        <input type="text" class="show_activity lesson_pay" onkeypress="nextInput(event)">
        <span>立减：</span>
        <input type="text" class="show_activity give_money" onkeypress="addActivity(event,5)">
        <span> 输完回车</span>
        <button onclick="remove_activity()"> 移除</button>
    </div>

@endsection
