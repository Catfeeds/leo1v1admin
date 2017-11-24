@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <input type="text" value="" class=" form-control click_on put_name for_input"  data-field="user_name" id="id_user_name"  placeholder="请按姓名,岗位,部门   回车查找" />
                    </div>
                </div>
                <div class="col-md-1 col-xs-5 ">
                    <div class="input-group input-group-btn">
                        <button type="button" class="btn btn-warning id_add form-control">新增面试信息</button>

                    </div>
                </div>

                <div class="col-md-1 col-xs-5 " style="display:none">
                    <div class="input-group input-group-btn">
                        <button type="button" class="btn btn-warning id_test form-control">测试</button>

                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> 姓名 </td>
                    <td> 面试岗位  </td>
                    <td> 面试部门  </td>
                    <td> 面试时间  </td>
                    <td> 面试官 </td>
                    <td> 是否发送提醒 </td>
                    <td> 发送时间 </td>
                    <td>  操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["name"]}} </td>
                        <td>{{@$var["post"]}} </td>
                        <td>{{@$var["dept"]}} </td>
                        <td>{{@$var["interview_time"]}} </td>
                        <td>{{@$var["interviewer_name"]}} </td>
                        <td>{!!@$var["is_send_flag_str"]!!} </td>
                        <td>{{@$var["send_msg_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit" title="编辑"> </a>
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
