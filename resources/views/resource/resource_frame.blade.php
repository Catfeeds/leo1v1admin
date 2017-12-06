@extends('layouts.app_old2')
@section('content')
    <!-- <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
         <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    -->
    <script type="text/javascript" src="/js/jquery.contextify.js"></script>
    <section class="content ">

        <div class="hide">
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table "  >
            <thead>
                <tr>
                    <td>资料类型</td>
                    <td>角色标签</td>
                    <td>角色标签</td>
                    <td>一级标签</td>
                    <td>二级标签</td>
                    <td>三级标签</td>
                    <td>四级标签</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="{{@$var['level']}}" data-resource_type="{{@$var['resource_type']}}" data-is_ban="{{@$var['is_ban']}}">
                        <td data-class_name="{{@$var['key1_class']}}" class="key1" data-index="1">{{@$var["resource_type_str"]}} </td>
                        <td data-class_name="{{@$var['key2_class']}}" class="key2 {{@$var['key1_class']}} {{@$var['key2_class']}} right-menu" data-index="2">{{@$var["subject_str"]}} </td>
                        <td data-class_name="{{@$var['key3_class']}}" class="key3 {{@$var['key2_class']}} {{@$var['key3_class']}} right-menu" data-index="3">{{@$var["grade_str"]}} </td>
                        <td data-class_name="{{@$var['key4_class']}}" class="key4 {{@$var['key3_class']}} {{@$var['key4_class']}} right-menu" data-index="4">{{@$var["tag_one_str"]}} </td>
                        <td data-class_name="{{@$var['key5_class']}}" class="key5 {{@$var['key4_class']}} {{@$var['key5_class']}} right-menu" data-index="5">{{@$var["tag_two_str"]}} </td>
                        <td data-class_name="{{@$var['key6_class']}}" class="key6 {{@$var['key5_class']}} {{@$var['key6_class']}} right-menu" data-index="6">{{@$var["tag_three_str"]}} </td>
                        <td data-class_name="{{@$var['key7_class']}}" class="key7 {{@$var['key6_class']}} {{@$var['key7_class']}} right-menu" data-index="7">{{@$var["tag_four_str"]}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
