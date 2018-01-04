<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
use \App\Libs;

class taobao_manage extends Controller
{
    use CacheNick;
    var $tbShop;

    /**
     * 接口 $this->tbShop->taobao_tae_items_select() 返回 code 27 无效Session解决方法
     * 访问 http://open.taobao.com/apitools/apiTools.htm?apiId=23582&apiName=taobao.tae.items.select&scopeId=11681
     * 点击 "Session获取工具(Authorize Tools)",输入appkey:23277683,验证登陆后获取Session
     */
    public function __construct(){
        include_once(app_path("Libs/taobao_shop/TaobaoShop.php"));
        $tbConfig = Config::get_taobao_shop_app();
        $this->tbShop = new \TaobaoShop($tbConfig['appKey'],$tbConfig['secretKey'],$tbConfig['sessionKey'],$tbConfig['name']);
    }

    /**
     * 更新淘宝物品信息和淘宝分类信息
     * @param page 一页有200个商品
     */
    public function update_taobao_item_list(){
        $page_num    = $this->get_in_int_val("page_num",1);

        $select_type = $this->tbShop->taobao_sellercats_list();
        $this->update_taobao_type_list($select_type);

        $open_iid_list = [];
        foreach($select_type['seller_cats']['seller_cat'] as $val){
            if($val['parent_cid']>0){
                $this->get_taobao_tae_items_select($val['cid'],$page_num,$open_iid_list);
            }
        }

        if(isset($open_iid_list)){
            $this->t_taobao_item->reset_taobao_status();
            foreach($open_iid_list as $item){
                $this->t_taobao_item->field_update_list($item,[
                    "status"=>1,
                ]);
            }
        }

        return $this->output_succ();
    }

    public function get_taobao_tae_items_select($cid,$page_num,&$arr){
        $ret_info = $this->tbShop->taobao_tae_items_select($cid,$page_num);
        dd($ret_info);
        if(isset($ret_info['code']) && $ret_info['code']==27){
            return $ret_info['code'];
        }elseif(isset($ret_info['items']['item_select'])){
            $taobao_list = $ret_info['items']['item_select'];
            $job = new \App\Jobs\AddTaobaoItem($taobao_list,$cid);
            dispatch($job);
            foreach($taobao_list as $v){
                if(!in_array($v['open_iid'],$arr)){
                    $arr[]=$v['open_iid'];
                }
            }
        }

        if($ret_info['has_next']==true){
            $page_num++;
            $this->get_taobao_tae_item_select($cid,$page_num,$arr);
        }
    }

    /**
     * 更新淘宝商铺的分类
     * @param select_type   
     * @return boolean
     * 分类列表在select_type['seller_cats']['seller_cat']
     */
    private function update_taobao_type_list($select_type){
        if(isset($select_type['seller_cats']['seller_cat'])){
            $this->t_taobao_type_list->reset_taobao_type_status();
            foreach($select_type['seller_cats']['seller_cat'] as $val){
                $ret=$this->t_taobao_type_list->field_get_value($val['cid'],"count(1)");
                if(!$ret){
                    $this->t_taobao_type_list->row_insert([
                        "cid"        => $val['cid'],
                        "parent_cid" => $val['parent_cid'],
                        "sort_order" => $val['sort_order'],
                        "name"       => $val['name'],
                        "status"     => 1,
                    ]);
                }else{
                    $this->t_taobao_type_list->field_update_list($val['cid'],[
                        "parent_cid" => $val['parent_cid'],
                        "sort_order" => $val['sort_order'],
                        "name"       => $val['name'],
                        "status"     => 1,
                    ]);
                }
            }
        }
        return true;
    }

    /**
     * 官网的API接口
     * 获取展示的5个分类
     * @return array
     */
    public function get_taobao_sub_type_list(){
        $ret_info=[
            [
                "cid"  => 1200846460,
                "name" => "语文"
            ],[
                "cid"  => 1200846461,
                "name" => "数学"
            ],[
                "cid"  => 1200846462,
                "name" => "英语"
            ],[
                "cid"  => 1200846463,
                "name" => "物理"
            ],[
                "cid"  => 1200846464,
                "name" => "化学"
            ]
        ];
        return $this->output_succ($ret_info);
    }

    /**
     * 官网的API接口
     * 获取该类目下的四个商品(淘宝官方接口按时间编辑顺序倒序排列)
     * @param cid 淘宝类目id
     * @param type 是否是首页(首页只拉取4个商品,非首页则拉去该类目下的所有商品)
     * @return array
     */
    public function get_taobao_package_info_list(){
        $cid  = $this->get_in_int_val('cid',0);
        $type = $this->get_in_str_val('type',1);

        $cid = $cid==0?'':$cid;
        $ret_info = $this->t_taobao_item->get_taobao_item_list($cid,$type);

        $img_href    = "http://img04.taobaocdn.com/bao/uploaded/";
        $taobao_href = "https://item.taobao.com/item.htm?id=";
        $ret_list    = array();
        foreach($ret_info as $key => $val){
            $ret_list[$key]['itemId']     = $val['open_iid'];
            $ret_list[$key]['sub_title']  = $val['title'];
            $ret_list[$key]['price']      = $val['price'];
            $ret_list[$key]['img_url']    = $img_href.$val['pict_url'];
            $ret_list[$key]['taobao_url'] = $taobao_href.$val['product_id'];
        }

        return $this->output_succ($ret_list);
    }

    /**
     * 手动更新淘宝商品
     */
    public function test_taobao(){
        $flag=$this->get_in_int_val("flag");
        if($flag==1){
            // $this->t_taobao_item->truncate_taobao();
        }
        $select_type = $this->tbShop->taobao_sellercats_list();

        // $this->t_taobao_item->reset_taobao_status();
        $open_iid_list=array();
        foreach($select_type['seller_cats']['seller_cat'] as $val){
            if($val['parent_cid']>0){
                $ret_info = $this->tbShop->taobao_tae_items_select($val['cid'],1);
                if(isset($ret_info['items']['item_select'])){
                    $taobao_list=$ret_info['items']['item_select'];
                    $this->taobao_job($taobao_list,$val['cid']);

                    foreach($taobao_list as $v){
                        if(!in_array($v['open_iid'],$open_iid_list)){
                            $open_iid_list[]=$v['open_iid'];
                        }
                    }
                }
            }
        }

        foreach($open_iid_list as $item){
            $this->t_taobao_item->field_update_list($item,[
                "status"=>1,
            ]);
        }
    }

    private function taobao_job($taobao_item,$cid){
        foreach($taobao_item as $val){
            $open_iid      = $val['open_iid'];
            $last_modified = strtotime($val['last_modified']);
            $time          = $this->t_taobao_item->get_last_modified($open_iid);
            $ret           = true;
            if($time>0){
                $cid_o = $this->t_taobao_item->get_cid($open_iid);
                if(strpos($cid_o,$cid)===false){
                    $cid_n = $cid_o.",".$cid;
                    $ret   = $this->t_taobao_item->field_update_list($open_iid,[
                        "cid" => $cid_n,
                    ]);
                }
                if($time < $last_modified){
                    $ret = $this->t_taobao_item->field_update_list($open_iid,[
                        "title"         => $val['title'],
                        "pict_url"      => $val['pict_url'],
                        "price"         => $val['price'],
                        "last_modified" => $last_modified,
                    ]);
                }
            }else{
                $ret = $this->t_taobao_item->row_insert([
                    "cid"           => $val['cid'],
                    "open_iid"      => $open_iid,
                    "title"         => $val['title'],
                    "pict_url"      => $val['pict_url'],
                    "price"         => $val['price'],
                    "last_modified" => $last_modified,
                ]);
            }
            if(!$ret){
                \App\Helper\Utils::logger("error the taobao is:".json_encode($val));
            }
        }
    }

    public function taobao_type(){
        $type=$this->get_in_int_val("type",-1);

        $ret_info = $this->t_taobao_type_list->get_taobao_type_list($type);
        foreach($ret_info['list'] as &$val){
            $val['type_str']=$val['type']==1?"是":"否";
        }
        \App\Helper\Utils::debug_to_html( $ret_info );
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function update_taobao_type(){
        $cid  = $this->get_in_int_val("cid",0);
        $type = $this->get_in_int_val("type",0);

        if($cid){
            $ret=$this->t_taobao_type_list->field_update_list($cid,[
                "type"=>$type
            ]);
            if($ret){
                return $this->output_succ();
            }else{
                return $this->output_err("设置失败!请重试!");
            }
        }else{
            return $this->output_err("淘宝类别为空!请重试!");
        }
    }

    /**
     * @param parent_cid 根分类
     * @param cid        子分类
     * @param status     状态
     * @return html
     */
    public function taobao_item(){
        $parent_cid = $this->get_in_int_val("parent_cid",-1);
        $cid        = $this->get_in_int_val("cid",-1);
        $status     = $this->get_in_int_val("status",-1);
        $page_num   = $this->get_in_page_num();

        $ret_info = $this->t_taobao_item->get_taobao_item_by_page($page_num,$cid,$status);

        $http_taobao = "http://img.alicdn.com/bao/uploaded/";
        if(is_array($ret_info['list'])){
            foreach($ret_info['list'] as &$val){
                $val['pict_url']          = $http_taobao.$val['pict_url'];
                $val['last_modified_str'] = date("Y-m-d H:i",$val['last_modified']);
                $val['status_str']        = $val['status']==0?"已下架":"正常";
                $val['product_str']       = $val['product_id']==''||$val['product_id']==0?"未设置":$val['product_id'];
            }
        }

        return $this->pageView(__METHOD__,$ret_info);
    }
    /**
     * 获取/更新淘宝信息
     * @param type 1 获取 2 更新
     * @param open_iid 商品的混淆id,唯一
     * @param product_id 产品id
     * @param sort_order 显示顺序排序
     * @param title  商品名称
     * @param price  商品价格
     */
    public function set_taobao_info(){
        $type       = $this->get_in_int_val("type");
        $open_iid   = $this->get_in_str_val("open_iid");
        $sort_order = $this->get_in_int_val("sort_order");
        $title      = $this->get_in_str_val("title");
        $price      = $this->get_in_int_val("price");

        if($type==1){
            $ret=$this->t_taobao_item->get_taobao_item($open_iid);
            return $this->output_succ(['data'=>$ret]);
        }elseif($type==2){
            if($open_iid!=''){
                $ret = $this->t_taobao_item->field_update_list($open_iid,[
                    "sort_order" => $sort_order,
                    "title" => $title,
                    "price" => $price,
                ]);
            }else{
                return $this->output_err("混淆id出错,不能为空!");
            }

            if($ret==0){
                return $this->output_err("更新失败!");
            }
            return $this->output_succ();
        }else{
            return $this->output_err(-1,"未知类型!");
        }
    }

    public function get_taobao_type_select(){
        $parent_cid=$this->get_in_int_val("parent_cid",-1);
        $ret=array();
        if($parent_cid!=-1){
            $ret = $this->t_taobao_type_list->get_taobao_select_list($parent_cid);
        }
        
        return $this->output_succ(['data'=>$ret]);
    }

    /**
     * 通过访问淘宝网页抓取价格信息来更新淘宝商品价格
     */
    public function update_taobao_item_price(){
        $list = $this->t_taobao_item->get_all_item_list();

        $start_str = "<em class=\"tb-rmb-num\">";
        foreach($list as $val){
            if($val['product_id']!=""){
                $price="";
                $url  = "https://item.taobao.com/item.htm?id=".$val['product_id'];
                $html = file_get_contents($url);

                $left_str = strstr($html,$start_str);
                $left_str = str_replace($start_str,"",$left_str);
                $check_str = ".";
                $price = stristr($left_str,$check_str,true);

                echo $val['open_iid']."|".$val['product_id']."|".$val["price"]."|".$price;
                echo "<br>";
                if($price!=""){
                    $this->t_taobao_item->field_update_list($val['open_iid'],[
                        "price" => $price,
                    ]);
                }else{
                    $this->t_taobao_item->field_update_list($val['open_iid'],[
                        "status"=>0
                    ]);
                }
            }
        }
    }

}