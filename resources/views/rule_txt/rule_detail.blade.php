@extends('layouts.app')
@section('content')
    <script src="/AdminLTE-2.4.0-rc/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

    <section class="content ">
        <div class="row" id="rule" data-id="{{@$rule['rule_id']}}">
            <div class="col-xs-6 col-md-2">
                <button class="btn btn-info opt-add">添加规则</button>
            </div>
            <div class="col-xs-12" style="text-align:center;">
                <h2>{{@$rule['title']}}</h2>
            </div>
            <div class="col-xs-12" style="text-align:right;padding-right:100px;">
                <h5>{{@$rule['create_time']}}</h5>
            </div>
            <div class="col-xs-12">
                <h3>重要提示:</h3>
                {!!@$rule['tip']!!}
            </div>

        </div>
        <table class="common-table" >
            <thead>
                <tr>
                    <td>规则等级</td>
                    <td>规则名称</td>
                    <td style="width:50%;">规则明细</td>
                    <td>质检扣分</td>
                    <td>处罚方式</td>
                    <td>附加处罚</td>
                    <td>更新日期</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $key => $var )
                    <tr>
                        @if( @$row[ $var["level_str"] ]['start'] == $key )
                            <td rowspan="{{@$row[ $var["level_str"] ]['num']}}" align="middle">{{@$var["level_str"]}} </td>
                        @endif
                        <td>{{@$var["name"]}} </td>
                        <td>{!!@$var["content"]!!} </td>
                        <td>{{@$var["deduct_marks_str"]}} </td>
                        <td>{{@$var["punish_type"]}} </td>
                        <td>{!! @$var["punish"] !!} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a class="fa fa-long-arrow-up opt-up" title="上移"> </a>
                                <a class="fa fa-long-arrow-down opt-down" title="下移"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
