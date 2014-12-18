<?php
return array(
'log4php'=>array( 
    'rootLogger' => array(
        'appenders' => array('default'),
    ),
    
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderFile',
            'layout' => array(
                'class' => 'LoggerLayoutSimple'
            ),
            'params' => array(
                'file' => dirname(__FILE__).DIRECTORY_SEPARATOR.'my.log',
                'append' => true
            ),
        )
    )
));