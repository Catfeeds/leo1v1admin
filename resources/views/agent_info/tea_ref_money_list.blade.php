@extends('layouts.teacher_header')
@section('content')
    <script>
     var teacher_ref_type = {{$teacher_ref_type}};
    </script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <div class="input-group">
                        <span>日期</span>
                        <select id="id_start_date" class="opt-change">
                            <option value="2017-03">2017-03</option>
                            <option value="2017-04">2017-04</option>
                            <option value="2017-05">2017-05</option>
                            <option value="2017-06">2017-06</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group">老师总工资</span>
                        <input value="{{@$all_money["teacher_money"]}}">
                        <span class="input-group">代理总工资</span>
                        <input value="{{@$all_money["all_money"]}}">
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>姓名</td>
                    <td>手机</td>
                    <td>老师工资</td>
                    <td>代理应得</td>
                    <td>推荐人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['realname']}}</td>
                        <td>{{$var['phone']}}</td>
                        <td>{{$var['money']}}</td>
                        <td>{{$var['teacher_ref_money']}}</td>
                        <td>{{$var['reference_name']}}</td>
                        <td>
                            <div class="opt-div" 
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
