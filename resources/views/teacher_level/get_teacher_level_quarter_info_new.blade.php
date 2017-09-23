@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_add_teacher"> 新增晋升老师 </button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>老师</td>
                    <td>当前等级</td>
                    <td>入职时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if(empty($var["require_time"]))
                                    <a class="opt-advance-require" title="晋升申请">晋升申请</a>
                                @endif
                                @if($var["hand_flag"]==1)
                                    <a class="opt-add-hand" title="手动刷新数据">手动刷新数据</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
