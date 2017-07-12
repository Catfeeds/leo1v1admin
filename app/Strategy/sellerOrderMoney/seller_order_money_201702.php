<?php
namespace App\OrderPrice;
namespace App\Strategy\sellerOrderMoney  ;

class seller_order_money_201702  extends  seller_order_money_base
{

    static $percent_config = [
        0     => 0,
        20000 => 3,
        50000 => 5,
        60000 => 8,
        100000 => 10,
        180000 => 12,
        230000 => 15,
    ];


}