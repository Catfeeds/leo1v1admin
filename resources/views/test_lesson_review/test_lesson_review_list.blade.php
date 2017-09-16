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
                    <td>序号 </td>
                    <td>申请人 </td>
                    <td>学生手机 </td>
                    <td>组长 </td>
                    <td>组长审核状态 </td>
                    <td>主管 </td>
                    <td>主管审核状态 </td>
                    <td>申请说明 </td>
                    <td>申请时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["num"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["group_nick"]}} </td>
                        <td>{!! @$var["group_suc_flag_str"] !!} </td>
                        <td>{{@$var["master_nick"]}} </td>
                        <td>{!! @$var["master_suc_flag_str"] !!} </td>
                        <td>{{@$var["review_desc"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                @if(@$adminid  == 831)
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
