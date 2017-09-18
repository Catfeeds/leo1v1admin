@extends('layouts.teacher_header')
@section('content')


    <section class="content ">
        <div>
            <div class="row">
               <div class="col-xs-12 col-md-4">
                    <div id="id_date_range"></div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>培训类型</span>
                        <select id="id_train_type" class="opt-change" >
                        </select>
                    </div>
                </div>
                 <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>学科</span>
                        <select id="id_subject" class="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>培训状态</span>
                        <select id="id_status" class="opt-change" >
                        </select>
                    </div>
                </div>
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
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      
                        <td></td>
                 
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

