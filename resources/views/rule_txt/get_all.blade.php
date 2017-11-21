@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                @if($edit_flag)
                    <div class="col-xs-6 col-md-2">
                        <div class="input-group " >
                            <button class="btn btn-info opt-add-rule">新增规则</button>
                        </div>
                    </div>

                    <div class="col-xs-6 col-md-2">
                        <div class="input-group " >
                            <button class="btn btn-warning opt-add-pro">新增流程</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <hr/>
        <div style="border:2px solid #ccc;">
            <h2 style="text-align:center">规则列表</h2>
            <table  class="common-table">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>规则标题</td>
                        <td>更新日期</td>
                        <td>操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $rule as $var )
                        <tr>
                            <td>{{@$var["rule_id"]}} </td>
                            <td> <a href="javascript:;" class="opt-rule-detail" data-id="{{@$var["rule_id"]}}"> {{@$var["title"]}} </a></td>
                            <td>{{@$var["create_time"]}} </td>
                            <td>
                                <div
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    @if($edit_flag)
                                        <a class="fa fa-edit opt-edit-rule"  title="编辑"> </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="border:2px solid #ccc;margin-top:50px;">
            <h2 style="text-align:center">流程文档</h2>
            <table  class="common-table">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>流程</td>
                        <td>更新日期</td>
                        <td>操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $process as $var )
                        <tr>
                            <td>{{@$var["process_id"]}} </td>
                            <td> <a href="javascript:;" class="opt-pro" data-id="{{@$var["process_id"]}}"> {{@$var["name"]}} </a></td>
                            <td>{{@$var["create_time"]}} </td>
                            <td>
                                <div
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >

                                    @if($edit_flag)
                                        <a class="fa fa-edit opt-edit-pro"  title="编辑"> </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        @include("layouts.page")
    </section>

@endsection
