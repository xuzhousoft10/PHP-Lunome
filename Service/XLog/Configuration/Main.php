<?php
use X\Core\X;
use X\Service\XDatabase\Service as XDatabaseService;
$dbConfiguration = X::system()->getServiceManager()->get(XDatabaseService::getServiceName())->getConfiguration()->get('databases');
$dbConfiguration = $dbConfiguration['default'];
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
                'dsn'           => $dbConfiguration['dsn'],
                'user'          => $dbConfiguration['username'],
                'password'      => $dbConfiguration['password'],
                'table'         => 'system_log_debug',
                'insertSql'     => 'INSERT INTO system_log_debug ('.
                                   'time,logger,level,message,exception,file,line,class,method,sid,session,pid,env,server,request,cookie'.
                                   ') VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                'insertPattern' => '%date{Y-m-d H:i:s},%logger,%level,%message,%exception,%file,%line,%class,%method,%sid,%session,%pid,%env,%server,%request,%cookie'
            ),
        )
    )
));