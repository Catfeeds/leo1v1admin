@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
     <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">
       <div>
            <div class="row">
               <div class="col-xs-12 col-md-4">
                    <div id="id_date_range"></div>
                </div>
            </div>
        </div>
        <hr/>

        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>#</td>
                    <td>学生</td>
                    <td>科目</td>
                    <td>月份</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["num"]}} </td>
                        <td>{{$var["student_nick"]}} </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="补充考试信息">补充考试记录</a>
                                <a class="fa fa-times opt-del"  title="取消添加考试信息">取消添加考试记录</a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

