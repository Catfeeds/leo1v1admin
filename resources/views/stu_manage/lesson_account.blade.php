@extends('layouts.stu_header')
@section('content')

    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/set_lesson_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>

    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>

    <section class="content">

        <div class="row">
            <div class="col-xs-6 col-md-6">
                <div class="input-group">
                    <span >课时包</span>
                    <select class="stu_sel form-control" id="id_lesson_account_id" >

                        @foreach ($lesson_account_list as $var)
                            <option value="{{$var["lesson_account_id"]}}"> 剩余{{$var["left_lesson_count"]}}课时-{{$var["add_time"]}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-btn ">
                        <button  id="id_set_lesson_account" type="button"  class="btn btn-warning" >查看&修改</button> 
                        <button  id="id_show_log_lesson_account" type="button"  class="btn  btn-primary" >查看日志</button> 
                    </div>
                </div>

            </div>

            <div class="col-xs-6 col-md-4">
                <div class="input-group">
                    <div class="input-group-btn ">
                        <button  id="id_add_lesson" type="button" class="btn btn-danger " >排课 </button> 
                    </div>
                </div>

            </div>


        </div>
        <hr/> 

        <table   class="common-table "   >

            <thead>
                <tr>
                    <td class="" >课次</td>
                    <td class="" >课程id</td>
                    <td class="" >课程时间</td>
                    <td class="" >老师</td>
                    <td  >助教</td>
                    <td  >课程状态</td>
                    <td   >初始课时</td>
                    <td class="" >最终课时</td>
                    <td class="remove-for-xs" >课时调整说明</td>
                    <td class="remove-for-xs" >操作</td>
                </tr>
            </thead>
            <tbody>
                    
                @foreach ($table_data_list as $var)
                    <tr>
                        <td  >{{$var["lesson_num"]}}</td>
                        <td  >{{$var["lessonid"]}}</td>
                        <td  >{{$var["lesson_time"]}}</td>
                        <td >{{$var["teacher_nick"]}}</td>
                        <td>{{$var["assistant_nick"]}}</td>
                        <td>{{$var["lesson_status_str"]}}</td>
                        <td> {{$var["lesson_count"]}} </td>
                        <td> {{$var["real_lesson_count"]}} </td>
                        <td class="remove-for-xs" >{{$var["reason"]}}</td>
                        <td class= "remove-for-xs" >
                            <div   data-lessonid="{{$var["lessonid"]}}"
                                   data-lesson_time="{{$var["lesson_time"]}}"
                                   data-price="{{$var["price"]}}"
                                   data-real_lesson_count="{{$var["real_lesson_count"]}}"
                                   data-lesson_count="{{$var["lesson_count"]}}"

                            >
                                <a href="javascript:;" class="btn  fa fa-clock-o  opt-set-time" title="修改课程时间"></a>
                                <a href="javascript:;" class="btn  fa fa-edit opt-change-money " title="修改金额"></a>
                                
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
	    <include: file="../al_common/page.html"/>
	    <include: file="../al_common/return_record_add.html"/>

        <div class="dlg_set_dynamic_passwd" style="display:none">
            <div class="row ">
                <div class="input-group">
                    <label class="stu_nick"> </label>
                    <label class="stu_phone"> </label>
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">请输入临时密码</span>
                    <input type="text" class="dynamic_passwd" />
                </div>
            </div>
        </div>

@endsection

