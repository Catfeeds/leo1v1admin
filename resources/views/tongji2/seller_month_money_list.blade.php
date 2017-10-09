@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
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
                    <td >签约总金额 </td>
                    <td >分期金额 </td>
                    <td >非分期金额 </td>
                    <td >一天内签约金额 </td>
                    <td >团队签约金额</td>
                    <td >团队签约目标</td>
                    <td >特殊申请金额</td>

                    <td style="display:none;"  >正常签约金额 </td>
                    <td  style="display:none;" > 单纯 特殊申请  </td>
                    <td style="display:none;" > 单纯 一天内 签约金额 </td>
                    <td style="display:none;" >特殊申请 并且一天内 签约金额 </td>

                    <td >提成点</td>
                    <td >主管提成点</td>
                    <td >入职时间</td>
                    <td >新员工提成系数</td>
                    <td >提成金额</td>
                    <td >单月提成金额</td>
                    <td >季度提成金额</td>
                    <td >计算方式</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )

                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>


                        <td class="all_price" ></td>
                        <td class="stage_money" ></td>
                        <td class="no_stage_money" ></td>
                        <td class="24_hour_all_price"></td>
                        <td class="group_all_price"  ></td>
                        <td class="group_default_money"  ></td>
                        <td class="require_all_price" ></td>
                        <td class="all_price_1" ></td>
                        <td class="require_all_price_1" ></td>
                        <td class="v24_hour_all_price_1" ></td>
                        <td class="require_and_24_hour_price_1" ></td>

                        <td class="percent" ></td>
                        <td class="group_money_add_percent" ></td>
                        <td class="create_time" ></td>
                        <td class="new_account_value" ></td>
                        <td class="money" ></td>
                        <td class="cur_month_money" ></td>
                        <td class="three_month_money" ></td>
                        <td class="desc" ></td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-show"  title="编辑"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
