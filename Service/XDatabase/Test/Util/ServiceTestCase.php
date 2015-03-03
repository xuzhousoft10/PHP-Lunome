<?php
namespace X\Service\XDatabase\Test\Util;
/**
 * 
 */
use X\Core\Util\TestCase\ServiceTestCase as CoreServiceTestCase;
/**
 * 
 */
abstract class ServiceTestCase extends CoreServiceTestCase {
    /**
     * @return string
     */
    protected function getServiceClass() {
        return 'X\\Service\\XDatabase\\Service';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ManagerTestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
    
        $this->manager->getDatabaseManager()->register('test-default', array (
            'dsn' => 'mysql:host=localhost;dbname=test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ));
        $this->manager->getDatabaseManager()->switchTo('test-default');
    }
}