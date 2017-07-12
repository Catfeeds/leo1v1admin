@extends('layouts.app')
@section('content')

<script src='/js/moment.js'></script>
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
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >渠道:</span>
                        <input type="text" id="id_origin" class="opt-change"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span >渠道EX:</span>
                        <input type="text" id="id_origin_ex" class="opt-change"/>
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
                        <td >预约总数</td>
                        <td >已分配销售</td>
                        <td >未回访</td>
                        <td >已回访</td>
                        <td >无效资源</td>
                        <td >未接通</td>
                        <td >有效意向(A)</td>
                        <td >有效意向(B)</td>
                        <td >有效意向(C)</td>
                        <td >试听-预约</td>
                        <td >试听-已排</td>
                        <td >已试听-待跟进</td>
                        <td >已试听-未签</td>
                        <td >已试听-已签</td>
                        <td >试听-时间待定</td>
                        <td >试听-时间确定</td>
                        <td >试听-无法排课</td>
                        <td >试听-驳回</td>

                        <td >首次付费</td>
                        <td >总付费</td>
                        <td > 操作</td>
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
                            <td >{{$var["revisited_yi"]}}</td>
                            <td >{{$var["revisited_wei"]}}</td>
                            <td >{{$var["revisited_yhf"]}}</td>
                            <td >{{$var["revisited_wuxiao"]}}</td>
                            <td >{{$var["no_call"]}}</td>
                            <td >{{$var["effective_a"]}}</td>
                            <td >{{$var["effective_b"]}}</td>
                            <td >{{$var["effective_c"]}}</td>
                            <td >{{$var["reservation"]}}</td>
                            <td >{{$var["revisited_yipai"]}}</td>
                            <td >{{$var["listened_dai"]}}</td>
                            <td >{{$var["listened_wei"]}}</td>
                            <td >{{$var["listened_yi"]}}</td>
                            <td >{{$var["listen_dai"]}}</td>
                            <td >{{$var["listen_que"]}}</td>
                            <td >{{$var["listen_cannot"]}}</td>
                            <td >{{$var["listen_refuse"]}}</td>
                            <td >{{$var["first_money"]}}</td>
                            <td >{{$var["money_all"]}}</td>
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

