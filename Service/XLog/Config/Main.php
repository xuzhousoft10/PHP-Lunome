<?php
return array(
'log4php'=>array( 
    'rootLogger' => array(
        'appenders' => array('default'),
    ),
    
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderPDO',
            'layout' => array(
                'class' => 'LoggerLayoutSimple'
            ),
            'params' => array(
                'dsn'           => 'mysql:host=localhost;dbname=lunome',
                'user'          => 'root',
                'password'      => '',
                'table'         => 'system_log_debug',
                'insertSql'     => 'INSERT INTO system_log_debug ('.
                                   'time,logger,level,message,exception,file,line,class,method,sid,session,pid,env,server,request,cookie'.
                                   ') VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                'insertPattern' => '%date{Y-m-d H:i:s},%logger,%level,%message,%exception,%file,%line,%class,%method,%sid,%session,%pid,%env,%server,%request,%cookie'
            ),
        )
    )
));