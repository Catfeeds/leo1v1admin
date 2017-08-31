@extends('layouts.app')
@section('content')
    <section class="content ">

        <div class="row">

            <div class="col-xs-12 col-md-5"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>

            <div class="col-xs-6 col-md-3" data-always_show="1"   >
                <div class="input-group ">
                    <input class="opt-change form-control" style="display:none;" id="id_phone_name" placeholder="电话,姓名,回车搜索"/>
                </div>
            </div>


            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <select class="opt-change form-control" id="id_grade" >
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">Pad</span>
                    <select class="opt-change form-control" id="id_has_pad" >
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">科目</span>
                    <select class="opt-change form-control" id="id_subject" >
                    </select>
                </div>
            </div>


        </div>

        <hr/>
        <table   class="table table-bordered table-striped"   >
            <thead>
                <tr>
                    <td>时间 </td>
                    <td>电话</td>
                    <td>地区</td>
                    <!-- <td>回流人</td> -->
                    <!-- <td>回流时间</td> -->
                    <td>年级</td>
                    <td>科目</td>
                    <td>Pad</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["phone_hide"]}} </td>
                        <td>{{@$var["phone_location"]}} </td>
                        <!-- <td>{{@$var["cc_nick"]}} </td> -->
                        <!-- <td>{{@$var["free_time"]}} </td> -->
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["has_pad_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                                <a title="手机拨打" class=" btn fa fa-phone  opt-telphone   "></a>
                                <a   class=" btn fa  opt-set-self" title="">抢学生 </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

@endsection
