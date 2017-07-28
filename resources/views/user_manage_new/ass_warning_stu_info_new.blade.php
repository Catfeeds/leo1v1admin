@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">助教</span>
                        <input class="opt-change form-control" id="id_assistantid"/>
                    </div>
                </div>
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否续费</span>
                        <select class="opt-change form-control " id="id_ass_renw_flag" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">续费截止日期</span>
                        <select class="opt-change form-control " id="id_renw_week" >
                            <option value="-1">全部</option>
                            <option value="0">未设置</option>
                            <option value="1">第一周</option>
                            <option value="2">第二周</option>
                            <option value="3">第三周</option>
                            <option value="4">第四周</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否成功</span>
                        <select class="opt-change form-control " id="id_master_renw_flag" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否结束</span>
                        <select class="opt-change form-control " id="id_done_flag" >
                            <option value="-1">全部</option>
                            <option value="0">进行中</option>
                            <option value="-2">结束</option>
                        </select>
                    </div>
                </div>



            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>数据生成时间 </td>
                    <td>组别 </td>
                    <td>助教</td>
                    <td>学生</td>
                    <td>剩余课时</td>
                    <td>是否续费</td>
                    <td>续费金额</td>
                    <td>未续费原因</td>
                    <td>续费截止日期</td>
                    <td>首次设置状态时间</td>
                    <td>是否成功(组长)</td>
                    <td>未续费原因(组长)</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["month_str"]}} </td>
                        <td>{{@$var["group_name"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["left_count"]/100}} </td>
                        <td>{{@$var["ass_renw_flag_str"]}} </td>
                        @if($var["renw_price"]>0)
                            <td>{{@$var["renw_price"]/100}} </td>
                        @else
                            <td></td>
                        @endif
                        <td>{{@$var["no_renw_reason"]}} </td>
                        <td>{{@$var["renw_end_day"]}}</td>
                        <td>{{@$var["first_time"]}}</td>
                        <td>{{@$var["master_renw_flag_str"]}} </td>
                        <td>{{@$var["master_no_renw_reason"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit" title="录入续费情况"></a>
                                <a class="fa fa-gavel opt-edit-leader" title="确认续费情况"></a>
                                <a class="fa-list-alt opt-type-change-list" title="续费状态变更列表"></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

