@if ($page_info["page_num"] > 1)
    <div class="pages">
        <!-- <a href="javascript:;" class="btn page-opt-show-all-xls" data="{{$page_info["page"]["input_page_num_url"]}}" >全量下载</a> -->
        <span>总记录数:{{@$page_info["result_num"] }}</span> 
        <a href="javascript:;" style="display:none;" class="btn page-opt-show-all" data="{{$page_info["page"]["input_page_num_url"]}}" >显示全部</a>
        <select   data="{{$page_info["page"]["input_page_num_url"]}}" class= " page-opt-select-page"  >
            <option value="10"> 每页10行 </option>
            <option value="50"> 每页50行 </option>
            <option value="100"> 每页100行 </option>
            <option value="500"> 每页500行 </option>
            <option value="1000"> 每页1000行 </option>
            <option value="5000"> 每页5000行 </option>
        </select>

        <input style="width:50px" placeholder="输入页数" data="{{$page_info["page"]["input_page_num_url"]}}"> </input>

        <!--上一页-->
        @if ( $page_info["current_page"]==1 )
            <a class="page_prev page_grey" href="javascript:void(0);"><</a>
        @else
            <a class="page_prev" href="{{$page_info["page"]["previous_url"]}}" ><</a>
        @endif
        <!--页码-->
        @if  ($page_info["page_num"]<10)
            @foreach ($page_info["page"]["pages"] as $key => $var )
                @if ($var["page_num"]== $page_info["current_page"])
                    <a class="page_num page_cur" href="javascript:void(0);" name="page_btn" page="{{$var["page_num"]}}">{{$var["page_num"]}}</a>
                @else
                    <a class="page_num" href="{{$var["page_link"]}}" name="page_btn" page="{{$var["page_num"]}}">{{$var["page_num"]}}</a>
                @endif
            @endforeach
        @else
            @if ($page_info["current_page"]< 6)
                @foreach ($page_info["page"]["pages"] as $var )
                    @if( $var["page_num"]== $page_info["current_page"])
                        <a class="page_num page_cur" href="javascript:void(0);">{{$var["page_num"]}}</a>
                    @else
                        <a class="page_num" href="{{$var["page_link"]}}">{{$var["page_num"]}}</a>
                    @endif
                @endforeach
                <span>...</span>
                <a class="page_num" href=" {{$page_info["page"]["last_page_url"]}}">{{$page_info["page_num"]}}</a>
            @elseif ($page_info["page_num"] - $page_info["current_page"] <4)
                <a class="page_num" href="{{$page_info["page"]["first_page_url"]}}">1</a>
                <span>...</span>
                @foreach ($page_info["page"]["pages"] as $var )
                    @if ($var["page_num"]== $page_info["current_page"])
                        <a class="page_num page_cur" href="javascript:void(0);">{{$var["page_num"]}}</a>
                    @else
                        <a class="page_num" href="{{$var["page_link"]}}">{{$var["page_num"]}}</a>
                    @endif
                @endforeach
            @else
                <a class="page_num" href="{{$page_info["page"]["first_page_url"]}}">1</a>
                <span>...</span>

                @foreach ($page_info["page"]["pages"] as $var )
                    @if ($var["page_num"]== $page_info["current_page"])
                        <a class="page_num page_cur" href="javascript:void(0);">{{$var["page_num"]}}</a>
                    @else

                        <a class="page_num" href="{{$var["page_link"]}}">{{$var["page_num"]}}</a>
                    @endif
                @endforeach
                <span>...</span>
                <a class="page_num" href="{{$page_info["page"]["last_page_url"]}}">{{$page_info["page_num"]}}</a>
            @endif
        @endif
        <!--下一页-->
        @if ($page_info["current_page"]== $page_info["page_num"])
            <a class="page_next page_grey" href="javascript:void(0);">></a>
        @else
            <a class="page_next"  name="page_btn" href="{{$page_info["page"]["next_url"]}}" >></a>
        @endif
    </div>
@endif
