<?php
if ( isset($_SERVER['HTTP_HOST']) && 'www.lunome.com' === $_SERVER['HTTP_HOST'] ) {
    return array (
        'databases' =>
        array (
            'default' =>
            array (
                'dsn' => 'mysql:host=localhost;dbname=lunomeco_lunome',
                'username' => 'lunomeco_lunome',
                'password' => 'ginhappy@1215',
                'charset' => 'utf8',
            ),
        ),
    );
} else {
    return array (
        'databases' =>
        array (
            'default' =>
            array (
                'dsn' => 'mysql:host=localhost;dbname=lunome',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ),
        ),
    );
}