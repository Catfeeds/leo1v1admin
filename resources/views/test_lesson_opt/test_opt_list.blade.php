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
                    <td>roomid </td>
                    <td>lessonid </td>
                    <td>用户名 </td>
                    <td>角色 </td>
                    <td>操作类型 </td>
                    <td>动作类型 </td>
                    <td>操作ip </td>
                    <td>课堂类型 </td>
                    <td> 操作时间  </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["roomid"]}} </td>
                        <td>{{@$var["lessonid"]}} </td>
                        <td>{{@$var["student_nick"]}} </td>
                        <td>{{@$var["role_str"]}} </td>
                        <td>{{@$var["opt_type_str"]}} </td>
                        <td>{{@$var["action_str"]}} </td>
                        <td>{{@$var["server_ip"]}} </td>
                        <td>{{@$var["class_type"]}} </td>
                        <td>{{@$var["opt_time"]}} </td>
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

