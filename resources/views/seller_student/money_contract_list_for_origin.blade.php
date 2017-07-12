@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/seller_student/common.js"></script>
 <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <section class="content">
        <div class="book_filter">

            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <span >时间:</span>
                        <input type="text" id="id_start_date" class="opt-change"/>
                        <span >-</span>
                        <input type="text" id="id_end_date" class="opt-change"/>
                    </div>
                </div>

              </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >key1</td>
                        <td >key2</td>
                        <td >key3</td>
                        <td >渠道</td>
                        <td >例子数</td>
                        <td >未回访</td>
                        <td >已回访</td>
                        <td >无效</td>
                        <td >未接通</td>
                        <td >试听人次</td>
                        <td >试听人数</td>
                        <td >合同数</td>
                        <td >人数</td>
                        <td >新签金额</td>
                        <td > 新签人均金额</td>
                        <td >总金额</td>
                        <td >人均金额</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["key1_class"]}}" class="key1" >{{$var["key1"]}}</td>
                            <td  data-class_name="{{$var["key2_class"]}}" class=" key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{$var["key2"]}}</td>
                            <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{$var["key3"]}}</td>
                            <td data-class_name="{{$var["key4_class"]}}" class="key4   {{$var["key3_class"]}} {{$var["key4_class"]}}"  >{{$var["key4"]}}</td>
                            <td >{{$var["al_count"]}}</td>
                            <td >{{$var["revisited_wei"]}}</td>
                            <td > {{$var["al_count"]- $var["revisited_wei"]}}</td>
                            <td >{{$var["revisited_wuxiao"]}}</td>
                            <td >{{$var["no_call"]}}</td>
                            <td >{{$var["test_count"]}}</td>
                            <td >{{$var["test_user_count"]}}</td>
                            <td >{{$var["order_count"]}}</td>
                            <td >{{$var["user_count"]}}</td>
                            <td >{{$var["new_price"]}}</td>
                            <td >{{ $var["user_count"]? intval($var["new_price"]/$var["user_count"]):"--"}} </td>
                            <td >{{$var["money_all"]}}</td>
                            <td >{{ $var["user_count"]? intval($var["money_all"]/$var["user_count"]):"--"}} </td>
                            <td>
                                <div></div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection

