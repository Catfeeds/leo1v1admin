@extends('layouts.app')
@section('content')

    <section class="content ">

        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">分类 </span>
                    <select class="opt-change form-control" id="id_t_flag" >
                        <option value="1">T类</option>
                        <option value="2">未拨通</option>
                    </select>
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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <input class="opt-change form-control" id="id_phone" placeholder="电话" />
            </div>
        </div>


            <div class="col-xs-6 col-md-4">
                <button style="display:none;" class="btn" id="id_max_count" data-value="{{$max_day_count}}" ></button>
                <button class="btn" id="id_left_count" data-value="{{$left_count}}" ></button>
                <button class="btn btn-primary" id="id_count_detail"  > 明细</button>
                <button class="btn btn-primary" id="id_reload"  > 刷新</button>
                <button class="btn btn-warning" id="id_cur_user"  > 当前例子</button>
            </div>



        </div>



        <hr/>
        <table   class="table table-bordered table-striped"   >
            <thead>
                <tr>
                    <td class="td-add-time">时间 </td>
                    <td>电话</td>
                    <td style="display:none;" >地区</td>
                    <td  >等级</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>Pad</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td class="td-add-time" >{{@$var["add_time"]}} </td>
                        <td>{{@$var["phone_hide"]}} </td>
                        <td style="display:none;" >{{@$var["phone_location"]}} </td>
                        <td>{{@$var["origin_level_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["has_pad_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a   style="display:none;" class=" btn fa  opt-set-self" title="">抢学生 </a>

                                <a   class=" btn fa  fa-phone  opt-telphone" title="打电话"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (count($errors) > 0)
            <!-- Form Error List -->
            <div class="alert alert-danger" style="margin:20px;">
                <ul>
                    @foreach ($errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


    </section>

@endsection
