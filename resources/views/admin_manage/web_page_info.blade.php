@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">删除</span>
                        <select class="opt-change form-control" id="id_del_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-1">
                    <button class="btn btn-primary " id="id_add"> 添加 </button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>编号</td>
                    <td>标题 </td>
                    <td>url</td>
                    <td>添加人</td>
                    <td>添加时间</td>
                    <td>删除</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["web_page_id"]}} </td>
                        <td>{{@$var["title"]}} </td>
                        <td>{{@$var["url"]}} </td>
                        <td>{{@$var["add_adminid_nick"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["del_flag_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-share opt-share"  title="分享"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
