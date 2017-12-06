@extends('layouts.app')
@section('content')
    <style>
     .note{
         margin-left:10px;
         font-size:13px;
         color: #999999;
     }
    </style>

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">标签一级分类</span>
                        <select class="opt-change form-control" id="id_tag_l1_sort" >
                        </select>

                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">标签二级分类</span>
                        <select class="opt-change form-control" id="id_tag_l2_sort" >
                        </select>

                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">标签三级分类</span>
                        <select class="opt-change form-control" id="id_tag_l3_sort" >
                        </select>

                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">标签名称</span>
                        <input class="opt-change form-control" id="id_tag_name" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button id="id_search" class="btn btn-primary"> 查找 </button>
                        <button id="id_add" class="btn btn-primary"> 添加标签 </button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>标签编号</td>
                    <td>标签名称</td>
                    <td>标签一级分类</td>
                    <td>标签二级分类</td>
                    <td>标签三级分类</td>
                    <td>权重系数</td>
                    <td>设定对象</td>
                    <td>标签定义</td>
                    <td>创建时间</td>
                    <td>创建者</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["tag_id"]}} </td>
                        <td>{{$var["tag_name"]}} </td>
                        <td>{{$var["tag_l1_sort"]}} </td>
                        <td>{{$var["tag_l2_sort"]}} </td>
                        <td>{{$var["tag_l3_sort"]}} </td>
                        <td>{{$var["tag_weight"]}} </td>
                        <td>{{$var["tag_object_str"]}} </td>
                        <td>{{$var["tag_desc"]}} </td>
                        <td>{{$var["create_time"]}} </td>
                        <td>{{@$var["account"]}} </td>
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
