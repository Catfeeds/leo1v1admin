@extends('layouts.app')
@section('content')

    <section class="content ">

        <table class="common-table ">
            <thead>
                <tr >
                    <td rowspan="2">学生</td>
                    <td >申请退费时间</td>
                    <td >主要责任</td>
                    <td >责任占比</td>
                    <td >主要责任人|组别</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >
                            <a href="/user_manage/refund_list?userid={{@$var['userid']}}&start_time={{@date('Y-m-01',$var['apply_time'])}}&end_time={{@$var['apply_time']}}">{{@$var["nick"]}}</a> <br/>
                            {{@$var["phone"]}} <br/>
                        </td>
                        <td>{{@$var['apply_time_str']}}</td>
                        <td>{{@$var['main_deparment']}}</td>
                        <td>{{@$var['main_deparment_per']}}</td>
                        <td>
                            @if($var['seller_group'])
                                销售: {{@$var['seller_nick']}} | {{@$var['seller_group']}} <br/>
                            @endif

                            @if($var['ass_group'])
                               助教: {{@$var['ass_nick']}} | {{@$var['ass_group']}} 
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            @include("layouts.page")
    </section>

@endsection
