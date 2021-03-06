@extends('layouts.app')
@section('content')
    <section class="content ">
        <hr/>

        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>序号 </td>
                    <td>手机号</td>
                    <td>微信昵称</td>
                    <td>上级微信昵称</td>
                    <td>上上级微信昵称</td>
                    <td>渠道</td>
                    <td>是否成功试听</td>
                    <td>类型</td>
                    <td>老师</td>
                    <td>系统判定是否有效</td>
                    <td>上课时间</td>
                    <td>创建时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["num"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["nickname"]}} </td>
                        <td>{{@$var["p_nickname"]}} </td>
                        <td>{{@$var["pp_nickname"]}} </td>
                        <td>{{@$var["origin"]}} </td>
                        @if(@$var['lesson_user_online_status'])
                            <td>是 </td>
                        @else
                            <td>否 </td>
                        @endif
                        @if(@$var['type'] == 1)
                            <td>报名上课 </td>
                        @elseif(@$var['type'] == 2)
                            <td>我要推荐 </td>
                        @else
                            <td>注册</td>
                        @endif
                        <td>{{@$var["tea_nick"]}} </td>
                        <td>{!! @$var["lesson_user_online_status_str"] !!} </td>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a style="display:none;" class="fa fa-edit opt-edit aaa"  title="编辑"> </a>
                                <a style="display:none;" class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
