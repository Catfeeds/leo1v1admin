@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/seller_student_new/common.js?{{@$_publish_version}}"></script>
    
    <section class="content ">
        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">phone</span>
                        <input class="opt-change form-control" id="id_phone" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>学员信息</td>
                    @if($type != 2)
                        <td>试听</td>
                        <td>签单量</td>
                        <td>签单金额</td>
                        <td>跟进销售</td>
                        <td>负责助教</td>
                        <td> 操作  </td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["phone"]}}/{{@$var["nickname"]}}</td>
                        @if($type != 2)
                            <td>{{@$var["is_test_lesson_str"]}}</td>
                            <td>{{@$var["self_order_count"]}}</td>
                            <td>{{@$var["self_order_price"]}}</td>
                            <td>{{@$var["sys_operator"]}}</td>
                            <td>{{@$var["teach_assistantant"]}}</td>
                            <td>
                                <div
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >

                                    <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>
                                    <a class="fa fa-phone opt-telphone " title="电话列表"> </a>
                                    <a style="display:none;"  class="fa fa-times opt-del" title="删除"> </a>

                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection


