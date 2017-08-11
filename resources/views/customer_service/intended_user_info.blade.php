@extends('layouts.app')
@section('content')


    <section class="content ">
        <div class="row">
           <div class="col-xs-6 col-md-2">
                <button class="btn btn-warning" id="id_add_indended_user_info">增加意向用户信息</button>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>#</td>
                    <td>创建时间</td>
                    <td>创建人</td>
                    <td>联系电话</td>
                    <td>孩子姓名</td>
                    <td>家长姓名</td>
                    <td>关系</td>
                    <td>地区</td>
                    <td>年级</td>
                    <td>试听科目</td>
                    <td>教材版本</td>
                    <td>备注</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['num']}}</td>
                        <td>{{$var['create_time']}}</td>
                        <td>{{$var['create_admin_nick']}}</td>
                        <td>{{$var['phone']}}</td>
                        <td>{{$var['child_realname']}}</td>
                        <td>{{$var['parent_realname']}}</td>
                        <td>{{$var['relation_ship_str']}}</td>
                        <td>{{$var['region']}}</td>
                        <td>{{$var['grade_str']}}</td>
                        <td>{{$var['free_subject_str']}}</td>
                        <td>{{$var['region_version_str']}}</td>
                        <td>{{$var['notes']}}</td>
                        <td>
                            <div class="opt-div" 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="修改信息"></a>
                                <a class="fa fa-trash-o opt-del" title="删除信息"></a>
                                <a class=" opt-reset"  title="刷新">刷新</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

