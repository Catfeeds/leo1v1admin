@extends('layouts.app')
@section('content')


    <section class="content ">
        <div>
            <div class="row">
               <div class="col-xs-12 col-md-4">
                   <div id="id_date_range"></div>
               </div>
               <div class="col-xs-6 col-md-2">
                   <div class="input-group">
                       <span>转化率</span>
                       <select id="id_tranfer_per" class="opt-change" >
                       </select>
                   </div>
               </div>
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>科目</span>
                        <select id="id_subject" class="opt-change" >
                        </select>
                    </div>
                </div>
                 <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>年级</span>
                        <select id="id_grade_part_ex" class="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-1 col-md-2">
                    <div class="input-group ">
                        <span >老师</span>
                        <input id="id_teacherid"  />
                    </div>
                </div>
                <div class="row">
                   <div class="col-xs-6 col-md-2">
                        <button class="btn btn-warning" id="id_add_train_info">添加培训信息</button>
                    </div>
                </div>
                </div>

        </div>
        
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>#</td>
                    <td>老师姓名</td>
                    <td>学科</td>
                    <td>年级</td>
                    <td>培训通过时间</td>
                    <td>联系方式</td>
                    <td>转化率</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['num']}}</td>
                        <td>{{$var['realname']}}</td>
                        <td>{{$var['subject_str']}}</td>
                        <td>{{$var['grade_part_ex_str']}}</td>
                        <td>{{$var['train_through_new_time']}}</td>
                        <td>{{$var['phone']}}</td>
                        <td>{{$var['per']}}% ({{$var['have_order']}}/{{$var['person_num']}})</td>
                        <td>
                            <div class="opt-div" 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

