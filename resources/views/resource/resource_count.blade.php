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

                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" data-always_show="1">
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
                    <td>学科</td>
                    <td>教研员</td>
                    <td>资料类型</td>
                    <td>上传文件数</td>
                    <td>浏览量</td>
                    <td>浏览率</td>
                    <td>浏览次数</td>
                    <td>使用量</td>
                    <td>使用率</td>
                    <td>使用次数</td>
                    <td>收藏量</td>
                    <td>收藏率</td>
                    <td>收藏次数</td>
                    <td>个人分值</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="mark" data-mark="{{$var["mark"]}}" key1="{{$var["subject"]}}" key2="{{$var["subject"]}}_{{$var["adminid"]}}">
                        <td class="key1" data-key1="{{$var["subject"]}}">{{@$var["subject_str"]}}</td>
                        <td class="key2" data-key2="{{$var["subject"]}}_{{$var["adminid"]}}">{{@$var["nick"]}} </td>
                        <td>{{@$var["resource_type_str"]}} </td>
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
                <tr>
                    <td>合计</td>
                    <td></td>
                    <td></td>
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
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
