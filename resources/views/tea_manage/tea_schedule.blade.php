@extends('layouts.app')
@section('content')
    <script type="text/javascript" >
    $(function(){
		$("#id_is_part_time").val("[$is_part_time]");
		$("#id_week_shift").data("week_shift","[$week_shift]");
		$("#id_teacher_list").val("[$tea_nick]");
		if ( "[$tea_nick]" != "" ){
			$("#id_tea_name").val("[$tea_nick]");
			$("#id_tea_name_title").hide();
			$("#id_tea_name").show();
		}

		//tab栏
		tab('.nav_tit .teach_mate','.teach_mate','.stu_tab11 td','.teacherTime',0);
		
		//按钮
		btn_s('.stu_data','.mesg_alert12');//点击单个学生查看详情
	});
    </script>
   
    <div class="right" >
    	<!-- <p class="nav_tit"><a href="#" class="teach_mate">教师课表</a><a href="#" class="teach_mate">教师课表</a></p> -->
        <div class="cont_box teacherTime">
            <div class="teach_cont row">
                <div class="col-xs-8 col-md-2">
                    <div class="input-group">
                        <span class="input-group-addon">兼职|全职</span>
                        <select class="will_change" id="id_is_part_time">
                            <option value="-1">不限</option>
                            <option value="0">仅全职</option>
                            <option value="1">仅兼职</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师</span>
                        <input id="id_teacherid" class="will_change"  /> 
                    </div>
                </div>
            </div>
            <hr>

            <div class="cont teacherTime_cont">
                <table   class="table table-bordered table-striped"   >
                    <thead>
                        <tr>
                            <td width="8%"></td>
                            <td width="10%">一({{$var["0"]}})</td>
                            <td width="10%">二({{$var["1"]}})</td>
                            <td width="10%">三({{$var["2"]}})</td>
                            <td width="10%">四({{$var["3"]}})</td>
                            <td width="10%">五({{$var["4"]}})</td>
                            <td width="10%">六({{$var["5"]}})</td>
                            <td width="10%">七({{$var["6"]}})</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table_data_list as $var)
                        <tr>
                        	<td>{{$var["tea_nick"]}}</td>
                        	<td>
                            	<ul>
                            	<ul>
									{{$var["1"]}}
                                </ul>
                            </td>
                            <td>
                            	<ul>
									{{$var["2"]}}
                                </ul>
                            </td>
                            <td>
                            	<ul>
									{{$var["3"]}}
                                </ul>
                            </td>
                            <td>
                            	<ul>
									{{$var["4"]}}
                                </ul>
                            </td>
                            <td>
                            	<ul>
									{{$var["5"]}}
                                </ul>
                            </td>
                            <td>
                            	<ul>
									{{$var["6"]}}
                                </ul>
                            </td>
                            <td>
                            	<ul>
									{{$var["7"]}}
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include("layouts.page")



        <div class="mesg_alert mesg_alert12" style="display:none">
	        <h6>课程详情<a href="javascript:;" class="closed"></a></h6>
            <div class="mesg_alertCont">
    	        <ul class="list05">
                    <li>学员姓名：<span id="id_student_name"></span></li>
                    <li>课程时间：<span id="id_lesson_interval"></span></li>
                    <li>课程课次：<span id="id_lesson_num"></span></li>
                    <li>课程类型：<span id="id_lesson_type"></span></li>
                    <li>授课内容：<span id="id_lesson_intro"></span></li>
                    <li><input type="button" value="确认" class="blue_btn" id="id_close_alert12"/></li>
                </ul>
            </div>
        </div>

@endsection
