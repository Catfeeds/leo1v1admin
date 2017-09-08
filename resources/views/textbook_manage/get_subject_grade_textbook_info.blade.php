@extends('layouts.app')
@section('content')
    <script src='/js/moment.js'></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <style>
     .fc-event {
         border-radius:0px;
     }
    </style>


    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-md-1 remove-for-xs col-xs-6 " >
                    <div>
                        <button class="btn btn-primary" id="id_upload_xls"> 上传xls </button>
                    </div>
                </div>
                             
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >年级</span>
                        <select id="id_grade" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="address" id="id_address"  placeholder="地区,学制等 回车查找" />
                    </div>
                </div>



               
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>省</td>
                    <td>市</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>教材版本</td>
                    <td>学制</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["province"]}} </td>
                        <td>{{@$var["city"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["textbook_str"]}} </td>
                        <td>{{@$var["educational_system"]}} </td>
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

