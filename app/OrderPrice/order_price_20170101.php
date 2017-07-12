<?php
namespace App\OrderPrice;
use \App\Enums as E;

class order_price_20170101 extends order_price_base
{

    static $grade_price_config=[
        101=> 70,
        102=> 70,
        103=> 70,
        104=> 70,
        105=> 70,
        106=> 75,
        201=> 80,
        202=> 86.66666666666666666667,
        203=> 110,
        301=> 135,
        302=> 140,
        303=> 150,
    ];
    static $new_discount_config = [
        90 => 98,
        180 => 96,
        270 => 94,
        360 => 92,
        450 => 90,
    ];

    static $new_present_lesson_config = [
        90 => 3,
        180 => 12,
        270 => 24,
        360 => 45,
        450 => 60,
    ];

    static $next_discount_config =[
        180 => 96,
        270 => 94,
        360 => 92,
        450 => 90,
    ];

    static $next_present_lesson_config = [
        180 => 12,
        270 => 24,
        360 => 45,
        450 => 60,
    ];

}