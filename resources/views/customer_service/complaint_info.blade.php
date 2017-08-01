@extends('layouts.app')
@section('content')


    <section class="content ">
        <div class="row">
           <div class="col-xs-6 col-md-2">
                <button class="btn btn-warning" id="id_add_complaint_user_info">添加用户投诉信息</button>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>#</td>
                    <td>创建时间</td>
                    <td>创建人</td>
                    <td>姓名</td>
                    <td>联系方式</td>
                    <td>身份</td>
                    <td>投诉内容</td>
                    <td>跟进状态</td>
                    <td>处理人</td>
                    <td>分配时间</td>
                    <td>处理状态</td>
                    <td>解决方案</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['num']}}</td>
                        <td>{{$var['create_time']}}</td>
                        <td>{{$var['create_admin_nick']}}</td>
                        <td>{{$var['username']}}</td>
                        <td>{{$var['phone']}}</td>
                        <td>{{$var['complaint_user_type_str']}}</td>
                        <td>{{$var['content']}}</td>
                        <td>{{$var['status']}}</td>
                        <td>{{$var['operator']}}</td>
                        <td>{{$var['assign_time']}}</td>
                        <td>{{$var['process_state']}}</td>
                        <td>{{$var['solution']}}</td>
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

