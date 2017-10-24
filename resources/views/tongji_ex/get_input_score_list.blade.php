@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">微信端</span>
                        <select class="opt-change form-control" id="id_admin_type" >
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                </div>


                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>学生 </td>
                    <td>录入时间 </td>
                    <td>录入人/类别</td>
                    <td>后台入口</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td><a href="http://admin.yb1v1.com/stu_manage/score_list?sid={{$var['userid']}}">{{@$var["nick"]}}</a> </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["create_nick"]}}/{{@$var["account_type"]}} </td>
                        <td>{{@$var['admin_type_str']}}</td>
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
