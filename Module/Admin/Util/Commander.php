<?php
namespace X\Module\Admin\Util;
use X\Core\X;
use X\Service\XAction\Core\Exception;
class Commander extends \X\Core\Basic {
    /**
     * The console that management is using.
     * 
     * @var Console
     */
    protected $console = null;
    
    /**
     * Init the commander
     * 
     * @return void
     */
    public function __construct() {
        $this->console = new Console();
    }
    
    /**
     * The action service.
     * 
     * @var \X\Service\XAction\XActionService
     */
    protected $actionService = null;
    
    /**
     * Start the commander
     * 
     * @return void
     */
    public function start() {
        $group = 'admin';
        $actionService = X::system()->getServiceManager()->get('XAction');
        $actionService->add($group, 'X\\Module\\Admin\\Action');
        $this->actionService = $actionService;
        
        while ( true ) {
            $this->standby();
        }
    }
    
    /**
     * Wait for command
     * 
     * @return void
     */
    protected function standby() {
        $prompt = 'ADMIN';
        if ( !is_null($this->using) ) {
            $prompt .= sprintf(' [%s @ %s]', $this->using, $this->usingType);
        }
        $prompt .= ' > ';
        
        $this->console->printString($prompt);
        $command = $this->console->getLine();
        if ( empty($command) ) {
            exit();
        }
        $this->handleCommand($command);
    }
    
    /**
     * Handle the command
     * 
     * @param unknown $command
     */
    protected function handleCommand( $command ) {
        $group = 'admin';
        $separatorPos = strpos($command, ' ');
        if ( false !== $separatorPos ) {
            $action = substr($command, 0, strpos($command, ' '));
            $parameterString = substr($command, strpos($command, ' ')+1);
        } else {
            $action = $command;
            $parameterString = '';
        }
        
        if ( 'use' === $action ) {
            $action = 'Xuse';
        }
        
        $actionService = $this->actionService;
        $actionService->setParameters(array('action'=>$action,'parameters'=>$parameterString, 'console'=>$this->console));
        
        try {
            $actionService->run($group);
        } catch ( Exception $e ) {
            $actionService->setParameters(array('action'=>'custom','parameters'=>$command, 'console'=>$this->console));
            $actionService->run($group);
        }
    }
    
    /**
     * The name of using object
     * @var string
     */
    public $using = null;
    
    /**
     * The name of using type.
     * @var string
     */
    public $usingType = null;
}