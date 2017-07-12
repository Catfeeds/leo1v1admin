@extends('layouts.app')
@section('content')

<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>


    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">adminid</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>

                <div class="col-md-1 remove-for-xs col-xs-6 "" >
                    <div> 
                        <button class="btn btn-primary" id="id_upload_xls"> 上传xls </button>
                    </div>
                </div>

            </div>

        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>时间 </td>
                    <td>考勤卡</td>
                    <td>账号</td>
                    <td>adminid</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["logtime"]}} </td>
                        <td>{{@$var["cardid"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["uid"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

