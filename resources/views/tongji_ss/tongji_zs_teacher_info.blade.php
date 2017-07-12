@extends('layouts.app')
@section('content')
    
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <section class="content ">
        
        <div>
            <div class="row" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span>老师月接试听课数</span>
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="address" id="id_month_test_lesson_num"   />
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span>本月预计试听课数</span>
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="address" id="id_except_test_lesson_num"   />
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="table table-bordered"  > 
            <caption>{{$last_month}}月份年级科目占比详情</caption>
            <thead>
                <tr>
                    <td>{{$last_month}}月份 </td>
                    <td>语文</td>
                    <td>数学</td>
                    <td>英语</td>
                    <td>化学</td>
                    <td>物理</td>
                </tr>
            </thead>
            <tbody>               
                    <tr>
                        <td>小学 </td>
                        <td>{{@$subject_grade_arr["100-1"]}} </td>
                        <td>{{@$subject_grade_arr["100-2"]}} </td>
                        <td>{{@$subject_grade_arr["100-3"]}}</td>
                        <td>{{@$subject_grade_arr["100-4"]}} </td>
                        <td>{{@$subject_grade_arr["100-5"]}} </td>
                    </tr>
                    <tr>
                        <td>年级占比 </td>
                        <td>{{@$subject_grade_arr["xxyw_per"]}}% </td>
                        <td>{{@$subject_grade_arr["xxsx_per"]}}% </td>
                        <td>{{@$subject_grade_arr["xxyy_per"]}}% </td>
                        <td>{{@$subject_grade_arr["xxhx_per"]}}% </td>
                        <td>{{@$subject_grade_arr["xxwl_per"]}}% </td>
                    </tr>

                    <tr>
                        <td>初中 </td>
                        <td>{{@$subject_grade_arr["200-1"]}} </td>
                        <td>{{@$subject_grade_arr["200-2"]}} </td>
                        <td>{{@$subject_grade_arr["200-3"]}}</td>
                        <td>{{@$subject_grade_arr["200-4"]}} </td>
                        <td>{{@$subject_grade_arr["200-5"]}} </td>
                    </tr>
                    <tr>
                        <td>年级占比 </td>
                        <td>{{@$subject_grade_arr["czyw_per"]}}% </td>
                        <td>{{@$subject_grade_arr["czsx_per"]}}% </td>
                        <td>{{@$subject_grade_arr["czyy_per"]}}% </td>
                        <td>{{@$subject_grade_arr["czhx_per"]}}% </td>
                        <td>{{@$subject_grade_arr["czwl_per"]}}% </td>
                    </tr>

                    <tr>
                        <td>高中 </td>
                        <td>{{@$subject_grade_arr["300-1"]}} </td>
                        <td>{{@$subject_grade_arr["300-2"]}} </td>
                        <td>{{@$subject_grade_arr["300-3"]}}</td>
                        <td>{{@$subject_grade_arr["300-4"]}} </td>
                        <td>{{@$subject_grade_arr["300-5"]}} </td>
                    </tr>
                    <tr>
                        <td>年级占比 </td>
                        <td>{{@$subject_grade_arr["gzyw_per"]}}% </td>
                        <td>{{@$subject_grade_arr["gzsx_per"]}}% </td>
                        <td>{{@$subject_grade_arr["gzyy_per"]}}% </td>
                        <td>{{@$subject_grade_arr["gzhx_per"]}}% </td>
                        <td>{{@$subject_grade_arr["gzwl_per"]}}% </td>
                    </tr>
                    <tr>
                        <td>合计 </td>
                        <td>{{@$subject_grade_arr["1"]}} </td>
                        <td>{{@$subject_grade_arr["2"]}} </td>
                        <td>{{@$subject_grade_arr["3"]}}</td>
                        <td>{{@$subject_grade_arr["4"]}} </td>
                        <td>{{@$subject_grade_arr["5"]}} </td>
                    </tr>
                    <tr>
                        <td>科目占比 </td>
                        <td>{{@$subject_grade_arr["yw_per"]}}% </td>
                        <td>{{@$subject_grade_arr["sx_per"]}}% </td>
                        <td>{{@$subject_grade_arr["yy_per"]}}% </td>
                        <td>{{@$subject_grade_arr["hx_per"]}}% </td>
                        <td>{{@$subject_grade_arr["wl_per"]}}% </td>
                    </tr>




            </tbody>
        </table>
        <br>
        <br>
        <table     class="table table-bordered"  >
            <caption>招师详情</caption>
            <tr>
                <td>学科</td>
                <td>年级段</td>
                <td>{{@$month}}月预计</td>
                <td>{{@$last_month}}月招师</td>
                <td>差额</td>
            </tr>
            <tr>
                <td rowSpan="3">数学</td>
                <td>小学</td>
                <td>{{@$month_except_detail_info[2][100]}}</td>
                <td>{{@$lecture_subject_grade[2][100]}}</td>
                <td>{{@$lecture_subject_grade[2][100]-@$month_except_detail_info[2][100]}}</td>
            </tr>
            <tr>
                <td>初中</td>
                <td>{{@$month_except_detail_info[2][200]}}</td>
                <td>{{@$lecture_subject_grade[2][200]}}</td>
                <td>{{@$lecture_subject_grade[2][200]-@$month_except_detail_info[2][200]}}</td>
            </tr>
            <tr>
                <td>高中</td>
                <td>{{@$month_except_detail_info[2][300]}}</td>
                <td>{{@$lecture_subject_grade[2][300]}}</td>
                <td>{{@$lecture_subject_grade[2][300]-@$month_except_detail_info[2][300]}}</td>
            </tr>            <tr>
                <td rowSpan="3">语文</td>
                <td>小学</td>
                <td>{{@$month_except_detail_info[1][100]}}</td>
                <td>{{@$lecture_subject_grade[1][100]}}</td>
                <td>{{@$lecture_subject_grade[1][100]-@$month_except_detail_info[1][100]}}</td>
            </tr>
            <tr>
                <td>初中</td>
                <td>{{@$month_except_detail_info[1][200]}}</td>
                <td>{{@$lecture_subject_grade[1][200]}}</td>
                <td>{{@$lecture_subject_grade[1][200]-@$month_except_detail_info[1][200]}}</td>
            </tr>
            <tr>
                <td>高中</td>
                <td>{{@$month_except_detail_info[1][300]}}</td>
                <td>{{@$lecture_subject_grade[1][300]}}</td>
                <td>{{@$lecture_subject_grade[1][300]-@$month_except_detail_info[1][300]}}</td>
            </tr>
            <tr>
                <td rowSpan="3">英语</td>
                <td>小学</td>
                <td>{{@$month_except_detail_info[3][100]}}</td>
                <td>{{@$lecture_subject_grade[3][100]}}</td>
                <td>{{@$lecture_subject_grade[3][100]-@$month_except_detail_info[3][100]}}</td>
            </tr>
            <tr>
                <td>初中</td>
                <td>{{@$month_except_detail_info[3][200]}}</td>
                <td>{{@$lecture_subject_grade[3][200]}}</td>
                <td>{{@$lecture_subject_grade[3][200]-@$month_except_detail_info[3][200]}}</td>
            </tr>
            <tr>
                <td>高中</td>
                <td>{{@$month_except_detail_info[3][300]}}</td>
                <td>{{@$lecture_subject_grade[3][300]}}</td>
                <td>{{@$lecture_subject_grade[3][300]-@$month_except_detail_info[3][300]}}</td>
            </tr>
            <tr>
                <td rowSpan="3">化学</td>
                <td>小学</td>
                <td>{{@$month_except_detail_info[4][100]}}</td>
                <td>{{@$lecture_subject_grade[4][100]}}</td>
                <td>{{@$lecture_subject_grade[4][100]-@$month_except_detail_info[4][100]}}</td>
            </tr>
            <tr>
                <td>初中</td>
                <td>{{@$month_except_detail_info[4][200]}}</td>
                <td>{{@$lecture_subject_grade[4][200]}}</td>
                <td>{{@$lecture_subject_grade[4][200]-@$month_except_detail_info[4][200]}}</td>
            </tr>
            <tr>
                <td>高中</td>
                <td>{{@$month_except_detail_info[4][300]}}</td>
                <td>{{@$lecture_subject_grade[4][300]}}</td>
                <td>{{@$lecture_subject_grade[4][300]-@$month_except_detail_info[4][300]}}</td>
            </tr>
            <tr>
                <td rowSpan="3">物理</td>
                <td>小学</td>
                <td>{{@$month_except_detail_info[5][100]}}</td>
                <td>{{@$lecture_subject_grade[5][100]}}</td>
                <td>{{@$lecture_subject_grade[5][100]-@$month_except_detail_info[5][100]}}</td>
            </tr>
            <tr>
                <td>初中</td>
                <td>{{@$month_except_detail_info[5][200]}}</td>
                <td>{{@$lecture_subject_grade[5][200]}}</td>
                <td>{{@$lecture_subject_grade[5][200]-@$month_except_detail_info[5][200]}}</td>
            </tr>
            <tr>
                <td>高中</td>
                <td>{{@$month_except_detail_info[5][300]}}</td>
                <td>{{@$lecture_subject_grade[5][300]}}</td>
                <td>{{@$lecture_subject_grade[5][300]-@$month_except_detail_info[5][300]}}</td>
            </tr>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

