<?php
/**
 * The action file for movie/poster/view action.
 */
namespace X\Module\Backend\Action\Movie\Poster;

/**
 * 
 */
use X\Core\X;
use X\Module\Backend\Util\Action\Basic;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/poster/view action.
 * @author Unknown
 */
class Download extends Basic { 
    /** 
     * The action handle for index action.
     * @return void
     */ 
    public function runAction( $id ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $content = $movieService->getPoster($id);
        header("content-type:image/jpeg");
        echo $content;
    }
}