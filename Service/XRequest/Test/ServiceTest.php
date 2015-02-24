<?php
namespace X\Service\XRequest\Test;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase;
use X\Service\XRequest\Service;

/**
 * 
 */
class ServiceTest extends TestCase {
    /**
     * @var \X\Service\XRequest\Service
     */
    private $service = null;
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        $this->service = Service::getService();
        $this->service->start();
    }
    
    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown() {
        $this->service->stop();
        $this->service->destroy();
        parent::tearDown();
    }
    
    /**
     * 
     */
    public function test_service() {
        $this->service->stop();
        $this->service->destroy();
        
        $old_GET = $_GET;
        $old_SERVER = $_SERVER;
        $_GET = array();
        $_SERVER['REQUEST_URI'] = '/book/content/page_1.html';
        $source = '{$module:/.*?/}/{$action:/.*?/}/page_{$page:/.*?/}.html';
        $dest = 'module={$module}&action={$action}&page={$page}';
        $this->service->getRouterManager()->register('test', $source, $dest);
        $this->service->getConfiguration()->set('testing', true);
        
        $this->service->start();
        $this->assertTrue(X::system()->getShortcutManager()->has('createURL'));
        $this->assertSame('/book/content/page_1.html', X::system()->createURL('test', 'book', 'content', '1'));
        $this->assertSame(array('module'=>'book', 'action'=>'content', 'page'=>'1'), $_GET);
        
        $this->service->getRouterManager()->unregister('test');
        $this->service->getConfiguration()->set('testing', false);
        $this->service->getConfiguration()->save();
        
        $_GET = $old_GET;
        $_SERVER = $old_SERVER;
    }
}