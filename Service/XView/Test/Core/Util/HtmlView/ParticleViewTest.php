<?php
namespace X\Service\XView\Test\Core\Util\HtmlView;
/**
 *
 */
use X\Core\X;
use X\Core\Util\TestCase;
use X\Service\XView\Core\Handler\Html;
/**
 * 
 */
class ParticleViewTest extends TestCase {
    /**
     * 
     */
    public function test_ParticleView() {
        $html = new Html();
        $html->getDataManager()->set('v1', 'value1');
        $testParticle = X::system()->getPath('Service/XView/Test/Fixture/View/Html/Particle1.php');
        $particleView = $html->getParticleViewManager()->load('test1', $testParticle);
        
        $particleView->getDataManager()->set('pv1', 'value1');
        $particleView->getOptionManager()->set('option1', 'value1');
        
        ob_start();
        ob_implicit_flush(false);
        $particleView->display();
        $viewContent = ob_get_clean();
        $this->assertSame("v1:value1\npv1:value1\n", $viewContent);
        $particleView->cleanUp();
        
        $particleView = $html->getParticleViewManager()->load('test2', array($this, 'particleViewHandler'));
        $particleView->getOptionManager()->set('mode', '1');
        $this->assertSame('value1-1', $particleView->toString());
        $particleView->getOptionManager()->set('mode', '2');
        $particleView->cleanUp();
        $this->assertSame('value1-X', $particleView->toString());
        
        $particleView = $html->getParticleViewManager()->load('test2', 'JUST STRING');
        $this->assertSame('JUST STRING', $particleView->toString());
        
        $particleView = $html->getParticleViewManager()->load('test2', null);
        $this->assertSame(null, $particleView->toString());
    }
     
    /**
     * @param unknown $data
     * @param unknown $option
     * @return string
     */
    public function particleViewHandler( $data, $option ) {
        if ( '1' === $option['mode'] ) {
            return $data['v1'].'-1';
        } else {
            return $data['v1'].'-X';
        }
    }
}