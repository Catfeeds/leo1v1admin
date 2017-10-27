@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>

    <script type="text/javascript" src="/page_js/seller_student_new/common.js?{{@$_publish_version}}"></script>




    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">处理状态</span>
                        <select id="id_is_complaint_state" class="opt-change" >
                        </select>

                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">投诉人类型</span>
                        <select class="opt-change form-control" id="id_account_type" />
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否跟进</span>
                        <select class="opt-change form-control" id="id_is_allot_flag" >
                            <option value="-1">全部</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                </div>



            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>页面名称 </td>
                    <td>页面ID </td>
                    <td>页面链接 </td>
                    <td>用户姓名[adminid]</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $key=>$var )
                    <tr>
                        <td>{{@$key}} </td>
                        <td>{{@$var["complaint_type_str"]}} </td>
                        <td>{{@$var["account_type_str"]}} </td>
                        <td>{{@$var["user_nick"]}}/{{@$var["phone"]}} </td>
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
