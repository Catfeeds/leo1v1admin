@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-2">
                    <button type="button" class="btn btn-primary " id="id_upload_xls" > 上传xlsx</button>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否新例子</span>
                        <select class="opt-change form-control" id="id_is_new_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <button id="id_del_all_exam" type="button" class="btn btn-danger">删除当前批次例子</button>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <button id="id_do_publish" type="button" class="btn btn-info">提交当前批次例子</button>
                </div>

            </div>

        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>编号</td>
                    <td>手机号</td>
                    <td>归属地</td>
                    <td>时间</td>
                    <td>来源</td>
                    <td>姓名</td>
                    <td>用户备注</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>是否有pad</td>
                    <td>是否新例子</td>
                    <td>发布时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["index"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["phone_location"]}} </td>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["origin"]}} </td>
                        <td>{{$var["name"]}} </td>
                        <td>{{$var["user_desc"]}} </td>
                        <td>{{$var["grade_str"]}} </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["has_pad_str"]}} </td>
                        <td>{{$var["is_new_flag_str"]}} </td>
                        <td>{{$var["publish_time"]}} </td>
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
