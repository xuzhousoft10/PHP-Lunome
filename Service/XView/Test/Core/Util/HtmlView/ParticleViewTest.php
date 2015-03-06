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
        $this->assertSame("v1:value1\npv1:value1", $viewContent);
    }
}