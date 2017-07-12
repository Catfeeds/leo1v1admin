@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
  
    <section class="content ">
        <div>
            <div class="row ">
                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">年级</span>
                     <select class="opt-change form-control" id="id_grade" >
                     </select>
                     </div>
                     </div>
                     <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">科目</span>
                     <select class="opt-change form-control" id="id_subject" >
                     </select>
                     </div>
                     </div>
                     <div class="col-xs-6 col-md-4">
                     <div class="input-group ">
                     <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="address" id="id_note_name"  placeholder="小标题 回车查找" />
                     </div>
                     </div>
 -->
                <div class="col-xs-6 col-md-2">
                    <button id="id_question_tongji" class="btn btn-primary">开始统计</button>
                </div >

            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>年级</td>
                    <td>科目</td>
                    <td>主标题</td>
                    <td>次标题</td>
                    <td>小标题</td>
                    <td>选择题</td>
                    <td>填空题</td>
                    <td>问答题</td>
                    <td>知识点</td>                  
                    <td>操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>                      
                        <td class="id_grade">{{@$var["grade_str"]}} </td>
                        <td class="id_subject">{{@$var["subject_str"]}} </td>
                        <td class="id_main_note_name">{{@$var["main_note_name"]}} </td>
                        <td class="id_second_note_name">{{@$var["second_note_name"]}} </td>
                        <td>{{@$var["note_name"]}} </td>
                        <td class="id_select_count">{{@$var["select_count"]}} </td>
                        <td class="id_tk_count">{{@$var["tk_count"]}} </td>
                        <td class="id_wd_count">{{@$var["wd_count"]}} </td>
                        <td class="id_zsd_count">{{@$var["zsd_count"]}} </td>                    
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                                <a class="question_info" style="display:none"></a> 
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
