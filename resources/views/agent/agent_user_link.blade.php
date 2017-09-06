@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">phone</span>
                        <input class="opt-change form-control" id="id_phone" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> 一级</td>
                    <td> 一级 试听 </td>
                    <td>  一级签单 </td>
                    <td>  一级提成 </td>
                    <td>  一级等级 </td>
                    <td>  一级试听成功提成 </td>
                    <td>  一级试听成功 可提现 </td>
                    <td> 二级 </td>
                    <td> 二级 试听 </td>
                    <td> 二级 签单</td>
                    <td>  二级提成 </td>
                    <td>  二级等级 </td>

                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["p1_name"]}}</td>
                        <td> {!! @$var["p1_test_lesson_flag_str"] !!} </td>
                        <td> {!! @$var["p1_price"] !!} </td>
                        <td> {!! @$var["p1_p_price"] !!} </td>
                        <td> {!! @$var["p1_p_agent_level_str"] !!} </td>

                        <td> {!! @$var["p1_agent_status_money"] !!} </td>
                        <td> {!! @$var["p1_agent_status_money_open_flag_str"] !!} </td>
                        <td> {{@$var["p2_name"]}} </td>
                        <td> {!! @$var["p2_test_lesson_flag_str"] !!} </td>
                        <td> {!! @$var["p2_price"] !!} </td>
                        <td> {!! @$var["p2_p_price"] !!} </td>
                        <td> {!! @$var["p2_p_agent_level_str"] !!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                                <a style="display:none;"  class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection


