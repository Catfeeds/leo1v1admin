@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>

    <section class="content li-section">

        <div>

            <div class="row  " >

                <div class="col-xs-12 col-md-6"  >
                    <div style="font-size:18px" > {{$cur_dir}} <a href="#" class=" fa fa-share color-blue" id="id_share_cur"  >分享 </a>   </div>
                </div>

                <div class="col-xs-12 col-md-6"  id="id_add_dir_parent" >
                    <button class="btn btn-info-ly" id="id_add_dir"> 创建文件夹</button>
                    <button class="btn btn-info-ly" id="id_add_file"> 上传文件</button>
                </div>
            </div>

        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>名称</td>
                    <td>大小 </td>
                    <td>创建时间 </td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td class="file_name" >{{@$var["file_name"]}} </td>
                        <td>{{@$var["file_size"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-download opt-download"  title="下载"> </a>
                                <a class="fa fa-edit opt-edit"  title="修改名字"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a class="fa fa-share pt-share"  title="分享"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
