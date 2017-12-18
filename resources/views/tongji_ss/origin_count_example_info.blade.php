@extends('layouts.app')

@section('content')
        <section class="content ">
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td style="width:60px">时间</td>
                    <td style="display:none;">手机号</td>
                    <td >基本信息</td>
                    <td >地区</td>
                    <td >来源</td>
                    <td >回访间隔</td>
                    <td style="display:none;">例子第一次拨打时间</td>
                    <td style="width:70px">回访状态</td>
                    <td style="width:70px">子状态</td>
                    <td >全局TQ状态</td>
                    <td >系统判定无效</td>

                    <td >用户备注</td>
                    <td >年级</td>
                    <td >科目</td>
                    <td >是否有pad</td>
                    <td >负责人</td>
                    <td >负责人联系次数</td>
                    <td >TMK负责人</td>
                    <td >抢单人/时间</td>
                    <td style="display:none" >试听申请人</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["opt_time"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>
                            {{$var["phone"]}} <br/>
                            {{$var["phone_location"]}} <br/>
                            {{$var["nick"]}} <br/>
                            {{$var["seller_resource_type_str"]}}
                        </td>
                        <td>{{$var["phone_location"]}} </td>

                        <td>
                            @if  ($var["origin_assistantid"]==0)
                                {{$var["origin"]}} ({{$var["origin_level_str"]}})/{{$var["nickname"]}} <br/>
                            @else
                                转介绍: {{$var["origin_assistant_nick"]}} <br/>
                            @endif
                        </td>
                        <td>{{$var["last_call_time_space"]}}天 </td>
                        <td>
                            {{$var['first_call_time']}}
                        </td>
                        <td>
                            {{$var["seller_student_status_str"]}} <br/>
                        </td>
                        <td>
                            {{$var["seller_student_sub_status_str"]}}
                        </td>

                        <td>
                            {{$var["global_tq_called_flag_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["sys_invaild_flag_str"]}} <br/>
                        </td>


                        <td>
                            {{$var["user_desc"]}} <br/>
                        </td>

                        <td>
                            {{$var["grade_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["subject_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["has_pad_str"]}} <br/>
                        </td>


                        <td>
                            {{$var["sub_assign_admin_2_nick"]}} / {{$var["admin_revisiter_nick"]}}
                            <br/>
                        </td>

                        <td>
                            {{$var["call_count"]}} <br/>
                        </td>

                        <td>
                            {{$var["tmk_admin_nick"]}} <br/>
                            {{$var["tmk_student_status_str"]}} <br/>
                        </td>


                        <td>
                            {{$var["competition_call_admin_nick"]}} /<br/>
                            {{$var["competition_call_time"]}}
                        </td>
                        <td>{{$var["require_admin_nick"]}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
