@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row" >                              
               
                <div class="col-xs-6 col-md-2" style="display:none">
                    <div class="input-group ">
                        <span class="input-group-addon">老师类型</span>
                        <select class="opt-change form-control " id="id_fulltime_flag" >
                            <option value="-1">全部</option>
                            <option value="0">兼职老师</option>
                            <option value="1">上海全职老师</option>
                            <option value="2">武汉全职老师</option>
                        </select>
                    </div>
                </div>               

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">工资类型</span>
                        <select class="opt-change form-control " id="id_teacher_money_type" >
                        </select>
                    </div>
                </div>               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">结果</span>
                        <select class="opt-change form-control " id="id_accept_flag" >
                            <option value="-1">全部</option>
                            <option value="0">未审核</option>
                            <option value="1">通过</option>
                            <option value="2">驳回</option>
                        </select>
                    </div>
                </div>               




            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>老师</td>
                    <td>当前等级</td>
                    <td>晋升后等级</td>
                    <td>课耗平均</td>
                    <td>课耗得分</td>
                    <td>试听课数(cc)</td>
                    <td>签单数(cc)</td>
                    <td>转化率(cc)</td>
                    <td>转化率得分(cc)</td>
                    <td>试听课数(其他)</td>
                    <td>签单数(其他)</td>
                    <td>转化率(其他)</td>
                    <td>转化率得分(其他)</td>
                    <td>反馈数量</td>
                    <td>反馈平均分数</td>
                    <td>教学质量评估分</td>
                    
                    <td>有无退费</td>
                    <td>总得分</td>
                    <td>晋升情况</td>
                   
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["level_before_str"]}} </td>
                        <td>{{@$var["level_after_str"]}} </td>
                        <td>{{@$var["lesson_count"]/100}} </td>
                        <td>{{@$var["lesson_count_score"]}} </td>
                        <td>{{@$var["cc_test_num"]}} </td>
                        <td>{{@$var["cc_order_num"]}} </td>
                        <td>{{@$var["cc_order_per"]}}% </td>
                        <td>{{@$var["cc_order_score"]}} </td>
                        <td>{{@$var["other_test_num"]}} </td>
                        <td>{{@$var["other_order_num"]}} </td>
                        <td>{{@$var["other_order_per"]}}% </td>
                        <td>{{@$var["other_order_score"]}} </td>

                        <td>{{@$var["record_num"]}} </td>
                        <td>{{@$var["record_score_avg"]}} </td>
                        <td>{{@$var["record_final_score"]}} </td>
                        <td >
                            @if($var["is_refund"]==1)
                                <a href="javascript:;" class="show_refund_detail" data-teacherid="{{$var["teacherid"]}}" >
                                    {!! $var['is_refund_str'] !!}
                                </a>
                            @else
                                {!! $var['is_refund_str'] !!}
                            @endif
                        </td>
                        <td>{{@$var["total_score"]}} </td>
                        <td>
                            @if(empty($var["require_time"]))
                                状态:未申请
                            @elseif(empty($var["accept_time"]))
                                状态:已申请,未审核<br>
                                时间:{{$var["require_time_str"]}}
                            @else
                                状态:已审核<br>
                                结果:{{$var["accept_flag_str"]}}<br>
                                @if($var["accept_flag"]==2)
                                    理由:{{$var["accept_info"]}}<br>
                                @endif
                                时间:{{$var["accept_time_str"]}}
                            @endif
                        </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if(empty($var["accept_time"]) || $var["teacherid"]==50158 || $var["accept_flag"]==2)
                                    <a class="opt-accept" >同意</a>
                                    <a class="opt-no-accept" >驳回</a>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

