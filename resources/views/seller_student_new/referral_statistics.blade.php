@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-3"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">负责人</span>
                        <select class="opt-change form-control" id="id_principal" >
                            <option value="-1">全部</option>
                            <option value="1">助教</option>
                            <option value="2">销售</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">所属部门</span>
                        <select class="opt-change form-control" id="id_group" >
                            <option value="-1">全部</option>
                            @foreach ( $main_group as $var )
                                <option value="{{$var['groupid']}}">{{$var["group_name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" style="display:none;">
                    <div class="input-group ">
                        <span class="input-group-addon">创建人</span>
                        <select class="opt-change form-control" id="id_create" >
                            <option value="-1">全部</option>
                            <option value="1">助教</option>
                            <option value="2">销售</option>
                            <option value="3">系统</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" style="display:none;">
                    <div class="input-group ">
                        <span class="input-group-addon">分配人</span>
                        <select class="opt-change form-control" id="id_allocation" >
                            <option value="-1">全部</option>
                            <option value="0">系统</option>
                            @foreach ( $allocation_list as $var )
                                <option value="{{$var['uid']}}">{{$var["account"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" style="display:none;">
                    <div class="input-group ">
                        <span class="input-group-addon">分配类型</span>
                        <select class="opt-change form-control" id="id_type" >
                            <option value="-1">全部</option>
                            <option value="1">助教自跟</option>
                            <option value="2">助转销</option>
                            <option value="3">销售自产</option>
                            <option value="4">系统转销</option>
                            <option value="5">系统转助</option>
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <input class="opt-change form-control" placeholder="电话、姓名回车搜索" id="id_search" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button id="id_jump" class="btn btn-primary">转介绍层级统计</button>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>电话</td>
                    <td>学生姓名</td>
                    <td>介绍人</td>
                    <td>创建人</td>
                    <td>创建人角色</td>
                    <td>资源首次进入时间</td>
                    <td>分配方式</td>
                    <td>负责人</td>
                    <td>负责人角色</td>
                    <td>系统转SD</td>
                    <td>分配人</td>
                    <td>是否试听申请</td>
                    <td>是否试听成功</td>
                    <td>是否签约</td>
                    <td>合同金额</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr class="referral-tr">
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["nick"]}} </td>
                        <td>{{$var["origin_nick"]}} </td>
                        <td>{{$var["create_nick"]}} </td>
                        <td>{{$var["create_role_str"]}} </td>
                        <td>{{$var["reg_time"]}} </td>
                        <td>{{$var["allocation_type"]}} </td>
                        <td>{{$var["admin_revisiter_nick"]}} </td>
                        <td>{{$var["admin_revisiter_role_str"]}} </td>
                        <td>{{$var["sd_nick"]}} </td>
                        <td>{{$var["admin_assigner_nick"]}} </td>
                        <td class='is_test_require'></td>
                        <td class='is_test_succ'></td>
                        <td class='is_order'></td>
                        <td class='order_money'></td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <span class="opt-show"></span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
