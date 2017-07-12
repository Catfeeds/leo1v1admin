@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    {!!\App\Helper\Utils::th_order_gen([
                        ["销售","st_application_nick" ],
                        ["排课总次数","all_count" ],
                        ["有效课程","succ_count" ],
                        ["取消次数","bad_count" ],
                        [" 取消次数(不算老师工资)","before_4_bad_count" ],
                        [" 取消次数(算老师工资)","after_4_bak_count" ],
                       ])  !!}
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["st_application_nick"]}} </td>
                        <td>{{@$var["all_count"]}} </td>
                        <td>{{@$var["succ_count"]}} </td>
                        <td>{{@$var["bad_count"]}} </td>
                        <td>{{@$var["before_4_bad_count"]}} </td>
                        <td>{{@$var["after_4_bak_count"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a style="display:none;" class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a style="display:none;" class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

