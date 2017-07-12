@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">销售</span>
                        <input class="opt-change form-control" id="id_admin_revisiterid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">TQ</span>
                        <select class="opt-change form-control" id="id_global_tq_called_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="opt-change form-control" id="id_seller_student_status" >
                        </select>
                    </div>
                </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">条数</span>
                <select class="opt-change form-control" id="id_page_count" >
                    <option value="10" >10 </option>
                    <option value="50" >50 </option>
                    <option value="100" >100 </option>
                    <option value="200" >200 </option>
                </select>

            </div>
        </div>




            </div>
            <div class="row  " >
                <div class="col-xs-3 col-md-1">
                    <button class="btn btn-primary" id="id_set_select_list">批量回新例子</button>
                </div>
            </div>


        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>

                    <td>销售</td>
                    <td>销售等级</td>
                    <td>学生电话</td>
                    <td>tq状态</td>
                    <td>状态</td>
                    <td>说明</td>
                    <td>分配时间</td>
                    <td>进入时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> <input type="checkbox" class="opt-select-item" data-userid="{{$var["userid"]}}"/>    </td>
                        <td>{{$var["account"]}} </td>
                        <td>{{$var["seller_level_str"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["global_tq_called_flag_str"]}} </td>
                        <td>{{$var["seller_student_status_str"]}} </td>
                        <td>{{$var["user_desc"]}} </td>
                        <td>{{$var["admin_assign_time"]}} </td>
                        <td>{{$var["add_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection


