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
                        <!-- <span >xx</span>
                             <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  /> -->
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button style="display:none;" id="id_add"> 增加</button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>订单id </td>
                    <td>手机号 </td>
                    <td>微信昵称 </td>
                    <td>合同金额 </td>
                    <!-- <td>上级手机号 </td> -->
                    <td>上级微信昵称 </td>
                    <td>上级转介绍费 </td>
                    <td>订单确认时上级等级 </td>
                    <!-- <td>上上级手机号 </td> -->
                    <td>上上级微信昵称 </td>
                    <td>上上级转介绍费 </td>
                    <td>订单确认时上上级等级 </td>
                    <td>例子进入时间 </td>
                    <td>订单确认时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["orderid"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["nickname"]}} </td>
                        <td>{{@$var["price"]}} </td>
                        <!-- <td>{{@$var["p_phone"]}} </td> -->
                        <td>{{@$var["p_nickname"]}} </td>
                        <td>{{@$var["p_price"]}} </td>
                        <td>{{@$var["p_level_str"]}} </td>
                        <!-- <td>{{@$var["pp_phone"]}} </td> -->
                        <td>{{@$var["pp_nickname"]}} </td>
                        <td>{{@$var["pp_price"]}} </td>
                        <td>{{@$var["pp_level_str"]}} </td>
                        <td>{{@$var["a_create_time"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <!-- <a class="fa fa-edit opt-edit"  title="编辑"> </a> -->
                                <a class="fa-user opt-user " title="上课记录" ></a>
                                <!-- <a class="fa fa-times opt-del" title="删除"> </a> -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
