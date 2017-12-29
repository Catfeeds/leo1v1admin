@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row" >                              
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">类型</span>
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
                    <button class="btn btn-primary" id="id_add_teacher"> 新增晋升老师 </button>
                </div>                
                @if($acc=="jack")
                    <button class="btn btn-primary" id="id_add_info"> 刷新数据 </button>
                @endif
                



            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>id</td>
                    <td>老师</td>
                    <td>等级</td>
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
                    <td>常规学生数</td>
                    <td>常规学生数得分</td>
                    <td>反馈数量</td>
                    <td>反馈平均分数</td>
                    <td>教学质量评估分</td>
                    
                    <td>有无退费</td>
                    {!!\App\Helper\Utils::th_order_gen([  
                        ["总得分","total_score" ],
                       ])  !!}
                    <td>晋升情况</td>
                   
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["lesson_count"]}} </td>
                        <td>{{@$var["lesson_count_score"]}} </td>
                        <td>{{@$var["cc_test_num"]}} </td>
                        <td>{{@$var["cc_order_num"]}} </td>
                        <td>{{@$var["cc_order_per"]}}% </td>
                        <td>{{@$var["cc_order_score"]}} </td>
                        <td>{{@$var["other_test_num"]}} </td>
                        <td>{{@$var["other_order_num"]}} </td>
                        <td>{{@$var["other_order_per"]}}% </td>
                        <td>{{@$var["other_order_score"]}} </td>
                        <td>{{@$var["stu_num"]}} </td>
                        <td>{{@$var["stu_num_score"]}} </td>

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
                                目标等级:{{@$var["level_after_str"]}}<br>
                                时间:{{$var["require_time_str"]}}
                            @else
                                状态:已审核<br>
                                目标等级:{{@$var["level_after_str"]}}<br>
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
                                @if(empty($var["require_time"]))
                                    <a class="opt-advance-require" title="晋升申请">晋升申请</a>
                                @endif
                                @if($var["hand_flag"]==1)
                                    <a class="opt-add-hand" title="手动刷新数据">手动刷新数据</a>
                                @endif
                                @if($var["accept_flag"]==0 && $var["require_time"]>0)
                                    <a class="opt-update-level-after" title="修改等级">修改等级</a>
                                @endif
                                @if($acc=="jack")
                                    <a class="opt-del" title="删除">删除</a>
                                    <a class="opt-edit" title="编辑">编辑</a>
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

