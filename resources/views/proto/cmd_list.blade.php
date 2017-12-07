@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >项目</span>
                        <select   id="id_project" class="opt-change"   >
                            <option value="yb_admin">yb_admin</option>
                            <option value="agent_api">agent_api</option>
                            <option value="class_api">class_api</option>
                            <option value="yb_account">yb_account</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >标签</span>
                        <select   id="id_tag"  class="opt-change" >
                                <option value=""> 全部 </option>
                            @foreach ( $tag_list as $tag )
                                <option> {{$tag}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3 " data-always_show="1" >
                    <div class="input-group ">
                        <span class="input-group-addon">关键字</span>
                        <input class="opt-change form-control" id="id_query_str" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-1">
                    <button id="id_all_cmd_desc" class="btn btn-primary"> 对外文档 </button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>命令号</td>
                    <td>名称</td>
                    <td>标签</td>
                    <td>说明</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["cmdid"]}} </td>
                        <td>{{@$var["name"]}} </td>
                        <td>{{@$var["tags"]}} </td>
                        <td>{{@$var["desc"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-search opt-show"  title="查看"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
