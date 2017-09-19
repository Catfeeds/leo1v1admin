<?php
include "TopSdk.php";
class TaobaoShop{

    var $sessionKey;
    var $name;

    public function __construct($appKey,$secretKey,$sessionKey,$name)
    {
        $this->topClient            = new TopClient;
        $this->topClient->appkey    = $appKey;
        $this->topClient->secretKey = $secretKey;
        $this->sessionKey           = $sessionKey;
        $this->name                 = $name;
    }

    /**
     * 获取淘宝店铺的分类
     * taobao.sellercats.list.get
     */
    public function taobao_sellercats_list(){
        $req = new SellercatsListGetRequest;
        $req->setNick($this->name);
        $ret= $this->topClient->execute($req);
        $ret=json_decode( json_encode($ret),true);

        return $ret;
    }

    /**
     * 获取类别下的商品
     * taobao.tae.items.select
     */
    public function taobao_tae_items_select($cid,$page){
        $req = new TaeItemsSelectRequest;
        $req->setSellerNick($this->name);
        $req->setSellerCids($cid);
        $req->setPageNo($page);
        $req->setPageSize("200");
        $ret = $this->topClient->execute($req,$this->sessionKey);
        $ret = json_decode( json_encode( $ret),true );

        return $ret;
    }

}