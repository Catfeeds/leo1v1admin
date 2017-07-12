@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">特殊申请</span>
                        <select class="opt-change form-control" id="id_require_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" data-always_show="1" >
                    <div class="input-group ">
                        <span class="input-group-addon">小于90课时</span>
                        <select class="opt-change form-control" id="id_class_hour" >
                        </select>
                    </div>
                </div>
        </div>
            <div class="row  " >
                <div class="col-xs-12 col-md-12">
                    总价：{{$all_discount_price}}  &nbsp; 总收入：{{$all_price}}   总折扣率：{{$all_discount_rate}}
                </div>
            </div>

        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> ID </td>
                    <td> 姓名 </td>
                    <td> 科目 </td>
                    <td> 年级</td>
                    <td> 原价格  </td>
                    <td> 折扣价格  </td>
                    <td> 最终价格  </td>
                    <td> 购买课时  </td>
                    <td> 赠送课时 </td>
                    <td> 折扣总价 </td>
                    <td> 折扣率 </td>
                    <td> 负责人 </td>
                    <td> 账号角色 </td>
                    <td> 编辑  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["orderid"]}} </td>
                        <td>{{@$var["student_nick"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["discount_price"]/100}} </td>
                        <td>{{@$var["promotion_discount_price"]/100}} </td>
                        <td>{{@$var["price"]/100}} </td>
                        <td>{{@$var["t_1_lesson_count"]/100}} </td>
                        <td>{{@$var["t_2_lesson_count"]/100}} </td>
                        <td>{{@$var["cost_price"]}} </td>
                        <td>{{@$var["discount_rate"]}} </td>
                        <td>{{@$var["sys_operator"]}} </td>
                        <td>{{@$var["account_role_str"]}} </td>
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
