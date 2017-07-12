@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-3">
                    <div class="input-group " >
                        <span >工资分类</span>
                        <select class="opt-change"  id="id_teacher_money_type"   > </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >等级</span>
                        <select class="opt-change"  id="id_level"   > </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>累计课时 </td>
                    <td>小一</td>
                    <td>小二</td>
                    <td>小三</td>
                    <td>小四</td>
                    <td>小五</td>
                    <td>小六</td>
                    <td>初一</td>
                    <td>初二</td>
                    <td>初三</td>
                    <td>高一</td>
                    <td>高二</td>
                    <td>高三</td>
                    <td> 操作  </td> </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["lesson_count"]}}</td>
                        <td>{{$var["f_101"]}}</td>
                        <td>{{$var["f_102"]}}</td>
                        <td>{{$var["f_103"]}}</td>
                        <td>{{$var["f_104"]}}</td>
                        <td>{{$var["f_105"]}}</td>
                        <td>{{$var["f_106"]}}</td>
                        <td>{{$var["f_201"]}}</td>
                        <td>{{$var["f_202"]}}</td>
                        <td>{{$var["f_203"]}}</td>
                        <td>{{$var["f_301"]}}</td>
                        <td>{{$var["f_302"]}}</td>
                        <td>{{$var["f_303"]}}</td>
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

