@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <style>
     .middle{text-align: center;vertical-align: middle};
    </style>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">全职老师</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否转正</span>
                        <select class="opt-change form-control " id="id_become_full_member_flag" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">全职老师分类</span>
                        <select class="opt-change form-control" id="id_fulltime_teacher_type" >
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td> 老师 </td>
                    <td> 入职日期 </td>
                    <td>考核详情</td>
                    <td>转正申请详情</td>
                    <td> 转正 </td>
                    <td> 转正时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["name"]}} </td>
                        <td>{{@$var["create_time_str"]}} </td>
                        <td>
                            @if($var["id"]>0)
                                @if($var["assess_time"]>0)
                                    状态:总监已考核 <br>
                                    考核时间:{{@$var["assess_time_str"]}}<br>
                                    考核人:{{@$var["assess_admin_nick"]}}
                                @else
                                    状态:已自评,总监未考核
                                @endif
                            @else
                                状态:未提交考核自评
                            @endif
                        </td>
                        <td>
                            @if(@$var["positive_id"]>0)
                                转正类型:{{@$var["positive_type_str"]}}<br>
                                @if(@$var["main_master_deal_flag"]>0)
                                    状态:总经理已审核 <br>
                                    结果:{{@$var["main_master_deal_flag_str"]}}<br>
                                    审核时间:{{@$var["main_master_assess_time_str"]}}<br>
                                    审核人:{{@$var["main_mater_admin_nick"]}}
                                @elseif(@$var["master_deal_flag"]>0)
                                    状态:总监已审核 <br>
                                    结果:{{@$var["master_deal_flag_str"]}}<br>
                                    审核时间:{{@$var["master_assess_time_str"]}}<br>
                                    审核人:{{@$var["mater_admin_nick"]}}
                                @else
                                    状态:已申请,总监未审核<br>
                                    申请时间:{{@$var["add_time_str"]}}<br>
                                @endif
                            @else
                                状态:未提交申请
                            @endif

                        </td>
                        <td>
                            {{@$var["become_full_member_flag_str"]}}
                        </td>
                        <td>
                            {{@$var["become_full_member_time_str"]}}
                        </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if($main_flag==1)
                                    <a class="set_fulltime_teacher_assessment_master" title="考核评定">总经理考核评定</a>
                                    <a class="set_fulltime_teacher_positive_require_master" title="转正申请审核">总经理转正申请审核</a>
                                @else
                                    <a class="set_fulltime_teacher_assessment" title="考核评定">总监考核评定</a>
                                    <a class="set_fulltime_teacher_positive_require" title="转正申请审核">总监转正申请审核</a>
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

