<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Smartphone\Util\Action;

/**
 * 
 */
abstract class Menu extends Visual {
    /**
     * 
     */
    public function runAction() {}
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::afterRunAction()
     */
    protected function afterRunAction() {
        $menu = $this->getMenu();
        $menu[] = array('label'=>'返回', 'link'=>$this->getReturnURL());

        $view = $this->getView();
        $viewName = 'MENU_INDEX';
        $viewPath = $this->getParticleViewPath('Util/Menu');
        $view->loadParticle($viewName, $viewPath);
        $view->setDataToParticle($viewName, 'menu', $menu);
        
        $view->title = '&nbsp';
        parent::afterRunAction();
    }
    
    /**
     * 
     */
    abstract protected function getReturnURL ();
    
    /**
     * 
     */
    abstract protected function getMenu();
}