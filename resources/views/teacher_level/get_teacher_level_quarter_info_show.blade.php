@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row" >


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">季度</span>
                        <select class="opt-change form-control " id="id_start_time" >
                            @foreach($season_list as $k=>$v)
                                <option value="{{$k}}">{{$v}}</option>
                            @endforeach
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
                        <span class="input-group-addon">晋升申请</span>
                        <select class="opt-change form-control " id="id_advance_require_flag" >
                            <option value="-1">全部</option>
                            <option value="1">已申请</option>
                            <option value="2">未申请</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">扣款申请</span>
                        <select class="opt-change form-control " id="id_withhold_require_flag" >
                            <option value="-1">全部</option>
                            <option value="1">已申请</option>
                            <option value="2">未申请</option>
                        </select>
                    </div>
                </div>               
                <div class="col-xs-6 col-md-6">
                    <button class="btn btn-primary" id="id_withhold_agree" style="float:right" > 一键同意扣款 </button>
                    <button class="btn btn-primary" id="id_advance_agree" style="float:right;margin-right:15px">一键同意晋升 </button>
                    <button class="btn btn-primary" id="id_edit_rule" style="float:right;margin-right:15px"> 修改晋升规则</button>
                </div>

               





                @if($account=="jack" || $account=="jim" )
                    <div class="col-xs-6 col-md-2">
                        <button class="btn btn-primary" id="id_add_teacher"> 新增晋升老师 </button>
                        <button class="btn btn-primary" id="id_update_all_info"> 刷新数据 </button>
                    </div>
                @endif
               




            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id</td>
                    <td>老师</td>
                    <td style="display:none">晋升前等级</td>
                    <td>等级</td>
                    <td>课耗平均</td>
                    <td>课耗得分</td>
                    <td>试听课数(CC)</td>
                    <td>签单数(CC)</td>
                    <td>CC得分</td>
                    <td>试听课数(CR)</td>
                    <td>签单数(CR)</td>
                    <td>CR得分</td>
                    <td>常规学生数</td>
                    <td>常规学生数得分</td>
                    <td>反馈数量</td>
                    <td>反馈平均分数</td>
                    <td>教学质量得分</td>
                    {!!\App\Helper\Utils::th_order_gen([
                        ["总得分","total_score" ],
                       ])  !!}
                    <td>是否达标</td>
                    <td width="130">扣款申请</td>
                    <td width="130">晋升申请</td>

                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td >{{@$var["level_before_str"]}} </td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["lesson_count"]}} </td>
                        <td>{{@$var["lesson_count_score"]}} </td>
                        <td>{{@$var["cc_test_num"]}} </td>
                        <td>{{@$var["cc_order_num"]}} </td>
                        <td>{{@$var["cc_order_score"]}} </td>
                        <td>{{@$var["other_test_num"]}} </td>
                        <td>{{@$var["other_order_num"]}} </td>
                        <td>{{@$var["other_order_score"]}} </td>
                        <td>{{@$var["stu_num"]}} </td>
                        <td>{{@$var["stu_num_score"]}} </td>

                        <td>{{@$var["record_num"]}} </td>
                        <td>{{@$var["record_score_avg"]}} </td>
                        <td>{{@$var["record_final_score"]}} </td>
                        <td>{{@$var["total_score"]}} </td>
                        <td>{{@$var["reach_flag_str"]}} </td>
                        <td>
                            @if(@$var["reach_flag"]==1)
                                0<br><br>
                                无
                            @else
                                -{{ @$var["withhold_money"] }}元/月<br><br>
                                @if(empty($var["withhold_require_time"]))
                                    无
                                @elseif(empty($var["withhold_first_trial_flag"]))
                                    待审批
                                @elseif(empty($var["withhold_final_trial_flag"]))
                                    审批中
                                @else
                                    @if($var["withhold_final_trial_flag"]==1)
                                        已通过
                                    @elseif($var["withhold_final_trial_flag"]==2)
                                        已拒绝
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td>
                            @if(empty($var["require_time"]))
                                状态:无
                            @elseif(empty($var["advance_first_trial_time"]) && empty($var["accept_time"]))
                                状态:待审批<br>
                                目标等级:{{@$var["level_after_str"]}}<br>
                            @elseif(!empty($var["advance_first_trial_time"]) && empty($var["accept_time"]))
                                状态:审批中<br>
                                目标等级:{{@$var["level_after_str"]}}<br>
                            @else
                                @if($var["accept_flag"]==1)
                                    状态:已通过<br>
                                @elseif($var["accept_flag"]==2)
                                    状态:已拒绝<br>
                                @endif
                                目标等级:{{@$var["level_after_str"]}}<br>
                            @endif
                        </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if(empty($var["accept_time"]))
                                    @if(empty($var["require_time"]))
                                        <a class="opt-advance-require" title="晋升申请">晋升申请</a>
                                    @else
                                        <a class="opt-advance-require-deal" title="晋升审批">晋升审批</a>
                                    @endif
                                @endif
                                @if($var["reach_flag"]==0 && empty($var["withhold_final_trial_flag"]))
                                    @if(empty($var["withhold_require_time"]))
                                        <a class="opt-advance-withhold-require" title="扣款申请">扣款申请</a>
                                    @else
                                        <a class="opt-advance-withhold-deal" title="扣款审批">扣款审批</a>
                                    @endif

                                @endif
                                @if($account=="jack" || $account=="jim")
                                    @if($var["hand_flag"]==1)
                                        <a class="opt-add-hand" title="手动刷新数据">手动刷新数据</a>
                                    @endif
                                    @if($var["accept_flag"]==0 && $var["require_time"]>0)
                                        <a class="opt-update-level-after" title="修改等级">修改等级</a>
                                    @endif

                                    <a class="opt-del" title="删除">删除</a>
                                    <a class="opt-edit" title="编辑">编辑</a>
                                @endif

                                @if(in_array($account,["林璐","jack","jim"]) && in_array($var["teacherid"],[50158,60030]))
                                    <a class="opt-edit-test" title="编辑">编辑-test</a>
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
