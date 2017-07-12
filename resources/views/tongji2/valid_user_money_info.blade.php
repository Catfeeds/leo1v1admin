@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>



                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">地区</span>
                        <input class="opt-change form-control" id="id_phone_location" />
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
                        <span class="input-group-addon">转介绍</span>
                        <select class="opt-change form-control" id="id_origin_from_user_flag" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">奥赛</span>
                        <select class="opt-change form-control" id="id_competition_flag" >
                        </select>
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
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-md-2"  >
                    <div class="input-group ">
                        <span class="input-group-addon" style="color:red;" >统计项</span>
                        <select class="opt-change form-control" id="id_check_field_id" >
                            <option value="1">年级</option>
                            <option value="2">科目</option>
                            <option value="3">地区</option>
                            <option value="4">渠道</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6"  data-title="时间段">

                    <div  id="id_date_range_1" >
                    </div>
                </div>
            </div>


        </div>
        <hr/>
        @if ($field_name !="origin")
            <table     class="common-table"  >
                <thead>
                    <tr>
                        <td >统计项</td>
                        <!-- <td>平均消耗课时</td> -->
                        <td>续费单笔</td>
                        <td>续费次数</td>
                        <td>续费人数</td>
                        <td>转介绍人数</td>
                        <td>转介绍成功人数</td>
                    </tr>
                </thead>
                <tbody>

                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["title"]}} </td>
                            <!-- <td>{{@$var["avg_lesson_count"]}} </td> -->
                            <td>{{@$var["contract_type_3_avg_money"]}} </td>
                            <td>{{@$var["contract_type_3_count"]}} </td>
                            <td>{{@$var["contract_type_3_user_count"]}} </td>
                            <td>{{@$var["origin_user_count"]}} </td>
                            <td>{{@$var["succ_origin_user_count"]}} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else


         <table class="common-table   ">
             <thead>
                 <tr>
                     <td >key1</td>
                     <td >key2</td>
                     <td >key3</td>
                     <td >渠道</td>
                     <!-- <td>平均消耗课时</td> -->
                        <td>续费单笔</td>
                        <td>续费次数</td>
                        <td>续费人数</td>
                        <td>转介绍人数</td>
                        <td>转介绍成功人数</td>
                 </tr>
             </thead>
             <tbody>
                 @foreach ($table_data_list as $var)
                     <tr class="{{$var["level"]}}">
                         <td data-class_name="{{$var["key1_class"]}}" class="key1" >{{$var["key1"]}}</td>
                         <td  data-class_name="{{$var["key2_class"]}}" class=" key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{$var["key2"]}}</td>
                         <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{$var["key3"]}}</td>
                         <td data-class_name="{{$var["key4_class"]}}" class="key4   {{$var["key3_class"]}} {{$var["key4_class"]}}"  >{{$var["key4"]}}</td>

                         <!-- <td>{{@$var["avg_lesson_count"]}} </td> -->
                            <td>{{@$var["contract_type_3_avg_money"]}} </td>
                            <td>{{@$var["contract_type_3_count"]}} </td>
                            <td>{{@$var["contract_type_3_user_count"]}} </td>
                            <td>{{@$var["origin_user_count"]}} </td>
                            <td>{{@$var["succ_origin_user_count"]}} </td>


                     </tr>
                 @endforeach
             </tbody>
         </table>



        @endif

        </div>

    </section>

@endsection

