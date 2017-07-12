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



        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-4 ">
         <table class="common-table   ">
             <thead>
                 <tr>
                     <td >key1</td>
                     <td >key2</td>
                     <td >key3</td>
                     <td >渠道</td>
                     <td >人数</td>
                 </tr>
             </thead>
             <tbody>
                 @foreach ($origin_list as $var)
                     <tr class="{{$var["level"]}}">
                         <td data-class_name="{{$var["key1_class"]}}" class="key1" >{{$var["key1"]}}</td>
                         <td  data-class_name="{{$var["key2_class"]}}" class=" key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{$var["key2"]}}</td>
                         <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{$var["key3"]}}</td>
                         <td data-class_name="{{$var["key4_class"]}}" class="key4   {{$var["key3_class"]}} {{$var["key4_class"]}}"  >{{$var["key4"]}}</td>

                         <td >{{@$var["count"]}}</td>
                     </tr>
                 @endforeach
             </tbody>
         </table>




            </div>
            <div class="col-xs-2 ">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td style="max-width:100px;">省份 </td>
                            <td style="max-width:100px;">人数 </td>
                            <td style="max-width:100px;"> 占比 </td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ( $table_data_list as $var )
                            <tr>
                                <td>{{@$var["region"]}} </td>
                                <td>{{@$var["count"]}} </td>
                                <td>{{@$var["percent"]}}% </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <div class="col-xs-2 ">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td style="max-width:100px;">年级</td>
                            <td style="max-width:100px;">人数 </td>
                            <td style="max-width:100px;"> 占比 </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $grade_list as $var )
                            <tr>
                                <td>{{@$var["grade_str"]}} </td>
                                <td>{{@$var["count"]}} </td>
                                <td>{{@$var["percent"]}}% </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="col-xs-2 ">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td style="max-width:100px;">科目</td>
                            <td style="max-width:100px;">人数 </td>
                            <td style="max-width:100px;"> 占比 </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $subject_list as $var )
                            <tr>
                                <td>{{@$var["subject_str"]}} </td>
                                <td>{{@$var["count"]}} </td>
                                <td>{{@$var["percent"]}}% </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>


            <div class="col-xs-2 ">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td style="max-width:100px;">奥赛</td>
                            <td style="max-width:100px;"> 人/科目数 </td>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>常规</td>
                                <td>{{@$competition_flag_map[0]}} </td>
                            </tr>
                            <tr>
                                <td>奥赛</td>
                                <td>{{@$competition_flag_map[1]}} </td>
                            </tr>

                    </tbody>
                </table>
                <hr/>
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td style="max-width:100px;">转介绍</td>
                            <td style="max-width:100px;"> 人数 </td>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>转介绍  </td>
                                <td>{{$user_count- $no_origin_userid_count }} </td>
                            </tr>
                            <tr>
                                <td>非转介绍  </td>
                                <td>{{ $no_origin_userid_count }} </td>
                            </tr>

                    </tbody>
                </table>
                <hr/>
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td style="max-width:100px;">单人科目数</td>
                            <td style="max-width:100px;">人数 </td>
                            <td style="max-width:100px;"> 占比 </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $user_subject_count_list as $var )
                            <tr>
                                <td>{{@$var["subject_count"]}} </td>
                                <td>{{@$var["count"]}} </td>
                                <td>{{@$var["percent"]}}% </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>




        </div>

    </section>

@endsection
