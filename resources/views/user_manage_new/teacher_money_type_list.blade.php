@extends('layouts.app')
@section('content')
    <section class="content">
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
                <!-- <div class="col-xs-12 col-md-2">
                     <div class="input-group ">
                     <button class="btn btn-primary" id="id_add_teacher_money_type">修改当前工资配置</button>
                     </div>
                     </div> -->
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>年级</td>
                    @foreach($total_type as $var)
                        <td>课时{{$var}}</td>
                    @endforeach
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["grade_str"]}}</td>
                        <td class="grade_{{$var['grade']}}" data-money="{{$var['money_0']}}">{{$var["money_0"]}}</td>
                        <td>{{@$var["money_1"]}}</td>
                        <td>{{@$var["money_2"]}}</td>
                        <td>{{@$var["money_3"]}}</td>
                        <td>{{@$var["money_4"]}}</td>
                        <td>{{@$var["money_5"]}}</td>
                        <td>{{@$var["money_6"]}}</td>
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
