@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row" >
                <div class="col-xs-12 col-md-3"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">展示</span>
                        <select class="opt-change form-control" id="id_show_type" >
                            <option value="1">按负责人角色</option>
                            <option value="2">按分配类型</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button id="id_jump" class="btn btn-primary">转介绍数据详情</button>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>类型</td>
                    <td>主管</td>
                    <td>小组</td>
                    <td>负责人</td>
                    <td>转介绍例子数</td>
                    <td>试听课申请</td>
                    <td>试听成功</td>
                    <td>合同数</td>
                    <td>合同人数</td>
                    <td>合同金额</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)

                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>

                        <td>{{@$var['referral_num']}}</td>
                        <td>{{@$var['test_lesson_require']}}</td>
                        <td>{{@$var['test_lesson_succ']}}</td>
                        <td>{{@$var["orderid_num"]}} </td>
                        <td>{{@$var["userid_num"]}} </td>
                        <td>{{@$var["price_num"]}} </td>

                        <td><div class=" row-data"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            <!-- <a class="fa-comments opt-set-vertical" > </a> -->
                        </div></td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        @include("layouts.page")
    </section>
    
@endsection


