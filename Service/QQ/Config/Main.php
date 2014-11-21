<?php 
return array(
'QQConnect' => array(
    'appid'     => '101161224',
    'appkey'    => 'f03143e996578b9222180fc85d594473',
    'callback'  => sprintf('http://%s/index.php?module=lunome&action=user/login/qqcallback', $_SERVER['HTTP_HOST']),
)
);