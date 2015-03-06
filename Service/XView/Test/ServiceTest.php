<?php
namespace X\Service\XView\Test;
/**
 * 
 */
use X\Core\X;
use X\Core\Util\TestCase\ServiceTestCase;
use X\Service\XView\Service as XViewService;
use X\Service\XView\Core\Util\Exception;
/**
 *
 */
class ServiceTest extends ServiceTestCase {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ServiceTestCase::getServiceClass()
     */
    protected function getServiceClass() {
        return 'X\\Service\\XView\\Service';
    }
    
    /**
     * 
     */
    public function test_service() {
        /* @var $service XViewService */
        $service = X::system()->getServiceManager()->get(XViewService::getServiceName());
        $service->getConfiguration()->set('auto_display', true);
        
        $service->createHtml('test');
        $this->assertContains('test', $service->getList());
        $this->assertTrue($service->has('test'));
        $service->activeView('test');
        
        /* @var $view \X\Service\XView\Core\Handler\Html */
        $view = $service->get('test');
        $view->setLayout('TEST LAYOUT');
        
        try {
            $service->createHtml('test');
            $this->fail('An exception should be throwed if try to register a view twice.');
        } catch ( Exception $e ){}
        
        try {
            $service->get('non-exists-view');
            $this->fail('An exception should be throwed if try to get a non exists view.');
        } catch ( Exception $e ) {}
        
        ob_start();
        ob_implicit_flush(false);
        $service->stop();
        
        $layoutConent = array();
        $layoutConent[] = '<!DOCTYPE html>';
        $layoutConent[] = '<html xmlns=\"http://www.w3.org/1999/xhtml\">';
        $layoutConent[] = '<head>';
        $layoutConent[] = '<title></title>';
        $layoutConent[] = '</head>';
        $layoutConent[] = ' ';
        $layoutConent[] = 'TEST LAYOUT';
        $layoutConent[] = '</html>';
        $layoutConent = implode("\n", $layoutConent);
        $this->assertSame($layoutConent, ob_get_clean());
        
        $service->start();
        ob_start();
        ob_implicit_flush(false);
        $service->stop();
        $this->assertSame('', ob_get_clean());
        
        $service->start();
    }
}