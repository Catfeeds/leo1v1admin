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


                    <td style="min-width:120px;">操作</td>
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
                        <td></td>
                        <td>
                            <div
                                {!!\App\Helper\Utils::gen_jquery_data($var)!!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </section>

@endsection
