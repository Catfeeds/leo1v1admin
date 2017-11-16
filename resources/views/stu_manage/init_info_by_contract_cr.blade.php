@extends('layouts.stu_header')
@section('content')
    <script>
       g_data_ex_list = {!!  json_encode($init_data )  !!} ;
    </script>

    <style>

     #id_student  .field-name {
         background-color: #d9edf7;
         color:#31708f;
         border-color: #bce8f1;

     }

     #id_student  input, #id_student  select , #id_student  textarea{
         border-color: #bce8f1;
     }

     #id_parent  .field-name {
         color: #3c763d;
         background-color: #dff0d8;
         border-color: #d6e9c6;

     }

     #id_parent input, #id_parent  select , #id_parent  textarea{
         border-color: #d6e9c6;
     }

     #id_subject  .field-name {

         background-color: #fcf8e3;
         border-color: #faebcc;
         color: #8a6d3b;
     }

     #id_subject input, #id_subject  select , #id_subject  textarea{
         border-color: #faebcc;
     }

     #id_require  .field-name {
         color: #a94442;
         background-color: #f2dede;
         border-color: #ebccd1;
     }

     #id_require input, #id_require  select , #id_require  textarea{
         border-color: #ebccd1;
     }





    </style>
    <section class="content " id="id_content"  style="max-width:1200px ;">

        <div class="row">
            <div class="col-xs-12 col-md-6   ">
                <div class="col-xs-12     ">
                    <div class="panel panel-info"  id="id_student">
                        <div class="panel-heading">
                            学生信息
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　姓名:</span>
                                        <input class="form-control" id="id_real_name"  />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　性别:</span>
                                        <select class="form-control" id="id_gender"  >    </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　年级:</span>
                                        <select class="form-control" id="id_grade" >    </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　生日:</span>
                                        <input class="form-control" id="id_birth"  />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　学校:</span>
                                        <input class="form-control" id="id_school" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >性格特点:</span>
                                        <input class="form-control"  id="id_xingetedian" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　爱好:</span>
                                        <input class="form-control"  id="id_aihao"   />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >业余安排:</span>
                                        <input class="form-control"  id="id_yeyuanpai"   />
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xs-12   ">

                    <div class="panel panel-warning"  id="id_subject">
                        <div class="panel-heading">
                            学科信息
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >英语成绩:</span>
                                        <input class="form-control" value="" id="id_subject_yingyu" placeholder="例子: 90/150"   />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >语文成绩:</span>
                                        <input class="form-control" value="" id="id_subject_yuwen"  />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >数学成绩:</span>
                                        <input class="form-control" value="" id="id_subject_shuxue"  />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >物理成绩:</span>
                                        <input class="form-control" value="" id="id_subject_wuli" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >化学成绩:</span>
                                        <input class="form-control" value="" id="id_subject_huaxue" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >班级排名:</span>
                                        <input class="form-control" value="" id="id_class_top" placeholder="例子: 9/30"  />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >年级排名:</span>
                                        <input class="form-control" value="" id="id_grade_top" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >学科情况:</span>
                                        <textarea class="form-control" value="" id="id_subject_info" placeholder  ="可以从学生性格，学习态度，辅导预期三方面说明；参考例子：家长比较重视孩子学习,对老师各方面了解的也比较多,助教需要多和 家长互动,对孩子多关心些。" style="height:120px" >  </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >报课情况:</span>
                                        <textarea class="form-control" id="id_order_info"  style="height:120px" placeholder="说明报名几次课，支付多少钱，优惠情况；参考例子：5次课，支付1125元，9.9折">  </textarea>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


                <div class="submit_all row">
                    <input class="form-control" style="display:none;" value="" id="id_id" />

                    <div class="col-xs-12 " style="text-align:center;" >
                        <button  class="id_submit  btn btn-warning" style=" font-size:20px; " >驳回咨询</button>

                        <button  class="id_submit_succ btn btn-success" style=" font-size:20px;" >已驳回</button>

                        <button  class="id_reject_to_ass btn btn-success" style=" font-size:20px;display:none;" >驳回助教</button>

                        <button  class="id_reject_to_master btn btn-success" style=" font-size:20px;display:none;" >驳回组长</button>
                    </div>
                </div>


            </div>

            <div class="col-xs-12 col-md-6  ">
                <div class="col-xs-12   ">
                    <div class="panel panel-success"  id="id_parent">
                        <div class="panel-heading">
                            家长信息
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　姓名:</span>
                                        <input class="form-control" id="id_parent_real_name" value="{{ $init_data["parent_real_name"] }}" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name"   >　　关系:</span>
                                        <select class="form-control"  id="id_relation_ship" >    </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >电子邮箱:</span>
                                        <input class="form-control"  id="id_parent_email" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　电话:</span>
                                        <input class="form-control"  id="id_phone" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >联系时间:</span>
                                        <input class="form-control" id="id_call_time" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　　地址:</span>
                                        <textarea class="form-control"  id="id_addr" >     </textarea>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xs-12  ">
                    <div class="panel panel-danger"  id="id_require">
                        <div class="panel-heading">
                            家长期待
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >辅导老师:</span>
                                        <input class="form-control" id="id_teacher" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >首次上课时间:</span>
                                        <input class="form-control" id="id_first_lesson_time" />
                                    </div>
                                </div>

                                <!-- <div class="col-md-12">
                                     <div class="input-group ">
                                     <span class="field-name" >常规课上课时间:</span>
                                     <textarea class="form-control" id="id_common_lesson_time"  placeholder="参考例子：数学，周二周五晚上6点到7点，  物理，周四周六晚上7点到8点"   >  </textarea>
                                     </div>
                                     </div>

                                   -->
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >包装老师:</span>
                                        <input class="form-control " id="id_teacher_info" placeholder="你对家长介绍老师水平,:金牌老师 ,在职老师 .." />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >试听反馈:</span>
                                        <textarea class="form-control" id="id_test_lesson_info" style="height:180px" placeholder="参考例子：本节试听课课堂气氛较好,学生能够跟着老师的节奏进行新课的学习,学生多次配 合老师做一些关于声音的小实验.学生愿意学习物理,兴趣比较浓. 徐赵阳同学是七升八的学生,我选取了学生所在地物理教材版本苏科版第一章第 一节声音的学习,这一节中有很多小实验,要求学生配合老师进行动手实验,徐赵阳 同学很不错,多次进行实验,尽管我看不到,但是我能够感知到学生在动手做,并且 能够从实验中得出正确的实验结果.新课的知识学生能够接受,说明学生的接受能 力也较好,分析和总结的能力也较高.学生的态度比较端正,一节近 50"   >  </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >礼包地址:</span>
                                        <textarea class="form-control" id="id_mail_addr"  >  </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >　开发票:</span>
                                        <select class="form-control"  id="id_has_fapiao"  >  </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >发票抬头:</span>
                                        <input class="form-control" id="id_fapai_title" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >上课安排:</span>
                                        <textarea class="form-control" id="id_lesson_plan" style="height:120px" placeholder="上课的频次以及上课的时间；参考例子：8/22 8/23 8/26 8/27 8/29 晚上 19:00-21:00 已经和老师确认时间" >  </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name">每周课次:</span>
                                        <select id="id_week_lesson_num" class="form-control" >
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >每次课时:</span>
                                        <select id="id_except_lesson_count" class="form-control" >
                                            <option value="0">0</option>
                                            <option value="100">1</option>
                                            <option value="150">1.5</option>
                                            <option value="200">2</option>
                                            <option value="250">2.5</option>
                                            <option value="300">3</option>
                                            <option value="350">3.5</option>
                                            <option value="400">4</option>
                                            <option value="450">4.5</option>
                                            <option value="500">5</option>
                                        </select>
                                    </div>
                                    <span style="color:red"> *&nbsp1课时=40分钟</span>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <span class="field-name" >特殊需求:</span>
                                        <textarea class="form-control" id="id_parent_other_require"  style="height:120px"  placeholder="如发送作业、讲义、提前预习等，家长要求班主任多关注">  </textarea>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
        </div>
    </section>
@endsection
