<?php
namespace X\Module\Administration\Util;

/**
 *
 */
use X\Module\Administration\Module;

/**
 * 
 */
abstract class Action {
    /**
     * @var Module
     */
    private $module = null;
    
    /**
     * @return Module
     */
    public function getModule() {
        return $this->module;
    }
    
    /**
     * @var string
     */
    public $title = '';
    
    /**
     * @var string
     */
    public $layout = null;
    
    /**
     * @var array
     */
    public $menu = array(
        'module'  => array('name'=>'模块管理', 'link'=>'/?module=administration&action=module/index', 'status'=>''),
        'service' => array('name'=>'服务管理', 'link'=>'/?module=administration&action=service/index', 'status'=>''),
    );
    
    /**
     * @param unknown $key
     */
    public function activeMenuItem( $key ) {
        foreach ( $this->menu as $name => $item ) {
            $this->menu[$name]['status'] = '';
        }
        $this->menu[$key]['status'] = 'active';
    }
    
    /**
     * @var array
     */
    private $breadcrumb = array();
    
    /**
     * @param unknown $name
     * @param unknown $link
     */
    public function addBreadcrumbItem( $name, $link ) {
        $this->breadcrumb[] = array('name'=>$name, 'link'=>$link);
    }
    
    /**
     * @param Module $module
     */
    public function __construct( Module $module ) {
        $this->module = $module;
        $this->layout = $this->module->getPath('View/Layout/Main.php');
    }
    
    /**
     * @var array
     */
    private $particles = array();
    
    /**
     * @param string $name
     * @param string $path
     * @param array $data
     * @param array $option
     */
    public function loadParticle( $name, $path, $data=array(), $option=array() ) {
        $this->particles[$name] = array('path'=>$path, 'data'=>$data, 'option'=>$option);
    }
    
    /**
     * @param string $path
     * @return string
     */
    public function getParticlePath( $path ) {
        return $this->getModule()->getPath('View/Particle/'.$path.'.php');
    }
    
    /**
     * @return void
     */
    public function display() {
        foreach ( $this->particles as $name => $particle ) {
            $this->particles[$name]['content'] = $this->renderParticle($particle['path'], $particle['data']);
        }
        $this->renderLayout();
    }
    
    /**
     * @param string $path
     * @param string $data
     */
    private function renderParticle( $path, $data ) {
        extract($data);
        ob_start();
        ob_implicit_flush(false);
        require $path;
        return ob_get_clean();
    }
    
    /**
     * @return void
     */
    private function renderLayout() {
        require $this->layout;
    }
    
    /**
     * @param array $parameters
     */
    abstract public function run( $parameters=array() );
}