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
                /**
                    CREATE TABLE `system_log_debug` (
                      `time` datetime DEFAULT NULL,
                      `logger` varchar(64) DEFAULT NULL,
                      `level` varchar(16) DEFAULT NULL,
                      `message` varchar(256) DEFAULT NULL,
                      `exception` text,
                      `file` varchar(1024) DEFAULT NULL,
                      `line` int(11) DEFAULT NULL,
                      `class` varchar(256) DEFAULT NULL,
                      `method` varchar(64) DEFAULT NULL,
                      `sid` varchar(64) DEFAULT NULL,
                      `session` text,
                      `pid` varchar(16) DEFAULT NULL,
                      `env` text,
                      `server` text,
                      `request` text,
                      `cookie` text
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                 */
                'insertSql'     => 'INSERT INTO system_log_debug ('.
                                   'time,logger,level,message,exception,file,line,class,method,sid,session,pid,env,server,request,cookie'.
                                   ') VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                'insertPattern' => '%date{Y-m-d H:i:s},%logger,%level,%message,%exception,%file,%line,%class,%method,%sid,%session,%pid,%env,%server,%request,%cookie'
            ),
        )
    )
));