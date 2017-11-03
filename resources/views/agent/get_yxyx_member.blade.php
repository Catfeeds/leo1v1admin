@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <input type="text" value=""  class="opt-change"  id="id_phone"  placeholder="输入手机号/微信号 查询"  />
                    </div>
                </div>
            </div>
            <div class="row" id="">
                <div class="col-xs-12">
                    当前页面统计<br>
                总例子数:{{$all_user}}
                下单总人数:{{$order_user}}
                金额总数:{{$price}}
                </div>
            </div>
        </div>

        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>电话/微信名</td>
                    <td>上级电话/微信名</td>
                    <td>上上级电话/微信名</td>
                    <td>TMD例子数</td>
                    <td>MTD(TQ全局未回访)</td>
                    <td>MTD(未接通)</td>
                    <td>MTD(已接通)</td>
                    <td>MTD(已接通未排课)</td>
                    <td>MTD(已排课)</td>
                    <td>MTD(已排课取消人数)</td>
                    <td>MTD(成功试听数)</td>
                    <td>MTD(到课率)</td>
                    <td>MTD(成功试听未签约数)</td>
                    <td>MTD(签约人数)</td>
                    <td>MTD(签约率)</td>
                    <td>MTD(签约金额)</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr data-id="{{@$var['id']}}" class="opt-a">
                        <td>{{@$var["phone1"]}} <br/>/ {{@$var["nick1"]}} </td>
                        <td>{{@$var["phone2"]}} <br/>/ {{@$var["nick2"]}} </td>
                        <td>{{@$var["phone3"]}} <br/>/ {{@$var["nick3"]}} </td>
                        <td><a href="javascript:;" data-type="user_count"> {{@$var["user_count"]}} </a> </td>
                        <td><a href="javascript:;" data-type="no_revisit_count"> {{@$var["no_revisit_count"]}}</a> </td>
                        <td><a href="javascript:;" data-type="no_phone_count"> {{@$var["no_phone_count"]}}</a> </td>
                        <td><a href="javascript:;" data-type="ok_phone_count"> {{@$var["ok_phone_count"]}}</a> </td>
                        <td><a href="javascript:;" data-type="ok_phone_no_lesson"> {{@$var["ok_phone_no_lesson"]}} </a></td>
                        <td><a href="javascript:;" data-type="rank_count"> {{@$var["rank_count"]}} </a></td>
                        <td><a href="javascript:;" data-type="del_lesson_count"> {{@$var["del_lesson_count"]}}</a> </td>
                        <td><a href="javascript:;" data-type="ok_lesson_count"> {{@$var["ok_lesson_count"]}}</a> </td>
                        <td>{{@$var["ok_lesson_rate"]}}</td>
                        <td><a href="javascript:;" data-type="ok_lesson_no_order"> {{@$var["ok_lesson_no_order"]}}</a> </td>
                        <td><a href="javascript:;" data-type="order_user_count"> {{@$var["order_user_count"]}}</a> </td>
                        <td>{{@$var["order_rate"]}}</td>
                        <td>{{@$var["price"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
