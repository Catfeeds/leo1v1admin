@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>合同id </td>
                    <td>学生 </td>
                    <td>CC </td>
                    <td>下单人 </td>
                    <td>价格 </td>
                    <td>订单时间 </td>
                    <td>TMK分配时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["orderid"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["cc_nick"]}} </td>
                        <td>{{@$var["sys_operator"]}} </td>
                        <td>{{@$var["order_money"]}} </td>
                        <td>{{@$var["order_time"]}} </td>
                        <td>{{@$var["tmk_assign_time"]}} </td>
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
