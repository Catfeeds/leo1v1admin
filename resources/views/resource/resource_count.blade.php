@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <!-- <div class="row  row-query-list" > -->
            <div class="row" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">总分类</span>
                        <select class="opt-change form-control" id="id_type" >
                            <option value="1">分项统计</option>
                            <option value="2">按姓名统计</option>
                            <option value="3">按科目统计</option>
                            <option value="4">按年级统计</option>
                            <option value="5">按资源类型统计</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2 hide_class">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2 hide_class" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 hide_class" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 hide_class" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">资料类型</span>
                        <select class="opt-change form-control" id="id_resource_type" >
                        </select>
                    </div>
                </div>

                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group " >
                     <span >xx</span>
                     <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                     </div>
                     </div> -->
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    @if($type == 1)
                        <td>学科</td>
                        <td>教研员</td>
                        <td>资料类型</td>
                    @endif
                    @if($type == 2 )
                    <td>教研员</td>
                    @endif
                    @if($type == 3)
                    <td>学科</td>
                    @endif
                    @if($type == 4)
                    <td>年级</td>
                    @endif
                    @if($type == 5)
                    <td>资料类型</td>
                    @endif
                      {!!\App\Helper\Utils::th_order_gen([
                        ["上传文件数","file_num" ],
                        ["浏览量","visit" ],
                        ["浏览率","visit_rate" ],
                        ["浏览次数","visit_num"],
                        ["使用量","use"],
                        ["使用率","use_rate"],
                        ["使用次数","use_num"],
                        ["收藏量","error"],
                        ["收藏率","error_rate"],
                        ["收藏次数","error_num"],
                        ["个人分值","score"],
                       ])  !!}
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        @if($type == 1)
                        <td>{{@$var["subject_str"]}}</td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["resource_type_str"]}} </td>
                        @endif
                        @if($type == 2)
                        <td>{{@$var['adminid']}}</td>
                        @endif
                        @if($type == 3)
                        <td>{{@$var['subject']}}</td>
                        @endif
                        @if($type == 4)
                        <td>{{@$var['grade']}}</td>
                        @endif
                        @if($type == 5)
                        <td>{{@$var['resource_type']}}</td>
                        @endif
                       
                       

                        <td>{{@$var["file_num"]}} </td>
                        <td>{{@$var["visit"]}} </td>
                        <td>{{@$var["visit_rate"]}} </td>
                        <td>{{@$var["visit_num"]}} </td>
                        <td>{{@$var["use"]}} </td>
                        <td>{{@$var["use_rate"]}} </td>
                        <td>{{@$var["use_num"]}} </td>
                        <td>{{@$var["error"]}} </td>
                        <td>{{@$var["error_rate"]}} </td>
                        <td>{{@$var["error_num"]}} </td>
                        <td>{{@$var["score"]}} </td>
                    </tr>
                @endforeach
                @if ($type > 1)
                <tr>
                    <td>合计</td>
                    <td>{{@$total["file_num"]}}</td>
                    <td>{{@$total["visit"]}}</td>
                    <td>{{@$total["visit_rate"]}}</td>
                    <td>{{@$total["visit_num"]}}</td>
                    <td>{{@$total["use"]}}</td>
                    <td>{{@$total["use_rate"]}}</td>
                    <td>{{@$total["use_num"]}}</td>
                    <td>{{@$total["error"]}}</td>
                    <td>{{@$total["error_rate"]}}</td>
                    <td>{{@$total["error_num"]}}</td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
