@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">工资类型</span>
                        <select class="opt-change form-control " id="id_teacher_money_type" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">批次</span>
                        <select class="opt-change form-control " id="id_batch" >
                            <option value="-1">[全部]</option>
                            <option value="1">第1批</option>
                            <option value="2">第2批</option>
                            <option value="3">第3批</option>
                            <option value="4">第4批</option>
                            <option value="5">第5批</option>
                            <option value="6">第6批</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="opt-change form-control " id="id_status" >
                        </select>
                    </div>
                </div>
                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">老师</span>
                     <input id="id_teacherid"/>
                     </div>
                     </div> -->
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td style="display:none">id</td>
                    <td >姓名</td>
                    <td >原薪资类型</td>
                    <td >原等级</td>
                    <td >拟调整等级</td>
                    <td >调整批次属性</td>
                    <td >调整状态</td>
                    <td >调整时间</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["teacher_money_type_str"]}} </td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["new_level_str"]}} </td>
                        <td>{{@$var["batch_str"]}} </td>
                        <td>{{@$var["status_str"]}} </td>
                        <td>{{@$var["put_time_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if(in_array($acc,['ted']))
                                    <a class="opt-finally_check" title="最终审核">最终审核</a>
                                @elseif(in_array($acc,['Rain']))
                                    <a class="opt-first_check" title="第一次审核">审核</a>
                                @else
                                    <a class="opt-switch_upload" title="申请">申请</a>
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

