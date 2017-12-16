@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.pie.min.js"></script>
    <script src="/js/jquery.admin.js?{{@$_publish_version}}" type="text/javascript"></script>
    <section class="content">
        <div class="book_filter">
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">渠道等级</span>
                        <input class="opt-change form-control" id="id_origin_level" />
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table  table-clean-flag ">
                <thead>
                    <tr>
                        <td >key0</td>
                        <td >key1</td>
                        <td >key2</td>
                        <td >key3</td>
                        <td >渠道</td>
                        <td >例子总数</td>
                        <td >未拨打</td>
                        <td >未拨通</td>
                        <td >拨通-无效例子</td>
                        <td > 拨通-有效例子</td>
                        <td > TMK-有效例子</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["key0_class"]}}" class="key0" >{{$var["key0"]}}</td>
                            <td data-class_name="{{$var["key1_class"]}}" class="key1 {{$var["key0_class"]}}  {{$var["key1_class"]}}" >{{$var["key1"]}}</td>
                            <td  data-class_name="{{$var["key2_class"]}}" class=" key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{$var["key2"]}}</td>
                            <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{$var["key3"]}}</td>
                            <td data-class_name="{{$var["key4_class"]}}" class="key4   {{$var["key3_class"]}} {{$var["key4_class"]}}"  >{{$var["key4"]}}</td>
                            <td class="opt-link" data-opt_type ="all_count" >{{@$var["all_count"]}}</td>
                            <td class="opt-link" data-opt_type ="tq_no_call_count" >{{@$var["tq_no_call_count"]}}</td>
                            <td class="opt-link" data-opt_type ="tq_call_fail_count" >{{@$var["tq_call_fail_count"]}}</td>
                            <td class="opt-link" data-opt_type ="tq_call_succ_invalid_count" >{{@$var["tq_call_succ_invalid_count"]}}</td>
                            <td class="opt-link" data-opt_type ="valid_count" >{{@$var["valid_count"]}}</td>
                            <td class="opt-link" data-opt_type ="tmk_valid_count" >{{@$var["tmk_valid_count"]}}</td>
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
        </div>
    </section>
@endsection
