<?php
return array (
    'isDebug'         => true,
    'assets-base-url' => (isset($_SERVER['HTTP_HOST']) && 'lunome.kupoy.com' === $_SERVER['HTTP_HOST']) 
                        ? 'http://lunome.kupoy.com/Assets'
                        : 'http://lunome-assets.qiniudn.com',
);