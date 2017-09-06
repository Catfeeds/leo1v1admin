@extends('layouts.app')
@section('content')


    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-4" >
                    <div class="input-group ">
                        <span class="input-group-addon">渠道选择</span>
                        <input class="opt-change form-control" id="id_origin_ex" />
                    </div>
                </div>

        <div class="col-xs-6 col-md-3">
            <div class="input-group ">
                <span class="input-group-addon">tmk状态</span>
                <input class="opt-change form-control" id="id_tmk_student_status" />
            </div>
        </div>


        <div class="col-xs-6 col-md-3">
            <div class="input-group ">
                <span class="input-group-addon">渠道等级</span>
                <input class="opt-change form-control" id="id_origin_level" />
            </div>
        </div>
                <div class="col-xs-6 col-md-4" >
                    <div class="input-group ">
                        <span>例子产出: {{intval($old_per_price)}} </span>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                        <td>类型 </td>
                        <td>主管 </td>
                        <td>小组 </td>
                        <td>负责人 </td>
                        <td>入职时间</td>
                        <td>新用户数</td>
                        <td>试听课数</td>
                        <td>签单数</td>
                        <td>签单金额</td>
                        <td>消耗价值</td>
                        <td>例子平均产出</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>


                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["new_user_count"]}} </td>
                        <td>{{@$var["test_lesson_count"]}} </td>
                        <td>{{@$var["order_count"]}} </td>
                        <td>{{@$var["order_money"]}} </td>
                        <td>{{@$var["old_money"]}} </td>
                        <td>{{@$var["per_price"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
