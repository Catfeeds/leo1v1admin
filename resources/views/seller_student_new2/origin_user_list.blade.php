@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


             <div class="col-xs-6 col-md-4">
                 <div class="input-group ">
                     <span class="input-group-addon">渠道选择</span>
                     <input class="opt-change form-control" id="id_origin_ex" />
                 </div>
             </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">渠道</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>


        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">学生</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>进入时间 </td>
                    <td>学生 电话</td>
                    <td> 渠道</td>
                    <td> 当前渠道 </td>
                    <td> 是否一致</td>
                    <td> 科目 </td>
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["origin"]}} </td>
                        <td>{{@$var["cur_origin"]}} </td>
                        <td>{!! @$var["origin_same_flag_str"] !!} </td>
                        <td>{{@$var["subject_str"]}} </td>
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
