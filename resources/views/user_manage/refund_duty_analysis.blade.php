@extends('layouts.app')
@section('content')

    <section class="content ">

        <table class="common-table ">
            <thead>
                <tr >
                    <td rowspan="2">学生</td>
                    <td >申请退费时间</td>
                    <td >主要责任部门</td>
                    <td >部门责任占比</td>
                    <td >主要责任人|组别</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >
                            {{@$var["nick"]}} <br/>
                            {{@$var["phone"]}} <br/>
                        </td>
                        <td>{{@$var['apply_time_str']}}</td>
                        <td>{{@$var['main_deparment']}}</td>
                        <td>{{@$var['main_deparment_per']}}</td>
                        <td>
                            @if($var['seller_adminid'])
                                {{@$var['seller_nick']}} | {{@$var['seller_group']}} <br/>
                            @endif

                            @if($var['ass_adminid'])
                                {{@$var['ass_nick']}} | {{@$var['ass_group']}} 
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </section>

@endsection
