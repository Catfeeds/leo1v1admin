@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >


                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <a class="btn btn-danger "  id="id_sync_tq" > 刷新tq </a>
                    </div>

                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <a class="btn btn-danger "  id="id_sync_ytx" > 刷新云通讯 </a>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <a class="btn btn-primary "  id="id_call_phone" > 拨打电话 </a>
                    </div>
                </div>





                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <a class="btn btn-primary "  id="id_edit" >编辑</a>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <button class="btn btn-primary" id="id_left_count"  title="可抢个数" > {{$count_info["left_count"]}}
                        </button>
                        <a class="btn btn-warning "  id="id_goto_new_list" > 继续抢新例子</a>
                        <a class="btn btn-warning "  id="id_get_new" style="display:none;" >抢新例子</a>
                    </div>
                </div>



            </div>
        </div>
        <hr/>
        @if ( $user_info )
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>项目</td>
                    <td>值</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>电话</td>
                    <td>{{$user_info["phone"] }}</td>
                </tr>


                <tr>
                    <td>拨打状态</td>
                    <td>{{$user_info["tq_called_flag_str"] }}
                        <a class="btn btn-primary "  id="id_get_this_new_user" >认领例子</a>
                        @if  ($user_info["tq_called_flag"] ==0 )
                            <font color="red"> 请拨打,并 刷新通话记录 </font>
                        @elseif ( $user_info["tq_called_flag"] ==1  )
                            <font color="blue"> 重新拨打,或 抢新例子 </font>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>销售负责人</td>
                    <td>
                        {{$user_info["admin_revisiter_nick"] }}

                        @if ($user_info["admin_revisiterid"] ==0 )
                            <font color="red"> 还不是你的!! </font>
                        @endif
                    </td>
                    </td>
                </tr>



                <tr>
                    <tr>
                        <td>年级 </td>
                        <td>{{$user_info["grade_str"] }}</td>
                    </tr>

                    <tr>
                        <td>科目 </td>
                        <td>{{$user_info["subject_str"] }}</td>
                    </tr>


                    <tr>
                        <td>设备</td>
                        <td>{{$user_info["has_pad_str"] }}</td>
                    </tr>

            </tbody>
        </table>
        @elseif( is_array( @$errors) )
        <div class="alert alert-danger" style="margin:20px;">
            <strong>未开放</strong>
            <br><br>

            <ul>
                @foreach ($errors as $error)
                    <li>{!!  $error !!}  </li>
                @endforeach
            </ul>
        </div>

        @else
        <div class="row  " >
            <br/>

            <br/>
            <br/>


            <br/>

            <br/>
            <br/>
            <br/>

            <br/>
            <br/>


            <div class="col-xs-12 col-md-12  " >
                <div  style="  text-align:center; color:#3c8dbc;font-size:30px" >请抢新例子 </div>
                    <div  style="  text-align:center;  color:#3c8dbc;font-size:30px" >READY GO ... </div>
                </div>
            </div>

        @endif
    </section>


    <div style="display:none;" id="id_dlg_post_user_info">
        <div class="row">
            <div class="col-xs-12 col-md-6  ">
                <div class="row">
                    <div class="col-xs-12 col-md-6  ">
                        <div class="input-group ">
                            <span class="input-group-addon">学员姓名：</span>
                            <input type="text" class=" form-control "  id="id_stu_nick"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">家长姓名：</span>
                            <input type="text" class=" form-control "  id="id_par_nick"  />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">学生性别：</span>
                            <select id="id_stu_gender" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">是否有pad：</span>
                            <select id="id_stu_has_pad" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon">家庭住址：</span>
                            <input type="text" id="id_stu_addr"  class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">学生年级：</span>
                            <select id="id_stu_grade" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">　　科目：</span>
                            <select id="id_stu_subject" class=" form-control "   >
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row ">

                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">在读学校：</span>
                            <input type="text" id="id_stu_school"  class="form-control"  />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">教材版本：</span>
                            <select id="id_stu_editionid" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>

                <div class="row ">

                    <div class="col-xs-12 col-md-7 ">
                        <div class="input-group ">
                            <span class="input-group-addon">回访状态：</span>
                            <select id="id_stu_status" class=" form-control "   >
                            </select>
                            <span> &gt </span>
                            <select id="id_seller_student_sub_status" class=" form-control "   >
                            </select>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5 ">
                        <a class="btn  " id="id_stu_rev_info" >回访记录</a>
                        <a class="btn  btn-primary " id="id_send_sms" >发短信给家长</a>
                    </div>
                </div>
                <div class="row ">

                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">下次回访：</span>
                            <input id="id_next_revisit_time" class=" form-control " />

                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_next_revisit_time"  title="取消下次回访">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span >成绩情况:</span>
                            <input type="text" value=""   id="id_stu_score_info"  class="form-control" placeholder="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="input-group ">
                            <span >性格特点:</span>
                            <input type="text" value=""   id="id_stu_character_info" class="form-control"  placeholder="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6  ">
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听内容：</span>
                            <select id="id_stu_test_lesson_level" class=" form-control "   >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon"> 连线测试 ：</span>
                            <select id="id_stu_test_ipad_flag" class=" form-control "   >
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听时间：</span>
                            <input id="id_stu_request_test_lesson_time" class=" form-control "   />
                            <div class=" input-group-btn "  >
                                <button class="btn  btn-primary " id="id_stu_reset_stu_request_test_lesson_time"  title="取消">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 ">
                        <button class="btn  btn-primary " id="id_stu_request_test_lesson_time_info"  title=""> 试听其他时段 </button>
                        <button class="btn  btn-primary " id="id_stu_request_lesson_time_info"  title=""> 正式课时段 </button>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon">试听需求：</span>
                            <textarea class="form-control" style="height:60px;"
                                      id="id_stu_request_test_lesson_demand" > </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >　　备注：</span>
                            <textarea class="form-control" style="height:70px;" id="id_stu_user_desc" > </textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 ">
                        <div class="input-group ">
                            <span class="input-group-addon" >回访信息：</span>
                            <textarea class="form-control" style="height:130px;" id="id_stu_revisite_info"  > </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
