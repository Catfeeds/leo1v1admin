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
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>#</td>
                    <td>Bug编号</td>
                    <td>提出人</td>
                    <td>联系方式</td>
                    <td>项目名称</td>
                    <td>优先级</td>
                    <td>bug提交时间</td>
                    <td>期待完成时间</td>
                    <td>详细说明</td>
                    <td>附件</td>
                    <td>负责人</td>
                    <td>负责人联系方式</td>
                    <td>移接人</td>
                    <td>移接人联系方式</td>
                    <td>状态</td>
                    <td>操作</td>

                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var[""]}} </td>
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

