<?php
/**
 * Namespace defination
 */
namespace X\Service\XError\Core\Reporter;

/**
 * 
 */
use X\Core\X;
use X\Service\XError\Core\Reporter\Util\ReporterBasic;
use X\Service\XError\Service as ErrorService;

/**
 * The trace reporter
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class Tracker extends ReporterBasic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XError\Reporter\ReporterAbstract::init()
     */
    protected function init() {}
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XError\Reporter\ReporterAbstract::display()
     */
    public function display( $error ) {
        /* @var $errorService ErrorService */
        $errorService = X::system()->getServiceManager()->get(ErrorService::getServiceName());
        $path = $errorService->getPath('Core/View/Trace.php');
        $this->renderView($path, $error);
    }
    
    /**
     * @param unknown $path
     * @param unknown $error
     */
    private function renderView( $path, $error ) {
        extract($error, EXTR_OVERWRITE);
        require $path;
    }
}