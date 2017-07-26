@extends('layouts.stu_header')
@section('content')

      <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">
        <div class="row">
           <div class="col-xs-6 col-md-2">
                <button class="btn btn-warning" id="id_add_score_new">增加学生考试成绩</button>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>#</td>
                    <td>学生ID</td>
                    <td>创建时间</td>
                    <td>添加人</td>
                    <td>考试科目</td>
                    <td>测试分类</td>
                    <td>测试时间</td>
                    <td>分数</td>
                    <td>排名 </td>
                    <td>附件</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> {{$var["id"]}} </td>
                        <td> {{$var["userid"]}} </td>
                        <td> {{$var["create_time"]}} </td>
                        <td> {{$var["create_admin_nick"]}} </td>
                        <td> {{$var["subject_str"]}} </td>
                        <td> {{$var["stu_score_type_str"]}} </td>
                        <td> {{$var["stu_score_time"]}} </td>
                        <td> {{$var["score"]}} </td>
                        <td> {{$var["rank"]}} </td>
                        <td> {{$var["file_url"]}} </td>
                        <td>
                            <div class="opt-div" 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="修改信息"></a>
                                <a class="fa fa-trash-o opt-del" title="删除信息"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

