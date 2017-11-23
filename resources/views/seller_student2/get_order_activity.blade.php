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
         height:240px;
     }
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
                                    <span >最大修改金额累计值:</span>
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
                                    @if(!empty($_activity_type_list))
                                        @foreach ($activity_type_list_str as $var => $val)
                                            <span>{{$val}}</span>
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
                                    <span >json字符串:</span>
                                </div>
                                <div class="col-xs-5 col-md-5  row-td-field-value">
                                    <textarea class="json_input" disabled ="disabled">{{@$ret_info['discount_json']}}</textarea>
                                </div>
                                <div class="col-xs-1 col-md-1  row-td-field-value">
                                    <button style="margin-left:10px"  id="opt_edit_07" type="button" class="btn btn-primary">编辑</button> 
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
                                    <button style="margin-left:10px"  id="id_return" type="button" class="btn btn-default">返回</button> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

    </section>
    
    <div class="grade_arr hide">
        @foreach ($gradeArr as $var => $val)
            <label><input name="re_submit" type="checkbox" value="{{$var}}" />{{$val}}</label>
        @endforeach
    </div>

    <div class="_activity_type_list hide">
        @if($_activity_type_list)
            @foreach ($_activity_type_list as $var => $val)
                <div>
                    <input name="re_submit" type="checkbox" value="{{$var}}" />
                    <span>{{$val}}</span>
                </div>
            @endforeach
        @endif
    </div>

@endsection


