@extends('layouts.app')
@section('content')


    <section class="content ">
        <div>
            <div class="row">
               <div class="col-xs-12 col-md-4">
                    <div id="id_date_range"></div>
                </div>
            </div>
        </div>
        <div class="row">
           <div class="col-xs-6 col-md-2">
                <button class="btn btn-warning" id="id_add_complaint_user_info">添加培训信息</button>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>#</td>
                    <td>创建时间</td>
                    <td>创建人</td>
                    <td>老师姓名</td>
                    <td>学科</td>
                    <td>培训类型</td>
                    <td>培训状态</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['num']}}</td>
                        <td>{{$var['create_time']}}</td>
                        <td>{{$var['create_admin_nick']}}</td>
                        <td>{{$var['teacher_nick']}}</td>
                        <td>{{$var['subject_str']}}</td>
                        <td>{{$var['train_type_str']}}</td>
                        <td>{{$var['status']}}</td>
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

