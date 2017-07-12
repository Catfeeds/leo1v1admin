<?php

if (!function_exists('curl_init')) {
    throw new Exception('Pingpp needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new Exception('Pingpp needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
    throw new Exception('Pingpp needs the Multibyte String PHP extension.');
}

// Pingpp singleton
require(dirname(__FILE__) . '/Pingpp.php');

// Utilities
require(dirname(__FILE__) . '/Util/Util.php');
require(dirname(__FILE__) . '/Util/Set.php');
require(dirname(__FILE__) . '/Util/RequestOptions.php');

// Errors
require(dirname(__FILE__) . '/Error/Base.php');
require(dirname(__FILE__) . '/Error/Api.php');
require(dirname(__FILE__) . '/Error/ApiConnection.php');
require(dirname(__FILE__) . '/Error/Authentication.php');
require(dirname(__FILE__) . '/Error/InvalidRequest.php');
require(dirname(__FILE__) . '/Error/RateLimit.php');
require(dirname(__FILE__) . '/Error/Channel.php');

// Plumbing
require(dirname(__FILE__) . '/JsonSerializable.php');
require(dirname(__FILE__) . '/PingppObject.php');
require(dirname(__FILE__) . '/ApiRequestor.php');
require(dirname(__FILE__) . '/ApiResource.php');
require(dirname(__FILE__) . '/SingletonApiResource.php');
require(dirname(__FILE__) . '/AttachedObject.php');
require(dirname(__FILE__) . '/Collection.php');

// Pingpp API Resources
require(dirname(__FILE__) . '/Charge.php');
require(dirname(__FILE__) . '/Refund.php');
require(dirname(__FILE__) . '/RedEnvelope.php');
require(dirname(__FILE__) . '/Event.php');
require(dirname(__FILE__) . '/Transfer.php');
require(dirname(__FILE__) . '/Identification.php');
require(dirname(__FILE__) . '/Customs.php');
require(dirname(__FILE__) . '/BatchRefund.php');
require(dirname(__FILE__) . '/BatchTransfer.php');

// wx_pub OAuth 2.0 method
require(dirname(__FILE__) . '/WxpubOAuth.php');

//引入配置文件
require(dirname(__FILE__) . '/config.php');