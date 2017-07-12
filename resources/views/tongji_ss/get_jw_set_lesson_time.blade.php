@extends('layouts.app')
@section('content')
    
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <section class="content ">
        
        <div>
            <div class="row" >
               

            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <caption>教务平均排课时长(小时)</caption>
            <thead>
                <tr>
                    <td >类型</td>
                    <td >周一</td>
                    <td >周二</td>
                    <td>周三</td>                  
                    <td>周四</td>
                    <td>周五</td>
                    <td>周六</td>
                    <td>周日</td>                 
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>               
                    <tr>
                        <td>期待时间减去排课时间</td>
                        <td>
                            语文:{{@$ret[2][1]["hour"]}}<br>
                            数学:{{@$ret[2][2]["hour"]}}<br>
                            英语:{{@$ret[2][3]["hour"]}}<br>
                            物理:{{@$ret[2][5]["hour"]}}<br>
                            化学:{{@$ret[2][4]["hour"]}}<br>
                            其他:{{@$ret[2][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$ret[3][1]["hour"]}}<br>
                            数学:{{@$ret[3][2]["hour"]}}<br>
                            英语:{{@$ret[3][3]["hour"]}}<br>
                            物理:{{@$ret[3][5]["hour"]}}<br>
                            化学:{{@$ret[3][4]["hour"]}}<br>
                            其他:{{@$ret[3][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$ret[4][1]["hour"]}}<br>
                            数学:{{@$ret[4][2]["hour"]}}<br>
                            英语:{{@$ret[4][3]["hour"]}}<br>
                            物理:{{@$ret[4][5]["hour"]}}<br>
                            化学:{{@$ret[4][4]["hour"]}}<br>
                            其他:{{@$ret[4][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$ret[5][1]["hour"]}}<br>
                            数学:{{@$ret[5][2]["hour"]}}<br>
                            英语:{{@$ret[5][3]["hour"]}}<br>
                            物理:{{@$ret[5][5]["hour"]}}<br>
                            化学:{{@$ret[5][4]["hour"]}}<br>
                            其他:{{@$ret[5][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$ret[6][1]["hour"]}}<br>
                            数学:{{@$ret[6][2]["hour"]}}<br>
                            英语:{{@$ret[6][3]["hour"]}}<br>
                            物理:{{@$ret[6][5]["hour"]}}<br>
                            化学:{{@$ret[6][4]["hour"]}}<br>
                            其他:{{@$ret[6][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$ret[7][1]["hour"]}}<br>
                            数学:{{@$ret[7][2]["hour"]}}<br>
                            英语:{{@$ret[7][3]["hour"]}}<br>
                            物理:{{@$ret[7][5]["hour"]}}<br>
                            化学:{{@$ret[7][4]["hour"]}}<br>
                            其他:{{@$ret[7][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$ret[1][1]["hour"]}}<br>
                            数学:{{@$ret[1][2]["hour"]}}<br>
                            英语:{{@$ret[1][3]["hour"]}}<br>
                            物理:{{@$ret[1][5]["hour"]}}<br>
                            化学:{{@$ret[1][4]["hour"]}}<br>
                            其他:{{@$ret[1][6]["hour"]}}<br>
                        </td>
                        
                        
                        <td>
                        </td>
                    </tr>               
                    <tr>
                        <td>排课时间减去申请时间(21点前) </td>
                        <td>
                            语文:{{@$arr[2][1]["hour"]}}<br>
                            数学:{{@$arr[2][2]["hour"]}}<br>
                            英语:{{@$arr[2][3]["hour"]}}<br>
                            物理:{{@$arr[2][5]["hour"]}}<br>
                            化学:{{@$arr[2][4]["hour"]}}<br>
                            其他:{{@$arr[2][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$arr[3][1]["hour"]}}<br>
                            数学:{{@$arr[3][2]["hour"]}}<br>
                            英语:{{@$arr[3][3]["hour"]}}<br>
                            物理:{{@$arr[3][5]["hour"]}}<br>
                            化学:{{@$arr[3][4]["hour"]}}<br>
                            其他:{{@$arr[3][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$arr[4][1]["hour"]}}<br>
                            数学:{{@$arr[4][2]["hour"]}}<br>
                            英语:{{@$arr[4][3]["hour"]}}<br>
                            物理:{{@$arr[4][5]["hour"]}}<br>
                            化学:{{@$arr[4][4]["hour"]}}<br>
                            其他:{{@$arr[4][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$arr[5][1]["hour"]}}<br>
                            数学:{{@$arr[5][2]["hour"]}}<br>
                            英语:{{@$arr[5][3]["hour"]}}<br>
                            物理:{{@$arr[5][5]["hour"]}}<br>
                            化学:{{@$arr[5][4]["hour"]}}<br>
                            其他:{{@$arr[5][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$arr[6][1]["hour"]}}<br>
                            数学:{{@$arr[6][2]["hour"]}}<br>
                            英语:{{@$arr[6][3]["hour"]}}<br>
                            物理:{{@$arr[6][5]["hour"]}}<br>
                            化学:{{@$arr[6][4]["hour"]}}<br>
                            其他:{{@$arr[6][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$arr[7][1]["hour"]}}<br>
                            数学:{{@$arr[7][2]["hour"]}}<br>
                            英语:{{@$arr[7][3]["hour"]}}<br>
                            物理:{{@$arr[7][5]["hour"]}}<br>
                            化学:{{@$arr[7][4]["hour"]}}<br>
                            其他:{{@$arr[7][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$arr[1][1]["hour"]}}<br>
                            数学:{{@$arr[1][2]["hour"]}}<br>
                            英语:{{@$arr[1][3]["hour"]}}<br>
                            物理:{{@$arr[1][5]["hour"]}}<br>
                            化学:{{@$arr[1][4]["hour"]}}<br>
                            其他:{{@$arr[1][6]["hour"]}}<br>
                        </td>
                        
                        
                        <td>
                        </td>
                    </tr>               
                    <tr>
                        <td>排课时间减去申请时间(21点后) </td>
                        <td>
                            语文:{{@$list[2][1]["hour"]}}<br>
                            数学:{{@$list[2][2]["hour"]}}<br>
                            英语:{{@$list[2][3]["hour"]}}<br>
                            物理:{{@$list[2][5]["hour"]}}<br>
                            化学:{{@$list[2][4]["hour"]}}<br>
                            其他:{{@$list[2][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$list[3][1]["hour"]}}<br>
                            数学:{{@$list[3][2]["hour"]}}<br>
                            英语:{{@$list[3][3]["hour"]}}<br>
                            物理:{{@$list[3][5]["hour"]}}<br>
                            化学:{{@$list[3][4]["hour"]}}<br>
                            其他:{{@$list[3][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$list[4][1]["hour"]}}<br>
                            数学:{{@$list[4][2]["hour"]}}<br>
                            英语:{{@$list[4][3]["hour"]}}<br>
                            物理:{{@$list[4][5]["hour"]}}<br>
                            化学:{{@$list[4][4]["hour"]}}<br>
                            其他:{{@$list[4][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$list[5][1]["hour"]}}<br>
                            数学:{{@$list[5][2]["hour"]}}<br>
                            英语:{{@$list[5][3]["hour"]}}<br>
                            物理:{{@$list[5][5]["hour"]}}<br>
                            化学:{{@$list[5][4]["hour"]}}<br>
                            其他:{{@$list[5][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$list[6][1]["hour"]}}<br>
                            数学:{{@$list[6][2]["hour"]}}<br>
                            英语:{{@$list[6][3]["hour"]}}<br>
                            物理:{{@$list[6][5]["hour"]}}<br>
                            化学:{{@$list[6][4]["hour"]}}<br>
                            其他:{{@$list[6][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$list[7][1]["hour"]}}<br>
                            数学:{{@$list[7][2]["hour"]}}<br>
                            英语:{{@$list[7][3]["hour"]}}<br>
                            物理:{{@$list[7][5]["hour"]}}<br>
                            化学:{{@$list[7][4]["hour"]}}<br>
                            其他:{{@$list[7][6]["hour"]}}<br>
                        </td>
                        <td>
                            语文:{{@$list[1][1]["hour"]}}<br>
                            数学:{{@$list[1][2]["hour"]}}<br>
                            英语:{{@$list[1][3]["hour"]}}<br>
                            物理:{{@$list[1][5]["hour"]}}<br>
                            化学:{{@$list[1][4]["hour"]}}<br>
                            其他:{{@$list[1][6]["hour"]}}<br>
                        </td>
                        
                        
                        <td>
                        </td>
                    </tr>               

            </tbody>
        </table>       
        @include("layouts.page")
    </section>
    
@endsection

