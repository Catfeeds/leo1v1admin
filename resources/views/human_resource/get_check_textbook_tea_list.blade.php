@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span >是否设置过教材</span>
                        <select id="id_textbook_check_flag" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4" data-always_show="1">
                    <div class="input-group ">
                        <input type="text" class="click_on opt-change" id="id_user_name"  placeholder="姓名, 手机号回车查找" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >姓名</td>
                    <td>手机号</td>
                    <td>教务备注</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["tea_note"]}} </td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                                <a class="opt-teacher-info" title="老师信息">老师信息</a>
                                <a class="opt-tea_note" title="设置备注">设置备注</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
