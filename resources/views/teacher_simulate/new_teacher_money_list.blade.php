@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-5" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <span >老师:</span>
                        <input type="text" id="id_teacherid" class="opt-change"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >工资分类</span>
                        <select id="id_teacher_money_type" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >等级分类</span>
                        <select id="id_level" class ="opt-change" ></select>
                    </div>
                </div>
                <!-- <div class="col-xs-6 col-md-2">
                     <button class="btn btn-primary" id="id_reset_money_count">清空统计信息</button>
                     </div> -->
                <!-- <div class="col-xs-6 col-md-2">
                     <button class="btn btn-primary" id="id_reset_level_count">重置等级信息</button>
                     </div> -->
            </div>
            <!-- @if(in_array($acc,["adrian","ted"]))
                 <table class="common-table">
                 <tr>
                 <td></td>
                 <td>总工资</td>
                 <td>课程收入</td>
                 <td>模拟工资</td>
                 <td>模拟收入</td>
                 <td>总工资差别</td>
                 <td>总收入差别</td>
                 </tr>
                 <tr>
                 <td>本月统计</td>
                 <td>{{@$all_money}}</td>
                 <td>{{@$all_lesson_price}}</td>
                 <td>{{@$all_money_simulate}}</td>
                 <td>{{@$all_lesson_price_simulate}}</td>
                 <td>{{@$all_money_different}}</td>
                 <td>{{@$all_lesson_price_different}}</td>
                 </tr>
                 @if(!empty($final_money) && is_array($final_money))
                 <tr>
                 <td>1-7月统计</td>
                 <td>{{@$final_money['all_money']}}</td>
                 <td>{{@$final_money['all_lesson_price']}}</td>
                 <td>{{@$final_money['all_money_simulate']}}</td>
                 <td>{{@$final_money['all_lesson_price_simulate']}}</td>
                 <td>{{@$final_money['all_money_different']}}</td>
                 <td>{{@$final_money['all_lesson_price_different']}}</td>
                 </tr>
                 @endif
                 </table>
                 @endif
                 <table class="common-table">
                 <tr>
                 <td></td>
                 <td>工资成本</td>
                 <td>模拟工资成本</td>
                 <td>累积课时</td>
                 </tr>
                 <tr>
                 <td>本月全部老师</td>
                 <td>{{round($all_money/($all_lesson_price==0?1:$all_lesson_price),4)*100}}%</td>
                 <td>{{round($all_money_simulate/($all_lesson_price_simulate==0?1:$all_lesson_price_simulate),4)*100}}%</td>
                 <td>{{$lesson_total}}</td>
                 </tr>
                 <tr>
                 <td>1-7月全部老师</td>
                 <td>{{round($final_money['all_money']/($final_money['all_lesson_price']==0?1:$final_money["all_lesson_price"]),4)*100}}%</td>
                 <td>{{round($final_money['all_money_simulate']/($final_money['all_lesson_price_simulate']==0?1:$final_money['all_lesson_price_simulate']),4)*100}}%</td>
                 <td></td>
                 </tr>
                 </table>
                 <table class="common-table">
                 <tr>
                 <td>等级</td>
                 <td>人数</td>
                 <td>占比</td>
                 </tr>
                 @foreach($level_list as $l_key => $l_val)
                 <tr>
                 <td>{{$l_key}}</td>
                 <td>{{$l_val['level_num']}}</td>
                 <td>{{round($l_val['level_per'],4)*100}}%</td>
                 </tr>
                 @endforeach
                 </table> -->
        <hr />
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>姓名</td>
                    <td>常规课时</td>
                    <td>工资类型</td>
                    <td>等级</td>
                    <td>总工资</td>
                    <td>课时奖励</td>
                    <td>课程收入</td>

                    <td>模拟工资类型</td>
                    <td>模拟工资等级</td>
                    <td>模拟总工资</td>
                    <td>模拟课时奖励</td>
                    <td>模拟课程收入</td>

                    <td>老师工资差别</td>
                    <td>课程收入差别</td>

                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['realname']}}</td>
                        <td>{{$var['lesson_count']}}</td>
                        <td>{{$var['teacher_money_type_str']}}</td>
                        <td>{{$var['level_str']}}</td>
                        <td>{{$var['money']}}</td>
                        <td>{{$var['reward']}}</td>
                        <td>{{$var['lesson_price']}}</td>

                        <td>{{$var['teacher_money_type_simulate_str']}}</td>
                        <td>{{$var['level_simulate_str']}}</td>
                        <td>{{$var['money_simulate']}}</td>
                        <td>{{$var['reward_simulate']}}</td>
                        <td>{{$var['lesson_price_simulate']}}</td>

                        <td>{{$var['money_different']}}</td>
                        <td>{{$var['lesson_price_different']}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}  >
                                <a class="opt-set_simulate_info" title="重置等级">设置模拟信息</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

