<?php
namespace X\Service\XRequest\Test\Core;

/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XRequest\Core\Request;

/**
 * 
 */
class RequestTest extends TestCase {
    /**
     * 
     */
    public function test_request() {
        $old_GET = $_GET;
        $old_SERVER = $_SERVER;
        
        $_GET['parm1'] = 'value1';
        $_SERVER['REMOTE_ADDR'] = 'this-is-a-test-ip';
        $_SERVER['HTTP_HOST'] = 'this-is-a-test-host';
        $_SERVER['HTTP_USER_AGENT'] = 'test-user-agent';
        
        $request = new Request();
        $this->assertSame('this-is-a-test-ip', $request->getClientIP());
        $this->assertSame('http', $request->getScheme());
        $_SERVER['HTTPS'] = true;
        $this->assertSame('https', $request->getScheme());
        $this->assertSame('this-is-a-test-host', $request->getHost());
        $this->assertSame(array('parm1'=>'value1'), $request->getParameters());
        $this->assertSame('test-user-agent', $request->getUserAgent());
        sleep(1);
        $this->assertGreaterThan(0, $request->getTimeSpend());
        
        $_GET = $old_GET;
        $_SERVER = $old_SERVER;
    }
}