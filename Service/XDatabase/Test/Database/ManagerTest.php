<?php
namespace X\Service\XDatabase\Test\Database;
/**
 * 
 */
use X\Core\Util\TestCase\ServiceTestCase;
use X\Service\XDatabase\Core\Database\Manager;
use X\Service\XDatabase\Core\Util\Exception;

/**
 * 
 */
class ManagerTest extends ServiceTestCase {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ServiceTestCase::getServiceClass()
     */
    protected function getServiceClass() {
        return 'X\\Service\\XDatabase\\Service';
    }
    
    public function test_manager() {
        /* @var $service \X\Service\XDatabase\Service */
        $service = $this->manager;
        $manager = $service->getDatabaseManager();
        
        $manager->register('test-default', array (
          'dsn' => 'mysql:host=localhost;dbname=test',
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
        ));
        
        $manager->stop();
        $manager->start();
        
        $this->assertSame(Manager::DEFAULT_DATADASE, $manager->getCurrentName());
        $manager->switchTo('test-default');
        $this->assertSame('test-default', $manager->getCurrentName());
        $this->assertInstanceOf('X\\Service\\XDatabase\\Core\\Database\\Database', $manager->get());
        
        try {
            $manager->register('test-default', array());
            $this->fail('An exception should be throwed if try to register a database twice.');
        } catch ( Exception $e ){}
        
        try {
            $manager->get('non-exists');
            $this->fail('An exception should be throwed if try to get a non exists database.');
        } catch ( Exception $e ) {}
        
        $manager->unregister('test-default');
    }
}