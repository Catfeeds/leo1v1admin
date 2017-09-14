<?php
return [
    'qiniu' => [
        "public" => [
            "url"    => env('QINIU_PUBLIC_URL','http://7u2f5q.com2.z0.glb.qiniucdn.com'),
            "bucket" => env('QINIU_PUBLIC_BUCKET',"ybprodpub"),
        ] ,
        "private_url" => [
            "url"    => env('QINIU_PRIVATE_URL','http://7tszue.com2.z0.glb.qiniucdn.com'),
            "bucket" => env('QINIU_PRIVATE_BUCKET',"ybprod"),
        ] ,
        "access_key" => "yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px",
        "secret_key" => "gPwzN2_b1lVJAr7Iw6W1PCRmUPZyrGF6QPbX1rxz",
    ]
];
